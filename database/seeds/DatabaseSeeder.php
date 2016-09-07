<?php

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\DB;

    class DatabaseSeeder extends Seeder
    {
        protected $drop = ['users'];

        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            foreach ($this->drop as $key => $value) {
                DB::table($value)->truncate($value);
            }

            Model::unguard();
            $this->call(UsersTableSeeder::class);
            Model::reguard();
        }
    }
