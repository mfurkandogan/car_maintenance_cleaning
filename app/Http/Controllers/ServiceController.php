<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServiceCollection;
use App\Models\Service;

class ServiceController extends Controller
{
    public function getServices()
    {
        $services = Service::published()->get();
        return new ServiceCollection($services);
    }
}
