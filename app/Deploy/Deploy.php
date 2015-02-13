<?php namespace Deploy\Deploy;

use Deploy\Commander\Commander;
use Deploy\Events\PayloadWasReceived;
use Deploy\Events\ProjectWasCreated;
use Deploy\Project\ProjectContract;
use Deploy\Project\ProjectFactory;
use Illuminate\Filesystem\Filesystem;


class Deploy {

    /**
     * Project.
     *
     * @var \Deploy\Project\ProjectContract
     */
    protected $project;

    /**
     * @var \Deploy\Commander\Commander
     */
    protected $commander;

    /**
     * Construct.
     *
     * @param \Deploy\Commander\Commander $commander
     */
    public function __construct(Commander $commander)
    {
        $this->commander = $commander;
    }

    public function project(PayloadWasReceived $event)
    {
        $this->project = ProjectFactory::create()->make($event->payload);

        event(new ProjectWasCreated($this->project));
    }

    public function execute()
    {
        if ( ! $this->project->exists)
        {
            return $this->handleNewProject($this->project);
        }

        return $this->handleProject($this->project);
    }

    protected function handleProject(ProjectContract $project)
    {

    }

    protected function handleNewProject(ProjectContract $project)
    {

    }
}
