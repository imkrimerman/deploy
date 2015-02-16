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
    }

    public function handleProject(ProjectContract $project)
    {
        $this->init($project);

        $this->dir($this->dir)->actionFromState($this->projectState);

        $this->execute();
    }

    protected function execute()
    {
        $command = $this->queue->processAll($this->sequence);

        $this->fireBeforeScripts();

        $output = shell_exec($command());

        $this->fireAfterScripts();

        event(new CommandWasExecuted($command, string($output)));
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
        $this->queue->push($this->vcs->_clone());

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
     * Initialize Project.
     *
     * @param \Deploy\Contracts\ProjectContract $project
     * @return $this
     */
    protected function init(ProjectContract $project)
    {
        $this->config = $project->getConfig();
        $this->sequence = $this->getCommandSequence($this->config);
        $this->dir = $this->getWorkingDir($this->config);

        $this->vcs->setVcsPath($this->getVcsPath($this->config));

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
        return $this->retrieveConfig($config, 'file.mirror', $config->get('path'));
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
        return $this->retrieveConfig($config, 'file.sequence', $default);
    }

    /**
     * Get vcs path if exists in configuration, otherwise get default.
     *
     * @param \Deploy\Project\ProjectConfig $config
     * @param string $default
     * @return mixed|null|string
     */
    protected function getVcsPath(ProjectConfig $config, $default = 'git')
    {
        return $this->retrieveConfig($config, 'file.git', $default);
    }

    /**
     * Retrieve Config by $key, otherwise return $default.
     *
     * @param \Deploy\Project\ProjectConfig $config
     * @param $key
     * @param $default
     * @return mixed|null
     */
    protected function retrieveConfig(ProjectConfig $config, $key, $default)
    {
        if ($config->has($key))
        {
            return $config->get($key);
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

    protected function fireBeforeScripts()
    {}

    protected function fireAfterScripts()
    {}

    /**
     * Update Deployer.
     */
    public function selfUpdate()
    {
        $this->dir();

        $this->pullProject()->execute();
    }

    /**
     * Destruct.
     * Change dir to deployer base path.
     */
    public function __destruct()
    {
        $this->dir();
    }
}
