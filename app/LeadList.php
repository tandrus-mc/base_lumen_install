<?php


namespace App;



use Illuminate\Database\Eloquent\Model;

class LeadList extends Model
{

    protected $fillable = [
        'list_name',
        'column_keys',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function leads(){

        return $this->belongsToMany('App\Lead', 'lead_lead_list', 'lead_list_id', 'lead_id');

    }

    public function users(){

        return $this->belongsToMany('App\User', 'user_lead_list', 'lead_list_id', 'user_id');

    }

}