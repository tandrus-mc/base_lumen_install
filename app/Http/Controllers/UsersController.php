<?php

namespace App\Http\Controllers;


use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Contracts\Auth\Access\Gate;
use Tymon\JWTAuth\JWTAuth;

class UsersController extends ApiController
{

    protected $JWTAuth;
    protected $gate;
    protected $users;

    /**
     * UsersController constructor.
     * @param JWTAuth $JWTAuth
     * @param Gate $gate
     * @param User $users
     */
    public function __construct(JWTAuth $JWTAuth, Gate $gate, User $users){

        parent::__construct($JWTAuth, $gate, new UserTransformer());

        $this->users = $users;
        $this->user  = $this->JWTAuth->user();

    }

    public function index(){

        if($this->gate->allows('index', $this->user)){

            $paginator = $this->users->paginate();

            $users     = $paginator->getCollection();

            $this->setResponse($this->okResponse($this->prepareResource($users, $paginator)));

        } else {

            $this->setResponse($this->unauthorizedResponse());

        }

        $this->sendResponse();

    }

    public function create(){
        //
    }

    public function store(){
        //
    }

    public function show($id){
        //
    }

    public function edit($id){
        //
    }

    public function update($id){
        //
    }

    public function destroy($id){
        //
    }

}
