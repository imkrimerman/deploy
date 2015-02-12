<?php namespace Deploy\Deploy;

use Deploy\Events\PayloadWasReceived;
use Deploy\Events\ProjectWasCreated;
use Deploy\Project\ProjectFactory;

class Deploy {

    /**
     * Project.
     *
     * @var \Deploy\Project\ProjectContract
     */
    protected $project;

    public function project(PayloadWasReceived $event)
    {
        $this->project = ProjectFactory::create()->make($event->payload);

        event(new ProjectWasCreated($this->project));
    }

    public function execute(ProjectWasCreated $project)
    {

    }
}
