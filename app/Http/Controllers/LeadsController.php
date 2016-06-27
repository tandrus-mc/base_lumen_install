<?php


namespace App\Http\Controllers;


use App\Lead;
use App\Transformers\LeadTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Access\Gate;
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
    protected $gate;
    protected $user;

    public function __construct(Lead $leads, Gate $gate){

        $this->leadTransformer = new LeadTransformer();
        $this->manager         = new Manager();
        $this->leads           = $leads;
        $this->gate            = $gate;
        $this->user            = app('request')->user();

        $this->manager->setSerializer(new JsonApiSerializer());

    }

    public function index(){

        if($this->user->isClient()){

            $paginator = $this->leads->where('config_id', '=', $this->user->config_id)->paginate();

        } else {

            $paginator = $this->leads->paginate();

        }

        $leads = $paginator->getCollection();

        return $this->respondSuccess
        ((new Collection($leads, $this->leadTransformer, 'Lead'))
            ->setPaginator(new IlluminatePaginatorAdapter($paginator)));

    }

    public function create(){
        //
    }

    public function store(Request $request){

        $lead = $this->leads->create($this->validateLead($request)->all());

        $lead->forceFill(['capture_date' => strtotime(Carbon::now()->toDateTimeString())])->save();

        return $this->respondSuccess(new Item($lead, $this->leadTransformer, 'Lead'), 201);

    }

    public function show($id){

        $lead = $this->leads->find($id);

        if(!$lead){

            $response = $this->respondError('This lead cannot be found');

        } else {

            if($this->gate->allows('show-lead', $lead)){

                $response = $this->respondSuccess(new Item($lead, $this->leadTransformer, 'Lead'));

            } else {

                $response = $this->respondError('You do not have sufficient privileges', 403);

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

            if($this->gate->allows('update-lead')){

                $lead->update($this->validateLead($request)->all());

                $response = $this->respondSuccess(new Item($lead, $this->leadTransformer, 'Lead'));

            } else{

                $response = $this->respondError('You do not have sufficient privileges', 403);

            }

        }

        return $response;

    }

    public function destroy($id){

        $lead = $this->leads->find($id);

        if(!$lead){

            $response = $this->respondError('This lead cannot be found');

        } else {

            if($this->gate->allows('destroy-lead')){

                $lead->delete();

                $response = $this->respondSuccess(new Item($lead, $this->leadTransformer, 'Lead'), 410);

            } else {

                $response = $this->respondError('You do not have sufficient privileges', 403);

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