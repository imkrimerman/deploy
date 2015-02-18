<?php namespace Deploy\Config;

use Deploy\Contracts\PayloadContract;
use Deploy\Contracts\ProjectConfigContract;
use Deploy\Events\ProjectWasConfigured;
use Deploy\Events\ProjectWasPreconfigured;
use Deploy\Contracts\ProjectContract;

abstract class ProjectConfig extends Config implements ProjectConfigContract {

    /**
     * Project default execution sequence.
     */
    const EXECUTION_SEQUENCE = ';';

    /**
     * Make Project configuration.
     *
     * @param \Deploy\Contracts\ProjectContract $project
     */
    public function __construct(ProjectContract $project)
    {
        $this->initialize([]);

        $this->setMainConfigKeys($project);

        $this->setFromPayload($project->getPayload());

        $this->handleStorage();

        event(new ProjectWasPreconfigured($this));

        $this->update();

        event(new ProjectWasConfigured($this));
    }

    /**
     * Update from deploy config file if exists.
     *
     * @return $this
     * @throws \im\Primitive\String\Exceptions\StringException
     */
    public function update()
    {
        $file = $this->get('clone.storage').DS.$this->get('deploy.filename');

        if (is_file($file) && is_readable($file))
        {
            $this->appendFromYaml(string($file)->contents());
        }

        $this->setSequence()->setPath()->setExist()->setBranch()->setState();

        return $this;
    }

    /**
     * Set configuration from payload.
     *
     * @param \Deploy\Contracts\PayloadContract $payload
     * @return $this
     * @throws \im\Primitive\Container\Exceptions\EmptyContainerException
     */
    protected function setFromPayload(PayloadContract $payload)
    {
        $this->set('name', $payload->getName());
        $this->set('slug', $payload->getSlug());
        $this->set('owner', $payload->getOwner());

        return $this;
    }

    /**
     * Handle temporary storage directory.
     *
     * @return $this
     */
    protected function handleStorage()
    {
        $storage = $this->get('deploy.storage')->value();

        if ( ! is_dir($storage))
        {
            mkdir($storage);
        }

        $uuid = uuid();

        $this->set('clone.uuid', $uuid);

        $this->set('clone.storage', $storage.DS.$uuid);

        return $this;
    }

    /**
     * Set command execution sequence
     *
     * @return $this
     */
    protected function setSequence()
    {
        $sequence = static::EXECUTION_SEQUENCE;

        if ($this->has('sequence'))
        {
            $sequence = $this->get('sequence');
        }

        $this->set('sequence', $sequence);

        return $this;
    }

    /**
     * Set project path on machine.
     *
     * @return $this
     */
    protected function setPath()
    {
        $directory = $this->get('deploy.directory');

        $name = $this->has('alias') ? $this->alias : $this->slug;

        $this->set('path', $directory.DS.$name);

        return $this->mirrorIfHas();
    }

    /**
     * Map working dir to mirrored if exists.
     *
     * @return $this
     */
    protected function mirrorIfHas()
    {
        if ($this->has('mirror'))
        {
            $this->set('path', $this->get('mirror'));
        }

        return $this;
    }

    /**
     * Set project existence.
     *
     * @return $this
     */
    protected function setExist()
    {
        if (is_dir($this->path))
        {
            $this->set('exist', true);
        }
        else
        {
            $this->set('exist', false);
        }

        return $this;
    }

    /**
     * Set main configuration options.
     *
     * @param \Deploy\Contracts\ProjectContract $project
     * @return $this
     */
    protected function setMainConfigKeys(ProjectContract $project)
    {
        $repository = app('config');

        foreach ($this->getConfigKeys() as $key)
        {
            $this->set($key, string($repository->get($key)));
        }

        $this->set('clone.url', $this->getCloneUrl($project->getPayload(), $project->getProvider()));

        return $this;
    }

    /**
     * Get configuration keys.
     *
     * @return array
     */
    public function getConfigKeys()
    {
        return [
            'deploy.directory',
            'deploy.filename',
            'deploy.storage',
            'deploy.branch'
        ];
    }

    /**
     * Set branch.
     *
     * @return $this
     */
    abstract protected function setBranch();

    /**
     * Set project pending state.
     *
     * @return $this
     */
    abstract protected function setState();

    /**
     * Make url to clone project.
     *
     * @param \Deploy\Contracts\PayloadContract $payload
     * @param string $provider
     * @return \im\Primitive\String\String
     */
    abstract public function getCloneUrl(PayloadContract $payload, $provider);
}
