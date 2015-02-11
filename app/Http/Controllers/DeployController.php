<?php namespace Deploy\Http\Controllers;

use Deploy\Commands\DeployPayload;
use Deploy\Http\Requests\DeployRequest;

class DeployController extends Controller {

    /**
     * Run deployment when payload has come.
     *
     * @param \Deploy\Http\Requests\DeployRequest $request
     */
    public function run(DeployRequest $request)
    {
//        $payload = container($request->get('payload'));

        $this->dispatchFrom(DeployPayload::class, $request);
    }
}
