<?php

    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class CreateLogsTable extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up()
        {
            Schema::create('logs', function (Blueprint $table) {
                $table->increments('id')->unsigned();

                $table->integer('shop_id')->unsigned();

                $table->ipAddress('ip');

                $table->text('headers')->nullable();
                $table->text('payload')->nullable();

                $table->timestamps();

                $table->foreign('shop_id')->references('id')->on('users');

                $table->softDeletes();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down()
        {
            Schema::drop('logs', function (Blueprint $table) {
                $table->dropForeign('shop_id');
            });
        }
    }
