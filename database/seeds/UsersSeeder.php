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
        $user->name = 'Tim';
        $user->api_token = '1234';
        $user->save();

    }
}
