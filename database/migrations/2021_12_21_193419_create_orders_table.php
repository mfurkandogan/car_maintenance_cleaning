<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id')->index();
            $table->string('order_number')->index();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->bigInteger('car_id');
            $table->decimal('total_price',8,2);
            $table->tinyInteger('order_status')->default(0);
            $table->timestamps();
        });

        Schema::table('orders', function($table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
