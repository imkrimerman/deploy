<?php namespace Deploy\Commander;

use Deploy\Contracts\ProjectContract;
use Deploy\Contracts\VcsContract;
use Illuminate\Filesystem\Filesystem;
use im\Primitive\String\String;


class Commander {

    /**
     * Command Queue.
     *
     * @var \Deploy\Commander\CommandQueue
     */
    protected $queue;

    /**
     * Vcs commander.
     *
     * @var \Deploy\Contracts\VcsContract
     */
    protected $vcs;

    /**
     * Filesystem
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Command execution sequence.
     *
     * @var string
     */
    protected $sequence;

    /**
     * Project Config.
     *
     * @var \Deploy\Project\ProjectConfig
     */
    protected $config;

    /**
     * Project state.
     *
     * @var \im\Primitive\String\String
     */
    protected $projectState;

    /**
     * Project working directory.
     *
     * @var string
     */
    protected $dir;

    /**
     * Construct.
     *
     * @param \Deploy\Commander\CommandQueue $queue
     * @param \Deploy\Contracts\VcsContract $vcs
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     */
    public function __construct(CommandQueue $queue, VcsContract $vcs, Filesystem $filesystem)
    {
        $this->queue = $queue;
        $this->vcs = $vcs;
        $this->filesystem = $filesystem;
        $this->sequence = '|';
    }

    public function handle(ProjectContract $project)
    {
        if ($project->isExists())
        {
            return $this->handleProject($project);
        }

        return $this->handleNewProject($project);
    }

    protected function handleProject(ProjectContract $project)
    {
        $this->setConfig($project);
        $this->setProjectState($project);
        $this->setCommandSequence();
        $this->setWorkingDir();

        $this->dir($this->dir)->actionFromState()->execute();
    }

    protected function handleNewProject(ProjectContract $project)
    {

    }

    protected function execute()
    {
        $command = $this->queue->processAll($this->sequence);

        if ($this->config->has('file.scripts'))

        $output = shell_exec($command());
    }

    protected function actionFromState()
    {
        switch ($this->projectState->get())
        {
            case 'pull':
            case 'merge':
                return $this->pullProject();
            case 'clone':
                return $this->cloneProject();
        }
    }

    /**
     * Change current working directory.
     * If $dir is null than it changes to Deploy base path.
     *
     * @param null|string $dir
     * @return $this
     */
    public function dir($dir = null)
    {
        if (is_null($dir)) chdir(base_path());

        if ($this->filesystem->isDirectory($dir)) chdir($dir);

        return $this;
    }

    protected function pullProject()
    {
        $this->queue->enqueue($this->vcs->reset());
        $this->queue->enqueue($this->vcs->pull());

        return $this;
    }

    protected function cloneProject()
    {
        $this->queue->enqueue($this->vcs->clone());

        return $this;
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
     * Set Queue Instance.
     *
     * @param CommandQueue $queue
     * @return $this
     */
    public function setQueue($queue)
    {
        $this->queue = $queue;

        return $this;
    }

    /**
     * Set Project Config.
     *
     * @param \Deploy\Contracts\ProjectContract $project
     * @return $this
     */
    protected function setConfig(ProjectContract $project)
    {
        $this->config = $project->getConfig();

        return $this;
    }

    /**
     * Set Project state.
     *
     * @param \Deploy\Contracts\ProjectContract $project
     * @return $this
     */
    protected function setProjectState(ProjectContract $project)
    {
        $this->projectState = $project->getState();

        return $this;
    }

    /**
     * @return mixed
     */
    protected function setWorkingDir()
    {
        if ($this->config->has('file.mirror'))
        {
            $this->dir = $this->config->get('file.mirror');
        }
        else
        {
            $this->dir = $this->config->get('path');
        }
    }

    protected function setCommandSequence()
    {
        if ($this->config->has('file.sequence'))
        {
            $this->sequence = $this->config->get('file.sequence');
        }

        return $this;
    }

}
