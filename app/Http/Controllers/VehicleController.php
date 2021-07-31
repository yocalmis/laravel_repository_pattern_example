<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helper\ControlHelper;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Validator;
use App\Services\VehicleService;

class VehicleController extends Controller
{
	
	protected $vehicleService;
	
    public function __construct(VehicleService $vehicleService)
    {
        $this->vehicleService = $vehicleService;
    }	

      public function index()
    {
        $result = ['status' => 200];

        try {
            $result['data'] = $this->vehicleService->getAll();
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }
	
	public function create()
    {
        //
    }
	
	
	public function store(Request $request)
    {
        $data = $request->only([
        ]);

        $result = ['status' => 200];

        try {
            $result['data'] = $this->vehicleService->saveVehicleData($data);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    /**
     * Display the specified resource.
     *
     * @param  id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = ['status' => 200];

        try {
            $result['data'] = $this->vehicleService->getById($id);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result, $result['status']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Vehicle $vehicle
     * @return \Illuminate\Http\Response
     */
    public function edit(Vehicle $vehicle)
    {
        //
    }

    /**
     * Update post.
     *
     * @param Request $request
     * @param id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->only([
        ]);

        $result = ['status' => 200];

        try {
            $result['data'] = $this->vehicleService->updateVehicle($data, $id);

        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }

        return response()->json($result, $result['status']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = ['status' => 200];

        try {
            $result['data'] = $this->vehicleService->deleteById($id);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result, $result['status']);
    }
}
