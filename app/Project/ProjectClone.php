<?php namespace Deploy\Project;

use Deploy\Contracts\ProjectConfigContract;
use Deploy\Events\ProjectWasCloned;
use Deploy\Events\ProjectWasNotCloned;
use Deploy\Events\ProjectWasPreconfigured;
use Illuminate\Filesystem\Filesystem;


class ProjectClone {

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
     * @param \Deploy\Events\ProjectWasPreconfigured $event
     * @return $this
     * @throws \Deploy\Project\RuntimeException
     */
    public function process(ProjectWasPreconfigured $event)
    {
        $this->config = $event->config;

        if ( ! $this->config->has('clone.storage'))
        {
            throw new RuntimeException('Clone storage not configured.');
        }

        $current = getcwd();

        chdir($this->config->get('deploy.storage'));

        shell_exec($this->createCloneCommand());

        chdir($current);

        $this->checkIfCloned(new Filesystem)->fireEvents();

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
            return (new static)->process(new ProjectWasPreconfigured($config));
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
            event(new ProjectWasCloned);
        }
        else
        {
            event(new ProjectWasNotCloned);
        }

        return $this;
    }

    /**
     * Set cloned state.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     * @return $this
     */
    protected function checkIfCloned(Filesystem $filesystem)
    {
        $dir = $this->config->get('clone.storage');

        if ($filesystem->isDirectory($dir))
        {
            $this->cloned = true;
        }
        else
        {
            $this->cloned = false;
        }

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

    protected function createCloneCommand()
    {
        return "git clone {$this->config->get('clone.url')} {$this->config->get('clone.uuid')}";
    }

}