<?php

use Illuminate\Database\Seeder;


class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $role = new \App\Role();
        $role->name = 'Admin';
        $role->slug = 'admin';
        $role->save();

        $role = new \App\Role();
        $role->name = 'Manager';
        $role->slug = 'manager';
        $role->save();

        $role = new \App\Role();
        $role->name = 'Client';
        $role->slug = 'client';
        $role->save();

    }
}