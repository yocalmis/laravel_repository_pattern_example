<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Repositories\VehicleRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class VehicleService
{
    /**
     * @var $vehicleRepository
     */
    protected $vehicleRepository;

    /**
     * VehicleService constructor.
     *
     * @param VehicleRepository $vehicleRepository
     */
    public function __construct(VehicleRepository $vehicleRepository)
    {
        $this->vehicleRepository = $vehicleRepository;
    }

    /**
     * Delete vehicle by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById($id)
    {
        DB::beginTransaction();

        try {
            $vehicle = $this->vehicleRepository->delete($id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete post data');
        }

        DB::commit();

        return $vehicle;

    }

    /**
     * Get all vehicle.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->vehicleRepository->getAll();
    }

    /**
     * Get vehicle by id.
     *
     * @param $id
     * @return String
     */
    public function getById($id)
    {
        return $this->vehicleRepository->getById($id);
    }

    /**
     * Update vehicle data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function updateVehicle($data, $id)
    {
        $validator = Validator::make($data, [
   
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        DB::beginTransaction();

        try {
            $vehicle = $this->vehicleRepository->update($data, $id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update vehicle data');
        }

        DB::commit();

        return $vehicle;

    }

    /**
     * Validate vehicle data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function saveVehicleData($data)
    {
        $validator = Validator::make($data, [

        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }

        $result = $this->vehicleRepository->save($data);

        return $result;
    }

}