<?php

namespace App\Http\Controllers\ControllerImplementations;

interface PreparesResourcesContract {

    public function prepareResource($resource, $paginator = null, $type = null);

    public function prepareItem($item, $type);

    public function prepareCollection($collection, $type);

}