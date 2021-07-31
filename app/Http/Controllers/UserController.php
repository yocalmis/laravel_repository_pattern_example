<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helper\ControlHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Mail;
use Validator;

class UserController extends Controller
{

    public function index(Request $request)
    {
        if (!$request->session()->has('em')) {return redirect('login');}
        $data["name"] = $request->session()->get("em");
        // return view('index', $data);
        return redirect('vehicles');
    }

    public function login(Request $request)
    {
        if ($request->session()->has('em')) {return redirect('dashboard');}
        return view('login');
    }

    public function login_control(Request $request)
    {
        if ($request->session()->has('em')) {return redirect('dashboard');}
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('login')->withErrors($validator)->withInput();
        }

        $em = ControlHelper::test_input($request->input('email'));
        $ps = md5(ControlHelper::test_input($request->input('password')));

        if (count(User::where('email', $em)->where('password', $ps)->get()) == 0) {return redirect('login')->with('error', 'Member not found!');}

        $token = md5($request->input('email') . "_" . date("Y-m-d H:i:s"));
        $user = User::where('email', $em)
            ->update(['token' => $token]);

        ControlHelper::sess($request, $em, $token);
        return redirect('dashboard');

    }

    public function logout(Request $request)
    {

        $request->session()->flush();
        return Redirect('login')->with('message', 'Logout Successful!');

    }

    public function forgot_password(Request $request)
    {
        if ($request->session()->has('em')) {return redirect('dashboard');}
        return view('forgot-password');
    }

    public function register(Request $request)
    {
        if ($request->session()->has('em')) {return redirect('dashboard');}
        return view('register');
    }

    public function register_success(Request $request)
    {
        if ($request->session()->has('em')) {return redirect('dashboard');}
        if ($request->input('repass') != $request->input('pass')) {
            return redirect('register')->with('error', 'Passwords do not match!');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users|max:255',
            'email' => 'required|unique:users',
            'pass' => 'required',
            'repass' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('register')->withErrors($validator)->withInput();
        }

        $user = new User;
        $user->name = ControlHelper::test_input($request->input('name'));
        $user->email = ControlHelper::test_input($request->input('email'));
        $user->password = md5(ControlHelper::test_input($request->input('pass')));
        $user->validation = "123456";
        $user->status = 1;
        $user->save();
        return Redirect('register')->with('message', 'Registration Successful!');
    }

    public function forgot_send(Request $request)
    {
        if ($request->session()->has('em')) {return redirect('dashboard');}
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('forgot_password')->withErrors($validator)->withInput();
        }

        $em = ControlHelper::test_input($request->input('email'));
        if (count(User::where('email', $em)->get()) == 0) {return redirect('forgot_password')->with('error', 'E-Mail adress not found!');}

        $valid = rand(100000000, 999999999);
        $user = User::where('email', $request->input('email'))
            ->update(['validation' => $valid]);

        $array = [
            'sifre' => $valid,
            'to' => $em,
        ];
        mail::send('iletisim', $array, function ($message) use ($array) {
            $message->from('yusuf@zirvekayseri.com', 'İletişim');
            $message->subject("Şifre Yenileme İsteği");
            $message->to($array["to"]);
        });
        return redirect('forgot_password')->with('message', 'E-Mail sent for password reset!');

    }

    public function new_pass(Request $request, $id)
    {
        if ($request->session()->has('em')) {return redirect('dashboard');}
        if (count(User::where('validation', ControlHelper::test_input($id))->get()) == 0) {return redirect('forgot_password')->with('error', 'Invalid reset code!');}
        return view('new_pass', ['val' => $id]);

    }

    public function new_pass_send(Request $request)
    {
        if ($request->session()->has('em')) {return redirect('dashboard');}
        if ($request->input('repass') != $request->input('pass')) {return redirect('new_pass/' . $request->input('val'))->with('error', '
Passwords do not match!');}

        $val = ControlHelper::test_input($request->input('val'));
        if (count(User::where('validation', $val)->get()) == 0) {return redirect('forgot_password')->with('error', 'Invalid reset code!');}

        $user = User::where('validation', $val)
            ->update(['password' => md5(ControlHelper::test_input($request->input('pass')))]);

        return redirect('login')->with('message', '
Password change successful!');

    }

}
