<?php

namespace App;

use App\UserImplementations\HasRole;
use App\UserImplementations\HasRoleContract;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject as JWTSubjectContract;
use App\UserImplementations\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, HasRoleContract, JWTSubjectContract
{

    use Authenticatable, Authorizable, HasRole, JWTSubject;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function leadLists(){

        return $this->belongsToMany('App\LeadList', 'user_lead_list', 'user_id', 'lead_list_id');

    }

}
