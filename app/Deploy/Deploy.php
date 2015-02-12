<?php namespace Deploy\Deploy;

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
     * Filesystem
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Construct.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
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

    /**
     * Change current working directory.
     * If $dir is null than it changes to Deploy base path.
     *
     * @param null|string $dir
     * @return $this
     */
    protected function changeDir($dir = null)
    {
        if ( ! is_null($dir) && $this->filesystem->isDirectory($dir))
        {
            chdir($dir);
        }

        chdir(base_path());

        return $this;
    }
}
