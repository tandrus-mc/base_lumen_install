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

        $user = new \App\User();
        $user->name = 'Admin';
        $user->api_token = /*str_random(60)*/ 1234;
        $user->save();

        $user = new \App\User();
        $user->name = 'Manager';
        $user->api_token = str_random(60);
        $user->save();

        $user = new \App\User();
        $user->name = 'Client';
        $user->api_token = str_random(60);
        $user->save();

    }
}
