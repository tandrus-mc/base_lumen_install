<?php


namespace App\Http\Controllers;


use App\Lead;
use App\Transformers\LeadTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Access\Gate;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Tymon\JWTAuth\JWTAuth;

class LeadsController extends ApiController
{
    protected $leads;

    public function __construct(Lead $leads, Gate $gate, JWTAuth $JWTAuth){

        parent::__construct($JWTAuth, $gate, new LeadTransformer());

        $this->leads = $leads;

    }

    public function index(){

        if($this->user->isClient()){

            $paginator = $this->leads->where('config_id', '=', $this->user->config_id)->paginate();

        } else {

            $paginator = $this->leads->paginate();

        }

        $leads = $paginator->getCollection();

        return $this->respondSuccess(
            (new Collection($leads, $this->transformer, 'Lead'))
                ->setPaginator(new IlluminatePaginatorAdapter($paginator))
        );

    }

    public function create(){
        //
    }

    public function store(Request $request){

        $lead = $this->leads->create($this->validateLead($request)->all());

        $lead->forceFill([
            'capture_date' => strtotime(Carbon::now()->toDateTimeString()),
            'config_id'    => $this->user->config_id
        ])->save();

        return $this->respondSuccess(new Item($lead, $this->transformer, 'Lead'), 201);

    }

    public function show($id){

        $lead = $this->leads->find($id);

        if(!$lead){

            $response = $this->respondError('This lead cannot be found');

        } else {

            if($this->gate->allows('show', $lead)){

                $response = $this->respondSuccess(new Item($lead, $this->transformer, 'Lead'));

            } else {

                $response = $this->respondPrivilegeError();

            }

        }

        return $response;

    }

    public function edit($id){
        //
    }

    public function update(Request $request, $id){

        $lead = $this->leads->find($id);

        if(!$lead){

            $response = $this->respondError('This lead cannot be found');

        } else {

            if($this->gate->allows('update', $lead)){

                $lead->update($this->validateLead($request)->all());

                $response = $this->respondSuccess(new Item($lead, $this->transformer, 'Lead'));

            } else{

                $response = $this->respondPrivilegeError();

            }

        }

        return $response;

    }

    public function destroy($id){

        $lead = $this->leads->find($id);

        if(!$lead){

            $response = $this->respondError('This lead cannot be found');

        } else {

            if($this->gate->allows('destroy', $lead)){

                $lead->delete();

                $response = $this->respondSuccess(new Item($lead, $this->transformer, 'Lead'), 410);

            } else {

                $response = $this->respondPrivilegeError();

            }

        }

        return $response;

    }

    protected function validateLead(Request $request){

        /*TO DO: COMPLETE CUSTOM VALIDATION*/
        $this->validate($request, [
            'config_id'         => 'required|numeric',
            'email'             => 'required|email',
            'ip'                => 'required|ip',
            'referring_url'     => 'required|url',
            'registration_date' => 'required|date_format:m/d/Y H:i',
//            'home_phone'        => '',
            'first_name'        => 'alpha',
            'last_name'         => 'alpha',
//            'address'           => '',
            'city'              => 'alpha',
            'state'             => 'alpha',
            'zip'               => 'numeric',
            'country'           => 'alpha',
        ]);

        return $request;
    }

}