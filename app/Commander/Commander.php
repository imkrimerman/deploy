<?php namespace Deploy\Commander;

use Deploy\Contracts\ProjectContract;
use Deploy\Contracts\QueueContract;
use Deploy\Contracts\VcsContract;
use Deploy\Events\ChangedWorkingDir;
use Deploy\Events\CommandWasExecuted;
use Deploy\Project\ProjectConfig;
use im\Primitive\String\String;
use RuntimeException;


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
        $this->project = $project;

        $this->setVcsPath();

        $this->actionFromState();

        $this->execute();
    }

    /**
     * Execute prepared command queue.
     */
    protected function execute()
    {
        $this->setDirectory($this->project->getConfig('path'));

        $command = $this->queue->commands($this->project->getConfig('sequence'));

        $output = $this->shell($command);

        event(new CommandWasExecuted($command, $output));
    }

    /**
     * Set Vcs path if exists.
     *
     * @return $this
     */
    protected function setVcsPath()
    {
        if ($this->project->hasConfig('vcs'))
        {
            $this->vcs->setVcsPath(
                $this->project->getConfig('vcs')
            );
        }

        return $this;
    }

    /**
     * @return \Deploy\Commander\Commander
     */
    protected function actionFromState()
    {
        $state = $this->project->getConfig('state');

        switch ($state)
        {
            case 'clone':
                return $this->_clone();
            case 'pull':
                return $this->_pull();
            default:
                throw new RuntimeException('Unknown project pending state: '.$state);
        }
    }

    /**
     * Add clone project command queue.
     *
     * @return $this
     */
    protected function _clone()
    {
        $url = $this->project->getConfig('clone.url');

        $this->queue->push($this->vcs->_clone($url));

        return $this;
    }

    /**
     * Add pull project command to queue.
     *
     * @return $this
     */
    protected function _pull()
    {
        $this->queue->push($this->vcs->pull());

        return $this;
    }

    /**
     * Set working directory if $directory specified.
     * Otherwise set to deploy base path.
     *
     * @param string|null $directory
     * @return $this
     */
    protected function setDirectory($directory = null)
    {
        if ( ! is_null($directory) && is_dir($directory))
        {
            chdir(realpath($directory));
        }
        else
        {
            chdir(realpath(base_path()));
        }

        return $this;
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
     * Get Queue Instance.
     *
     * @return CommandQueue
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * Update Deployer.
     */
    public function selfUpdate()
    {
        $this->setDirectory()->_pull()->execute();
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
