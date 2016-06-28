<?php


namespace App\Http\Controllers;


use App\Lead;
use App\LeadList;
use App\Transformers\LeadListTransformer;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\Request;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
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

        $this->manager->parseIncludes('lead');

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

            $paginator = $this->user->leadLists()->paginate(5);

        } else {

            $paginator = $this->leadLists()->paginate(5);

        }

        $leadLists = $paginator->getCollection();

        return $this->respondSuccess
            ((new Collection($leadLists, $this->transformer, 'LeadList'))
                ->setPaginator(new IlluminatePaginatorAdapter($paginator)));

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

        return $this->respondSuccess(new Item($leadList, $this->transformer, 'LeadList'));

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

            $response = $this->respondError('This LeadList cannot be found.', 404);

        } else {

            if($this->gate->allows('update', $leadList)){

                $lead = $this->lead->find($request->lead_id);

                if(!$lead){

                    $response = $this->respondError('The specified Lead could not be found.', 404);

                } else {

                    if($this->gate->allows('show', $lead)){

                        $leadList->leads()->attach($lead);

                        $response = $this->respondSuccess(new Item($leadList, $this->transformer, 'LeadList'));

                    } else {

                        $response = $this->respondPrivilegeError();

                    }

                }

            } else {

                $response = $this->respondPrivilegeError();

            }

        }

        return $response;

    }

    public function destroy($id){

    }

}