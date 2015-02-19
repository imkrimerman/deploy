<?php namespace Deploy\Project;

use Deploy\Contracts\ProjectConfigContract;
use Deploy\Events\ProjectWasCloned;
use Deploy\Events\ProjectWasNotCloned;
use Deploy\Events\ProjectWasPreconfigured;
use Illuminate\Filesystem\Filesystem;

class ProjectCloner {

    /**
     * Project configuration
     *
     * @var \Deploy\Contracts\ProjectConfigContract
     */
    protected $config;

    /**
     * Cloned flag.
     *
     * @var bool
     */
    protected $cloned;

    /**
     * Clone project to temporary storage.
     *
     * @param \Deploy\Contracts\ProjectConfigContract $config
     * @return $this
     * @throws \Deploy\Project\RuntimeException
     */
    public function process(ProjectConfigContract $config)
    {
        $this->config = $config;

        if ( ! $this->config->has('clone.storage'))
        {
            throw new RuntimeException('Clone storage not configured.');
        }

        $current = getcwd();

        chdir($this->config->get('deploy.storage'));

        shell_exec($this->createCloneUrl());

        chdir($current);

        $this->checkIfCloned()->fireEvents();

        return $this;
    }

    /**
     * Create Project Clone Instance or create and process cloning if $config specified.
     *
     * @param \Deploy\Contracts\ProjectConfigContract $config
     * @return static
     */
    public static function create(ProjectConfigContract $config = null)
    {
        if ( ! is_null($config))
        {
            return (new static)->process($config);
        }

        return new static;
    }

    /**
     * Fire result events.
     *
     * @return $this
     */
    protected function fireEvents()
    {
        if ($this->cloned)
        {
            event(new ProjectWasCloned($this->config));
        }
        else
        {
            event(new ProjectWasNotCloned($this->config));
        }

        return $this;
    }

    /**
     * Set cloned state.
     *
     * @return $this
     */
    protected function checkIfCloned()
    {
        $dir = $this->config->get('clone.storage');

        $this->cloned = is_dir($dir) ? true : false;

        return $this;
    }

    /**
     * Check if Project is cloned.
     *
     * @return bool
     */
    public function cloned()
    {
        return $this->cloned;
    }

    /**
     * Create clone url.
     *
     * @return string
     */
    protected function createCloneUrl()
    {
        $branch = $this->config->get('deploy.branch');
        $url = $this->config->get('clone.url');
        $directory = $this->config->get('clone.uuid');

        return "git clone --depth=1 -b {$branch} {$url} {$directory}";
    }

}
