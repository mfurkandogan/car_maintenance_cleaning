<?php

namespace App\Http\Controllers;

use App\Console\Commands\GetCars;
use App\Http\Resources\CarCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;


class CarController extends Controller
{
    public function getCars(Request $request)
    {
        $limit = $request->limit ?? 25;
        $offset = $request->offset ?? 0;

        if ($cars = Redis::get(GetCars::CAR_REDIS_KEY)) {

            $carsArray = json_decode($cars, true);
            $limitedData = array_slice($carsArray, $offset, $limit);
            return new CarCollection($limitedData);
        } else {
            return response([
                'message' => 'Failed to load vehicle information'
            ], Response::HTTP_OK);
        }
    }
}
