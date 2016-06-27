<?php

use Illuminate\Database\Seeder;


class LeadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $users = \App\User::all();

        foreach($users as $user){

            if($user->isClient()){
                for($i = 0; $i < 20; $i++){
                    \App\Lead::create([
                        'config_id'         => $user->config_id,
                        'email'             => $faker->email,
                        'ip'                => $faker->ipv4,
                        'referring_url'     => $faker->url,
                        'home_phone'        => $faker->phoneNumber,
                        'first_name'        => $faker->firstName,
                        'last_name'         => $faker->lastName,
                        'address'           => $faker->streetAddress,
                        'city'              => $faker->city,
                        'state'             => 'CA',
                        'zip'               => $faker->postcode,
                        'country'           => $faker->country,
                        'registration_date' => $faker->time('m/d/Y H:i'),
                        'capture_date'      => strtotime($faker->time()),
                    ]);
                }
            }

        }
    }
}