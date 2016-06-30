<?php


namespace App\Http\Controllers;


use App\Lead;
use App\LeadList;
use App\Transformers\LeadListTransformer;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

class LeadListsController extends ApiController
{

    protected $leadLists;
    protected $lead;

    /**
     * LeadListsController constructor.
     * @param LeadList $leadList
     * @param Lead $lead
     * @param JWTAuth $JWTAuth
     * @param Gate $gate
     */
    public function __construct(LeadList $leadList, Lead $lead, JWTAuth $JWTAuth, Gate $gate){

        parent::__construct($JWTAuth, $gate, new LeadListTransformer());

        $this->leadLists = $leadList;
        $this->lead      = $lead;

    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(){

        if($this->user->isClient()){

            $paginator = $this->user->leadLists()->paginate();

        } else {

            $paginator = $this->leadLists->paginate();

        }

        $leadLists = $paginator->getCollection();

        $this->setResponse($this->okResponse($this->prepareResource($leadLists, $paginator)))->sendResponse();

    }

    public function create(){
        //
    }


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request){

        $this->validate($request, [
           'list_name' => 'required'
        ]);

        $leadList = $this->user->leadLists()->create($request->all());

        $this->setResponse($this->createdResponse($this->prepareResource($leadList)))->sendResponse();

    }

    public function show($id){

    }

    public function edit($id){
        //
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id){

        $leadList = $this->leadLists->find($id);

        if(!$leadList){

            $this->setResponse($this->notFoundResponse('This LeadList cannot be found.'));

        } else {

            if($this->gate->allows('update', $leadList)){

                $lead = $this->lead->find($request->lead_id);

                if(!$lead){

                    $this->setResponse($this->notFoundResponse('The specified Lead could not be found.'));

                } else {

                    if($this->gate->allows('show', $lead)){

                        $leadList->leads()->attach($lead);

                        $this->setResponse($this->okResponse($this->prepareResource($leadList)));

                    } else {

                        $this->setResponse($this->unauthorizedResponse());

                    }

                }

            } else {

                $this->setResponse($this->unauthorizedResponse());

            }

        }

        $this->sendResponse();

    }

    public function destroy($id){

    }

}