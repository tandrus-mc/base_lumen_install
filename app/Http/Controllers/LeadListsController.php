<?php


namespace App\Http\Controllers;


use App\Lead;
use App\LeadList;
use App\Transformers\LeadListTransformer;
use App\User;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\JsonApiSerializer;
use Tymon\JWTAuth\JWTAuth;

class LeadListsController extends Controller
{

    protected $manager;
    protected $leadListTransformer;
    protected $leadList;
    protected $lead;
    protected $jwt;
    protected $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(LeadList $leadList, Lead $lead, JWTAuth $jwt)
    {
        $this->manager             = new Manager();
        $this->leadListTransformer = new LeadListTransformer();
        $this->leadList            = $leadList;
        $this->lead                = $lead;
        $this->jwt                 = $jwt;
        $this->user                = $jwt->user();
        

        $this->manager->setSerializer(new JsonApiSerializer());

    }

    public function index(Request $request){


        if($request->input('include') == 'lead'){

            $this->manager->parseIncludes('lead');

        }

        if($this->user->isClient()){

            $paginator = $this->user->leadLists()->paginate(5);

        } else {

            $paginator = $this->leadLists()->paginate(5);

        }

        $leadLists = $paginator->getCollection();

        return $this->respondSuccess
        ((new Collection($leadLists, $this->leadListTransformer, 'LeadList'))
            ->setPaginator(new IlluminatePaginatorAdapter($paginator)));

    }

    public function create(){
        //
    }


    public function store(Request $request){

        $this->validate($request, [
           'list_name' => 'required'
        ]);

        $leadList = $this->leadList->create($request->all());

        $this->user->leadLists()->attach($leadList);

        return $leadList;

    }

    public function show($id){

    }

    public function edit($id){
        //
    }

    public function update(Request $request, $id){

        $leadList = $this->leadList->find($id);
        
        $lead = $this->lead->find($request->lead_id);

        $leadList->leads()->attach($lead);

        return $lead;

    }

    public function destroy($id){

    }

    protected function createData($resource){

        return $this->manager->createData($resource);

    }

    protected function respondSuccess($resource, $code = 200){

        return response()->json([
            'message' => 'success',
            'leads'    => $this->createData($resource)->toArray()
        ], $code);

    }

    protected function respondError($message, $code = 404){

        return response()->json([
            'error' => [
                'message' => $message
            ]
        ], $code);

    }

}