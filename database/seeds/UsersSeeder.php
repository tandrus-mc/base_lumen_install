<?php

use Illuminate\Database\Seeder;


class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker\Factory::create();

        $user = new \App\User();
        $user->config_id = $faker->numberBetween(1200, 1500);
        $user->role_id   = \App\Role::where('name', '=', 'Admin')->first()->id;
        $user->login     = 'admin';
        $user->password    = app('hash')->make('admin_pass');
//        $user->api_token = /*str_random(60)*/ 'admin';
        $user->save();

        $user = new \App\User();
        $user->config_id = $faker->numberBetween(1200, 1500);
        $user->role_id   = \App\Role::where('name', '=', 'Manager')->first()->id;
        $user->login     = 'manager';
        $user->password    = app('hash')->make('manager_pass');
//        $user->api_token = /*str_random(60)*/ 'manager';
        $user->save();

        $user = new \App\User();
        $user->config_id = $faker->numberBetween(1200, 1500);
        $user->role_id   = \App\Role::where('name', '=', 'Client')->first()->id;
        $user->login     = 'client_1';
        $user->password    = app('hash')->make('client_1_pass');
//        $user->api_token = /*str_random(60)*/ 'client1';
        $user->save();

        $user = new \App\User();
        $user->config_id = $faker->numberBetween(1200, 1500);
        $user->role_id   = \App\Role::where('name', '=', 'Client')->first()->id;
        $user->login     = 'client_2';
        $user->password    = app('hash')->make('client_2_pass');
//        $user->api_token = /*str_random(60)*/ 'client2';
        $user->save();

    }
}
