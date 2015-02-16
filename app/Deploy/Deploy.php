<?php namespace Deploy\Deploy;

use Deploy\Project\ProjectFactory;
use Deploy\Commands\DeployProject;
use Deploy\Events\ProjectWasCreated;
use Deploy\Events\PayloadWasReceived;
use Illuminate\Foundation\Bus\DispatchesCommands;

class Deploy {

    use DispatchesCommands;

    /**
     * Dispatch Deploy Command.
     *
     * @param \Deploy\Events\PayloadWasReceived $event
     */
    public function project(PayloadWasReceived $event)
    {
        $project = ProjectFactory::create()->make($event->payload);

        event(new ProjectWasCreated($project));

        $this->dispatch(new DeployProject($project));
    }
}
