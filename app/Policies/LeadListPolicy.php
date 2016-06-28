<?php

namespace App\Policies;


use App\LeadList;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeadListPolicy
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

    public function show(User $user, LeadList $leadList){



    }

    public function update(User $user, LeadList $leadList){

        return ($user->isAdmin() || $user->isManager() || $user->leadLists()->find($leadList->id));

    }

}