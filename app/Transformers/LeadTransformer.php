<?php


namespace App\Transformers;


use App\Lead;
use League\Fractal\TransformerAbstract;

class LeadTransformer extends TransformerAbstract
{
    public function transform(Lead $lead){
        return [
            'id'                => $lead->id,
            'config_id'         => $lead->config_id,
            'email'             => $lead->email,
            'ip'                => $lead->ip,
            'referring_url'     => $lead->referring_url,
            'home_phone'        => $lead->home_phone,
            'first_name'        => $lead->first_name,
            'last_name'         => $lead->last_name,
            'address'           => $lead->address,
            'city'              => $lead->city,
            'state'             => $lead->state,
            'zip'               => $lead->zip,
            'country'           => $lead->country,
            'registration_date' => $lead->registration_date,
            /*'capture_date'      => $lead->capture_date->format('m/d/Y H:i'),*/
        ];
    }
}