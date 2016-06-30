<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ControllerImplementations\PreparesResources;
use App\Http\Controllers\ControllerImplementations\PreparesResourcesContract;
use Illuminate\Contracts\Auth\Access\Gate;
use Laravel\Lumen\Routing\Controller as BaseController;
use League\Fractal\Manager;
use League\Fractal\Serializer\JsonApiSerializer;
use League\Fractal\TransformerAbstract;
use Tymon\JWTAuth\JWTAuth;


abstract class ApiController extends BaseController implements PreparesResourcesContract
{

    use PreparesResources;

    const HTTP_RESPONSE_OK                 = 200;
    const HTTP_RESPONSE_CREATED            = 201;
    const HTTP_RESPONSE_UNAUTHORIZED       = 401;
    const HTTP_RESPONSE_NOT_FOUND          = 404;

    protected $manager;
    protected $transformer;
    protected $JWTAuth;
    protected $user;
    protected $gate;

    private   $response;
    private   $responseCode = 200;

    public function __construct(JWTAuth $JWTAuth, Gate $gate, TransformerAbstract $transformer){

        $this->manager     = new Manager();
        $this->JWTAuth     = $JWTAuth;
        $this->user        = $JWTAuth->user();
        $this->transformer = $transformer;
        $this->gate        = $gate;


        $this->manager->setSerializer(new JsonApiSerializer());

    }

    private function createData($resource){

        return $this->manager->createData($resource);

    }

    private function setResponseCode($code){

        $this->responseCode = $code;

        return $this;

    }

    private function respondWithResource($resource, $message){

        return response()->json([
            'message'  => $message,
            'resource' => $this->createData($resource)->toArray()
        ], $this->responseCode);

    }
    private function respond($message){

        return response()->json([
            'message'  => $message,
        ], $this->responseCode);

    }

    protected function setResponse($response){

        $this->response = $response;

        return $this;

    }

    protected function sendResponse(){

        return $this->response->send();

    }

    protected function okResponse($resource, $message = 'Success.'){

        return $this->setResponseCode(self::HTTP_RESPONSE_OK)->respondWithResource($resource, $message);

    }

    protected function createdResponse($resource, $message = 'Resource created successfully'){

        return $this->setResponseCode(self::HTTP_RESPONSE_CREATED)->respondWithResource($resource, $message);

    }

    protected function unauthorizedResponse($message = 'You do not have sufficient privileges.'){

        return $this->setResponseCode(self::HTTP_RESPONSE_UNAUTHORIZED)->respond($message);

    }

    protected function notFoundResponse($message = 'Resource not found.'){

        return $this->setResponseCode(self::HTTP_RESPONSE_NOT_FOUND)->respond($message);

    }

}
