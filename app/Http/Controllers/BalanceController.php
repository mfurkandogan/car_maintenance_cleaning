<?php

namespace App\Http\Controllers;

use App\Events\BalanceHistoryEvent;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function addBalance(Request $request)
    {
        //TODO validate
        $oldBalance = auth()->user()->balance;
        $newBalance = auth()->user()->balance += $request->input('price');

        if(auth()->user()->save()){
            $data = [
              'type'=>1,
              'price'=>$request->input('price')
            ];
            event(new BalanceHistoryEvent($data));
            return response([
                'oldBalance' => number_format($oldBalance,2,'.',''),
                'newBalance'=> number_format($newBalance,2,'.','')
            ], \Symfony\Component\HttpFoundation\Response::HTTP_OK);
        } else {
            return response([
                'oldBalance' => number_format($oldBalance,2,'.',''),
                'newBalance'=> '-'
            ],\Symfony\Component\HttpFoundation\Response::HTTP_OK);
        }
    }
}
