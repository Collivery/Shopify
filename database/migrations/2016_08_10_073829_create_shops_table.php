<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
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
            $table->string('nonce');
            $table->string('access_token');

            $table->integer('user_id')->unsigned();
            $table->integer('carrier_id')->unsigned();

            //0 never installed, 1 installed, 2 uninstalled
            $table->smallInteger('app_installed')->default(0);
            $table->smallInteger('carrier_installed')->default(0);
            $table->smallInteger('webhooks_installed')->default(false);

            $table->dateTime('carrier_installed_on')->nullable();
            $table->dateTime('carrier_uninstalled_on')->nullable();
            $table->dateTime('webhooks_installed_on')->nullable();
            $table->dateTime('webhooks_uninstalled_on')->nullable();
            $table->dateTime('app_installed_on');
            $table->dateTime('app_uninstalled_on')->nullable();
            $table->dateTime('app_updated_on');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shops', function (Blueprint $table) {
            $table->dropForeign('user_id');
        });
    }
}
