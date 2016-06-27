<?php


namespace App;


use Carbon\Carbon;
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
        'updated_at',
        'capture_date'
    ];

    public function getCaptureDateAttribute(){

        return Carbon::createFromFormat('m/d/Y H:i', $this->attributes['capture_date']);

    }

    public function setCaptureDateAttribute($date){

        $this->attributes['capture_date'] = Carbon::createFromTimestamp($date)->format('m/d/Y H:i');

    }

}