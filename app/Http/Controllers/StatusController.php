<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatusResource;
use App\Models\Status;

class StatusController extends Controller
{
    public function getOrderStatus()
    {
        $statuses = Status::published()->get();
        return new StatusResource($statuses);
    }
}
