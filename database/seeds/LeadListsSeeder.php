<?php

use Illuminate\Database\Seeder;


class LeadListsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = \App\User::all();

        foreach($users as $user){

            if($user->isClient()){

                \App\Lead::where('config_id', '=', $user->config_id)->chunk(10, function($leads) use ($user){

                    $leadList = $user->leadLists()->create(['list_name' => 'list_'.rand(1, 100)]);

                    foreach($leads as $lead){

                        $leadList->leads()->attach($lead);

                    }

                });

            }

        }
    }
}