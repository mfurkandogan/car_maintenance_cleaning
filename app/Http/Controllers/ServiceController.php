<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceController extends Controller
{
    public function getServices()
    {
        $services = Service::published()->get();
        return response()->json($services);
    }
}
