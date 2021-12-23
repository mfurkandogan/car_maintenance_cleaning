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
                'errors' => $validation->errors()->toArray()
                ],\Symfony\Component\HttpFoundation\Response::HTTP_NOT_ACCEPTABLE);
        }



        DB::beginTransaction();

        try {
            $oldBalance = auth()->user()->balance;
            auth()->user()->balanceTransAction(1,$request->input('price'));
            $newBalance = auth()->user()->balance;

            $data = [
                'type'=>1,
                'price'=>$request->input('price')
            ];
            event(new BalanceHistoryEvent($data));
            DB::commit();
            return response([
                'oldBalance' => number_format($oldBalance,2,'.',''),
                'newBalance'=> number_format($newBalance,2,'.','')
            ],\Symfony\Component\HttpFoundation\Response::HTTP_OK);


        } catch (\Exception $exception) {
            DB::rollback();
            return response([
                'message'=>$exception->getMessage()
            ],\Symfony\Component\HttpFoundation\Response::HTTP_PAYMENT_REQUIRED);

        }
    }
}
