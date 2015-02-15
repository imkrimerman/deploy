<?php namespace Deploy\Deploy;

use Deploy\Commander\Commander;
use Deploy\Contracts\ProjectContract;
use Deploy\Events\PayloadWasReceived;
use Deploy\Events\ProjectWasCreated;
use Deploy\Project\ProjectFactory;

class Deploy {

    /**
     * Project.
     *
     * @var \Deploy\Contracts\ProjectContract
     */
    protected $project;

    /**
     * Commander.
     *
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
     * Register project. Fire event. Start execution.
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
        switch($this->project->exists)
        {
            case true:
                return $this->executeProject($this->project);
            case false:
                return $this->executeNewProject($this->project);
        }
    }

    protected function executeProject(ProjectContract $project)
    {
        $config = $project->getConfig();

        $this->commander->dir($config->path);
    }

    protected function executeNewProject(ProjectContract $project)
    {

    }
}
