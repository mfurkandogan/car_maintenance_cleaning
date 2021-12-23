<?php

namespace App\Http\Controllers;

use App\Events\BalanceHistoryEvent;
use App\Helpers\Helper;
use App\Http\Resources\OrderCollection;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function getOrders(Request $request)
    {
        $limit = $request->limit ?? 25;
        $offset = $request->offset ?? 0;
        $service_id = $request->service_id ?? 0;

        $orders = Order::with('order_items.service');
        if ($service_id > 0) {
            $orders = $orders->where('service_id', $service_id);
        }
        $orders = $orders->skip($offset)->limit($limit)->get();
        return new OrderCollection($orders);
    }

    public function createOrder(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'service_id' => 'required',
            'car_id' => 'required',
            'price' => 'required'
        ]);

        if ($validation->fails()) {
            return response(['message' => 'All fields are required', 'errors' => $validation->errors()->toArray()],
                Response::HTTP_NOT_ACCEPTABLE);
        }

        if (!Redis::exists('cars_' . $request->car_id)) {
            return response([
                'message' => 'Car not found'
            ],Response::HTTP_NOT_ACCEPTABLE);
        }

        $nonPublishedServices = Service::whereIn('id', $request->service_id)->nonPublished()->count();

        if ($nonPublishedServices > 0) {
            return response([
                'message' => 'Sended service not usable'
            ],Response::HTTP_NOT_ACCEPTABLE);
        }

        $totalServicePrice = Service::whereIn('id', $request->service_id)->published()->sum('service_price');
        $userBalance = auth()->user()->balance;

        if ($totalServicePrice <> $request->price) {
            return response([
                'message' => 'Sended price not equal to the total price of services'
            ],Response::HTTP_NOT_ACCEPTABLE);
        }

        if ($userBalance < $totalServicePrice) {
            return response([
                'message' => 'User balance insufficient'
            ],Response::HTTP_NOT_ACCEPTABLE);
        }

        DB::beginTransaction();

        try {
            $order = new Order();
            $order->order_number = Helper::generateOrderNumber();
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

            auth()->user()->balanceTransAction(0, $request->input('price'));

            $data = [
                'type' => 0,
                'price' => $totalServicePrice,
                'order_id' => $order->id
            ];
            event(new BalanceHistoryEvent($data));

            DB::commit();

            return response([
                'message' => 'Your order has been successfully created.',
                'orderNumber' => $order->order_number,
                'balance' => $userBalance
            ],Response::HTTP_OK);

        } catch (\Exception $exception) {
            return response([
                'message'=>$exception->getMessage()
            ],Response::HTTP_NOT_ACCEPTABLE);
        }
    }
}
