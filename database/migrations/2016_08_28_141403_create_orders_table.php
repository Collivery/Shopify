<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->bigInteger('shop_id');
            $table->unsignedBigInteger('shopify_order_id');
            $table->longText('order_status_url');
            $table->unsignedBigInteger('waybill_number');
            $table->unsignedInteger('order_number');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('shop_id')->references('shops')->on('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('orders', function(Blueprint $table){
            $table->dropForeign('shop_id');
        });
    }
}
