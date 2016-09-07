<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shop_id');
            $table->unsignedBigInteger('shopify_order_id');
            $table->longText('order_status_url');
            $table->string('waybill_number')->default(null);
            $table->string('order_number');

            //0  not processed, 1 collivery added,  2 quote accepted, 3 fulfilled
            $table->unsignedSmallInteger('status')->default(0);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('shop_id')->references('id')->on('shops');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('orders', function (Blueprint $table) {
            $table->dropForeign('shop_id');
        });
    }
}
