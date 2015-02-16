<?php namespace Deploy\Commander;

use Deploy\Contracts\ProjectContract;
use Deploy\Contracts\QueueContract;
use Deploy\Contracts\VcsContract;
use Deploy\Project\ProjectConfig;
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

    public function handleProject(ProjectContract $project)
    {

    }

    public function handleNewProject(ProjectContract $project)
    {

    }

    protected function execute()
    {
        $command = $this->queue->processAll($this->sequence);

        $output = shell_exec($command());
    }

    /**
     * Prepare actions from state.
     *
     * @param \im\Primitive\String\String $state
     * @return \Deploy\Commander\Commander
     */
    protected function actionFromState(String $state)
    {
        switch ($state())
        {
            case 'pull':
            case 'merge':
                return $this->pullProject();
            case 'clone':
                return $this->cloneProject();
            case 'setup':
                return $this->setupProject();
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

    /**
     * Pull Project changes.
     *
     * @return $this
     */
    protected function pullProject()
    {
        $this->queue->push($this->vcs->reset());
        $this->queue->push($this->vcs->pull());

        return $this;
    }

    /**
     * Clone Project.
     *
     * @return $this
     */
    protected function cloneProject()
    {
        $this->queue->push($this->vcs->clone());

        return $this;
    }

    /**
     * Setup and push Project configuration if it has no config file.
     *
     * @return $this
     */
    protected function setupProject()
    {
        $path = $this->config->path.DS.app('configurator')->getFile();

        if ( ! is_file($path) && $this->config->toFile($path))
        {
            $this->queue->push($this->vcs->commit('Added deployment configuration.'));
            $this->queue->push($this->vcs->push());
        }

        return $this;
    }


    /**
     * Get Project working directory.
     *
     * @param \Deploy\Project\ProjectConfig $config
     * @return mixed
     */
    protected function getWorkingDir(ProjectConfig $config)
    {
        if ($config->has('file.mirror'))
        {
            return $config->get('file.mirror');
        }

        return $config->get('path');
    }

    /**
     * Get Project command sequence.
     *
     * @param \Deploy\Project\ProjectConfig $config
     * @param string $default
     * @return $this
     */
    protected function getCommandSequence(ProjectConfig $config, $default = '|')
    {
        if ($config->has('file.sequence'))
        {
            return $config->get('file.sequence');
        }

        return $default;
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
     * @param \Deploy\Contracts\QueueContract $queue
     * @return $this
     */
    public function setQueue(QueueContract $queue)
    {
        $this->queue = $queue;

        return $this;
    }
}
