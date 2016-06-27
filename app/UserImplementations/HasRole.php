<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 6/26/16
 * Time: 11:11 PM
 */

namespace App\UserImplementations;


trait HasRole
{

    public function role(){

        return $this->belongsTo('App\Role');

    }

    public function isAdmin(){

        return $this->role()->first()->name == 'Admin';

    }

    public function isManager(){

        return $this->role()->first()->name == 'Manager';

    }

    public function isClient(){

        return $this->role()->first()->name == 'Client';

    }

}