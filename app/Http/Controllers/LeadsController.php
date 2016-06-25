<?php


namespace App\Http\Controllers;


use App\Lead;
use App\Transformers\LeadTransformer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;

class LeadsController extends Controller
{
    protected $manager;
    protected $leadTransformer;
    protected $leads;

    public function __construct(Lead $leads){

        $this->leadTransformer = new LeadTransformer();
        $this->manager         = new Manager();
        $this->leads           = $leads;

        $this->manager->setSerializer(new JsonApiSerializer());

    }

    public function index(){

        $paginator = $this->leads->paginate();

        $leads = $paginator->getCollection();

        $resource = new Collection($leads, $this->leadTransformer, 'Lead');

        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));

        return response()->json($this->createData($resource), 200);
    }

    public function create(){
        //
    }

    public function store(Request $request){

        $this->validateLead($request);

        $lead = $this->leads->create($request->all());

        $lead->capture_date = Carbon::now()->format('m/d/Y H:i');

        $resource = new Item($lead, $this->leadTransformer, 'Lead');

        $response = response()->json([
            'message' => 'success',
            'lead'    => $this->createData($resource)
        ], 200);

        return $response;

    }

    public function show($id){

        try {

            $lead = $this->leads->findOrFail($id);

            $resource = new Item($lead, $this->leadTransformer, 'Lead');

            $response = response()->json($this->createData($resource), 200);

        } catch(ModelNotFoundException $e){

            $response = response()->json([
                'error' => [
                    'message' => 'This lead cannot be found'
                ]
            ], 404);

        } finally {

            return $response;

        }

    }

    public function edit($id){
        //
    }

    public function update(Request $request, $id){

        try {

            $lead = $this->leads->findOrFail($id);

            $lead->update($this->validateLead($request)->all());

            $resource = new Item($lead, $this->leadTransformer, 'Lead');

            $response = response()->json([
                'message' => 'success',
                'lead'    => $this->createData($resource)
            ], 200);

        } catch(ModelNotFoundException $e){

            $response = response()->json([
                'error' => [
                    'message' => 'This lead cannot be found'
                ]
            ], 404);

        } finally {

            return $response;

        }

    }

    public function destroy($id){

    }

    protected function createData($resource){

        return $this->manager->createData($resource)->toArray();

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