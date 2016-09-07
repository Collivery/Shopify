<?php

    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class CreateUsersTable extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up()
        {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id')->unsigned();

                //collivery user_id
                $table->integer('user_id')->unsigned()->default(0);

                $table->string('name')->nullable();
                $table->string('email')->unique();
                $table->string('password');

                $table->boolean('active')->default(false);

                $table->rememberToken();

                $table->timestamps();

                $table->softDeletes();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down()
        {
            Schema::drop('users');
        }
    }
