<?php

namespace App\Http\Controllers\ControllerImplementations;

use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;


trait PreparesResources {

    public function prepareResource($resource, $paginator = null, $type = null){

        if($paginator != null){

            $prepared = $this->prepareCollection(
                $resource,
                ($type == null ? collect(explode('\\', get_class($resource->first())))->last() : $type)
            )->setPaginator(new IlluminatePaginatorAdapter($paginator));

        } else{

            $prepared = $this->prepareItem(
                $resource,
                ($type == null ? collect(explode('\\',get_class($resource)))->last() : $type)
            );

        }

        return $prepared;

    }

    public function prepareItem($item, $type){

        return new Item($item, $this->transformer, $type);

    }

    public function prepareCollection($collection, $type){

        return new Collection($collection, $this->transformer, $type);

    }

}
