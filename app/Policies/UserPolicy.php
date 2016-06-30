<?php

namespace App\Policies;


use App\Lead;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index(User $user){

        return ($user->isAdmin() || $user->isManager());

    }

}