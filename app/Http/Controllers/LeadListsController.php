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

    protected $leadList;
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

        $this->leadList = $leadList;
        $this->lead     = $lead;

    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request){


        /*if($request->input('include') == 'lead'){

            $this->manager->parseIncludes('lead');

        }*/

        if($this->user->isClient()){

            $paginator = $this->user->leadLists()->paginate();

        } else {

            $paginator = $this->leadLists()->paginate();

        }

        $leadLists = $paginator->getCollection();

        return $this->respondOk($this->prepareResource($leadLists, $paginator));

    }

    public function create(){
        //
    }


    /**
     * @param Request $request
     * @return static
     */
    public function store(Request $request){

        $this->validate($request, [
           'list_name' => 'required'
        ]);

        $leadList = $this->leadList->create($request->all());

        $this->user->leadLists()->attach($leadList);

        return $this->respondCreated($this->prepareResource($leadList));

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

        $leadList = $this->leadList->find($id);

        if(!$leadList){

            $response = $this->respondNotFound('This LeadList cannot be found.');

        } else {

            if($this->gate->allows('update', $leadList)){

                $lead = $this->lead->find($request->lead_id);

                if(!$lead){

                    $response = $this->respondNotFound('The specified Lead could not be found.');

                } else {

                    if($this->gate->allows('show', $lead)){

                        $leadList->leads()->attach($lead);

                        $response = $this->respondOk($this->prepareResource($leadList));

                    } else {

                        $response = $this->respondUnauthorized();

                    }

                }

            } else {

                $response = $this->respondUnauthorized();

            }

        }

        return $response;

    }

    public function destroy($id){

    }

}