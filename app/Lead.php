<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'config_id',
        'email',
        'ip',
        'referring_url',
        'home_phone',
        'first_name',
        'last_name',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'registration_date',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];
}