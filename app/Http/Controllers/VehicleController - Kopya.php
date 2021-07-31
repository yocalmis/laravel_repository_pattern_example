<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helper\ControlHelper;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Validator;

class VehicleController extends Controller
{

    public function index(Request $request)
    {
        if (!$request->session()->has('em')) {return redirect('login');}
        $data["name"] = $request->session()->get("em");
        $data["vehicles"] = Vehicle::all();
        return view('vehicle', $data);
    }

    public function add(Request $request)
    {
        if (!$request->session()->has('em')) {return redirect('login');}
        $data["name"] = $request->session()->get("em");
        $data["users"] = User::all();
        return view('vehicle_form', $data);
    }

    public function add_save(Request $request)
    {
        if (!$request->session()->has('em')) {return redirect('login');}

        $validator = Validator::make($request->all(), [
            'plate' => 'required|unique:vehicles',
            'nickname' => 'required|unique:vehicles',
            'authentication' => 'required',
            'brand' => 'required',
            'model' => 'required',
            'year' => 'required',
            'type' => 'required',
            'color' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('vehicles/add')->withErrors($validator)->withInput();
        }

        $vehicle = new Vehicle;
        $vehicle->plate = ControlHelper::test_input($request->input('plate'));
        $vehicle->nickname = ControlHelper::test_input($request->input('nickname'));
        $vehicle->authentication = ControlHelper::test_input($request->input('authentication'));
        $vehicle->brand = ControlHelper::test_input($request->input('brand'));
        $vehicle->model = ControlHelper::test_input($request->input('model'));
        $vehicle->year = ControlHelper::test_input($request->input('year'));
        $vehicle->type = ControlHelper::test_input($request->input('type'));
        $vehicle->color = ControlHelper::test_input($request->input('color'));
        $vehicle->status = ControlHelper::test_input($request->input('status'));

        $vehicle->save();
        return Redirect('vehicles/add')->with('message', 'Vehicle Insert Successful!');

    }

    public function edit(Request $request, $id)
    {

        if (!$request->session()->has('em')) {return redirect('login');}
        $data["name"] = $request->session()->get("em");
        $data["vehicles"] = Vehicle::where('id', $id)->get();
        $data["users"] = User::all();
        $data["id"] = $id;
        return view('vehicle_form_edit', $data);
    }

    public function edit_save(Request $request)
    {
        if (!$request->session()->has('em')) {return redirect('login');}

        $validator = Validator::make($request->all(), [
            'plate' => 'required|unique:vehicles,plate,' . $request->input('id'),
            'nickname' => 'required|unique:vehicles,nickname,' . $request->input('id'),
            'authentication' => 'required',
            'brand' => 'required',
            'model' => 'required',
            'year' => 'required',
            'type' => 'required',
            'color' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('vehicles/edit/' . $request->input('id'))->withErrors($validator)->withInput();
        }

        $vehicle = Vehicle::where('id', $request->input('id'))
            ->update(
                [
                    'plate' => ControlHelper::test_input($request->input('plate')),
                    'nickname' => ControlHelper::test_input($request->input('nickname')),
                    'authentication' => ControlHelper::test_input($request->input('authentication')),
                    'brand' => ControlHelper::test_input($request->input('brand')),
                    'model' => ControlHelper::test_input($request->input('model')),
                    'year' => ControlHelper::test_input($request->input('year')),
                    'type' => ControlHelper::test_input($request->input('type')),
                    'color' => ControlHelper::test_input($request->input('color')),
                    'status' => ControlHelper::test_input($request->input('status')),
                ]);

        return Redirect('vehicles/edit/' . $request->input('id'))->with('message', 'Vehicle Update Successful!');

    }

    public function delete(Request $request, $id)
    {

        if (!$request->session()->has('em')) {return redirect('login');}
        $vehicle = Vehicle::find($id);
        $vehicle->delete();
        return Redirect('vehicles')->with('message', 'Vehicle Delete Successful!');

    }

}
