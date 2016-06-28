<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Access\Gate;
use Laravel\Lumen\Routing\Controller as BaseController;
use League\Fractal\Manager;
use League\Fractal\Serializer\JsonApiSerializer;
use League\Fractal\TransformerAbstract;
use Tymon\JWTAuth\JWTAuth;


abstract class ApiController extends BaseController
{
    protected $manager;
    protected $transformer;
    protected $JWTAuth;
    protected $user;
    protected $gate;

    public function __construct(JWTAuth $JWTAuth, Gate $gate, TransformerAbstract $transformer){

        $this->manager     = new Manager();
        $this->JWTAuth     = $JWTAuth;
        $this->user        = $JWTAuth->user();
        $this->transformer = $transformer;
        $this->gate        = $gate;


        $this->manager->setSerializer(new JsonApiSerializer());

    }

    protected function createData($resource){

        return $this->manager->createData($resource);

    }

    protected function respondSuccess($resource, $code = 200){

        return response()->json([
            'message' => 'success',
            'leads'   => $this->createData($resource)->toArray()
        ], $code);

    }

    protected function respondError($message, $code = 404){

        return response()->json([
            'error' => [
                'message' => $message
            ]
        ], $code);

    }

    protected function respondPrivilegeError(){

        return $this->respondError('You do not have sufficient privileges.', 403);

    }

}
