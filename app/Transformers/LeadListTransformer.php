<?php


namespace App\Transformers;


use App\LeadList;
use League\Fractal\TransformerAbstract;

class LeadListTransformer extends TransformerAbstract
{

    protected $availableIncludes = [
        'lead'
    ];

    public function transform(LeadList $leadList){
        return [
            'id'   => $leadList->id,
            'name' => $leadList->list_name
        ];
    }

    public function includeLead(LeadList $leadList){

        $leads = $leadList->leads;

        return $this->collection($leads, new LeadTransformer(), 'Lead');

    }
}