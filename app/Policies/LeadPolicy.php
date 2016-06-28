<?php

namespace App\Policies;


use App\Lead;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeadPolicy
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

    public function show(User $user, Lead $lead){
        return ($user->isAdmin() || $user->config_id === $lead->config_id);
    }

    public function update(User $user, Lead $lead){
        return ($user->isAdmin() || $user->isManager());
    }

    public function destroy($user, Lead $lead){
        return $user->isAdmin();
    }

}