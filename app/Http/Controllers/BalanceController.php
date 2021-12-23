<?php

namespace App\Http\Controllers;

use App\Events\BalanceHistoryEvent;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BalanceController extends Controller
{
    public function addBalance(Request $request)
    {

        $validation = Validator::make($request->all(),['price' => 'required|numeric']);

        if($validation->fails()){
            return response([
                'message'=>'An error occurred while adding balance',
                'errors' => $validation->errors()->toArray()],
                \Symfony\Component\HttpFoundation\Response::HTTP_NOT_ACCEPTABLE);
        }

        $oldBalance = auth()->user()->balance;
        $newBalance = auth()->user()->balance += $request->input('price');

        DB::beginTransaction();

        try {
            auth()->user()->save();
            $data = [
                'type'=>1,
                'price'=>$request->input('price')
            ];
            event(new BalanceHistoryEvent($data));
            DB::commit();
            return response([
                'oldBalance' => number_format($oldBalance,2,'.',''),
                'newBalance'=> number_format($newBalance,2,'.','')
            ], \Symfony\Component\HttpFoundation\Response::HTTP_OK);


        } catch (\Exception $exception) {
            DB::rollback();
            return response([
                'oldBalance' => number_format($oldBalance,2,'.',''),
                'newBalance'=> $exception->getMessage()
            ],\Symfony\Component\HttpFoundation\Response::HTTP_PAYMENT_REQUIRED);

        }
    }
}
