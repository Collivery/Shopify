<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (Schema::hasTable('shops')) {
            $this->down();
        }

        Schema::create('shops', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();

            $table->string('shop');
            $table->string('name')->nullable();
            $table->string('nonce');
            $table->string('access_token');

            $table->integer('user_id')->unsigned();
            $table->integer('carrier_id')->unsigned();

            //contact info
            $table->string('email');
            $table->string('province');
            $table->string('province_code');
            $table->string('country');
            $table->string('country_code');
            $table->string('zip');
            $table->string('city');
            $table->string('phone');
            $table->string('customer_email');
            $table->string('address1');
            $table->string('address2');

            //0 never installed, 1 installed, 2 uninstalled
            $table->smallInteger('installed')->default(0);
            $table->dateTime('installed_at');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('shops', function (Blueprint $table) {
            $table->dropForeign('user_id');
        });
    }
}
