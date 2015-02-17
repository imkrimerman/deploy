<?php namespace Deploy\Commander;

use Deploy\Contracts\ProjectContract;
use Deploy\Contracts\QueueContract;
use Deploy\Contracts\VcsContract;
use Deploy\Events\ChangedWorkingDir;
use Deploy\Events\CommandWasExecuted;
use Deploy\Project\ProjectConfig;
use im\Primitive\String\String;

class Commander {

    /**
     * Command Queue.
     *
     * @var \Deploy\Commander\CommandQueue
     */
    protected $queue;

    /**
     * Vcs.
     *
     * @var \Deploy\Contracts\VcsContract
     */
    protected $vcs;

    /**
     * Project scripts.
     *
     * @var \im\Primitive\Container\Container
     */
    protected $scripts;

    /**
     * Project.
     *
     * @var \Deploy\Contracts\ProjectContract
     */
    protected $project;

    /**
     * Construct.
     *
     * @param \Deploy\Commander\CommandQueue $queue
     * @param \Deploy\Contracts\VcsContract $vcs
     */
    public function __construct(CommandQueue $queue, VcsContract $vcs)
    {
        $this->queue = $queue;
        $this->vcs = $vcs;
    }

    /**
     * Initialize and handle project.
     *
     * @param \Deploy\Contracts\ProjectContract $project
     */
    public function handle(ProjectContract $project)
    {


        $this->execute();
    }

    /**
     * Execute prepared command queue.
     */
    protected function execute()
    {
        event(new CommandWasExecuted($command, $output));
    }

    /**
     * Execute shell command.
     *
     * @param \im\Primitive\String\String $command
     * @return \im\Primitive\String\String
     */
    protected function shell(String $command)
    {
        $output = shell_exec($command()." 2>&1");

        return string((is_string($output) ? $output : ''));
    }
    /**
     * Get Vcs Instance.
     *
     * @return VcsContract
     */
    public function getVcs()
    {
        return $this->vcs;
    }


    /**
     * Set Vcs Instance.
     *
     * @param VcsContract $vcs
     * @return $this
     */
    public function setVcs($vcs)
    {
        $this->vcs = $vcs;

        return $this;
    }
    /**
     * Get Queue Instance.
     *
     * @return CommandQueue
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * Set Queue Instance.
     *
     * @param \Deploy\Contracts\QueueContract $queue
     * @return $this
     */
    public function setQueue(QueueContract $queue)
    {
        $this->queue = $queue;

        return $this;
    }

    /**
     * Update Deployer.
     */
    public function selfUpdate()
    {
        $this->setDirectory()->pull_()->execute();
    }

    /**
     * Destruct.
     * Change dir to deployer base path.
     */
    public function __destruct()
    {
        $this->setDirectory();
    }
}
