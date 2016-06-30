<?php


namespace App\Http\Controllers;


use App\Lead;
use App\Transformers\LeadTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Access\Gate;
use Tymon\JWTAuth\JWTAuth;

class LeadsController extends ApiController
{
    protected $leads;

    /**
     * LeadsController constructor.
     * @param Lead $leads
     * @param Gate $gate
     * @param JWTAuth $JWTAuth
     */
    public function __construct(Lead $leads, Gate $gate, JWTAuth $JWTAuth){

        parent::__construct($JWTAuth, $gate, new LeadTransformer());

        $this->leads = $leads;

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(){

        if($this->user->isClient()){

            $paginator = $this->leads->where('config_id', '=', $this->user->config_id)->paginate();

        } else {

            $paginator = $this->leads->paginate();

        }

        $leads = $paginator->getCollection();

        $this->setResponse($this->okResponse($this->prepareResource($leads, $paginator)))->sendResponse();

    }


    public function create(){
        //
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request){

        $lead = $this->leads->create($this->validateLead($request)->all());

        $lead->forceFill([
            'capture_date' => strtotime(Carbon::now()->toDateTimeString()),
            'config_id'    => $this->user->config_id
        ])->save();

        $this->setResponse($this->createdResponse($this->prepareResource($lead)))->sendResponse();

    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show($id){

        $lead = $this->leads->find($id);

        if(!$lead){

            $this->setResponse($this->notFoundResponse('This lead cannot be found'));

        } else {

            if($this->gate->allows('show', $lead)){

                $this->setResponse($this->okResponse($this->prepareResource($lead)));

            } else {

                $this->setResponse($this->unauthorizedResponse());

            }

        }

        $this->sendResponse();

    }

    public function edit($id){
        //
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, $id){

        $lead = $this->leads->find($id);

        if(!$lead){

            $this->setResponse($this->notFoundResponse('This lead cannot be found'));

        } else {

            if($this->gate->allows('update', $lead)){

                $lead->update($this->validateLead($request)->all());

                $this->setResponse($this->okResponse($this->prepareResource($lead)));

            } else{

                $this->setResponse($this->unauthorizedResponse());

            }

        }

        $this->sendResponse();

    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id){

        $lead = $this->leads->find($id);

        if(!$lead){

            $this->setResponse($this->notFoundResponse('This lead cannot be found'));

        } else {

            if($this->gate->allows('destroy', $lead)){

                $lead->delete();

                $this->setResponse($this->okResponse($this->prepareResource($lead)));

            } else {

                $this->setResponse($this->unauthorizedResponse());

            }

        }

        $this->sendResponse();

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