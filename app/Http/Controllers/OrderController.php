<?php

namespace App\Http\Controllers;

use App\Events\BalanceHistoryEvent;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function getOrders()
    {

    }

    private function generateOrderNumber()
    {
        return "PKS".$this->uniqueId(10);
    }

    /**
     * @throws \Exception
     */
    public function uniqueId($length = 10) {
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($length / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
        } else {
            throw new \Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $length);
    }

    public function createOrder(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'service_id' => 'required',
            'car_id' => 'required',
            'price' => 'required'
        ]);

        if ($validation->fails()) {
            return response(['message' => 'All fields are required']);
        }

        $nonPublishedServices = Service::whereIn('id', $request->service_id)->nonPublished()->count();

        if ($nonPublishedServices > 0) {
            return response([
                'message' => 'Sended service not usable'
            ]);
        }

        $totalServicePrice = Service::whereIn('id', $request->service_id)->published()->sum('service_price');
        $userBalance = auth()->user()->balance;

        if ($totalServicePrice <> $request->price) {
            return response([
                'message' => 'Sended price not equal to the total price of services'
            ]);
        }

        if($userBalance < $totalServicePrice){
            return response([
                'message' => 'User balance insufficient'
            ]);
        }

        DB::beginTransaction();

        try {
            $order = new Order();
            $order->order_number = $this->generateOrderNumber();
            $order->car_id = $request->car_id;
            $order->user_id = auth()->user()->id;
            $order->total_price = $totalServicePrice;
            $order->order_status = 1;

            $order->save();

            foreach ($request->service_id as $serviceId) {
                $orderItem = new OrderItem();
                $orderItem->service_id = $serviceId;
                $order->order_items()->save($orderItem);
            }

            auth()->user()->balance -= $totalServicePrice;
            auth()->user()->save();
            $data = [
                'type'=>0,
                'price'=>$totalServicePrice,
                'order_id'=>$order->id
            ];
            event(new BalanceHistoryEvent($data));

            DB::commit();

            return response([
                'message'=>'Your order has been successfully created. Order Number='.$order->order_number
            ], Response::HTTP_OK);

        } catch (\Exception $exception) {
            DB::rollback();
            throw new Exception('An error occurred while ordering.');
        }
    }
}
