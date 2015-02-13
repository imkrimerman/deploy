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

    /**
     * Register project. And fire event after creation.
     *
     * @param \Deploy\Events\PayloadWasReceived $event
     */
    public function project(PayloadWasReceived $event)
    {
        $this->project = ProjectFactory::create()->make($event->payload);

        event(new ProjectWasCreated($this->project));

        $this->execute();
    }

    public function execute()
    {
        if ($this->project->exists)
        {
            return $this->handleProject($this->project);
        }

        return $this->handleNewProject($this->project);
    }

    protected function handleProject(ProjectContract $project)
    {

    }

    protected function handleNewProject(ProjectContract $project)
    {

    }
}
