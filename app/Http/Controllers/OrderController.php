<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getOrders()
    {

    }

    public function createOrder()
    {
        /** parametre olarak buraya service id multiple , car _ id => user balance eger yetiyorsa payment completed ,
         * history e eklenip, user ın balance dan total price düşecek
        **/
    }
}
