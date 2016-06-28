<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('RolesSeeder');
        $this->call('UsersSeeder');
        $this->call('LeadsSeeder');
        $this->call('LeadListsSeeder');
    }
}
