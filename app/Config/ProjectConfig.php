<?php namespace Deploy\Config;

use Deploy\Contracts\PayloadContract;
use Deploy\Contracts\ProjectConfigContract;
use Deploy\Events\ProjectWasPreconfigured;
use Guzzle\Http\Client;
use im\Primitive\String\String;
use Symfony\Component\Yaml\Yaml;
use im\Primitive\Container\Container;
use Deploy\Contracts\ProjectContract;

abstract class ProjectConfig extends Container implements ProjectConfigContract {

    /**
     * Make Project configuration.
     *
     * @param \Deploy\Contracts\ProjectContract $project
     */
    public function __construct(ProjectContract $project)
    {
        $this->initialize([]);

        $repository = app('config');

        foreach ($this->getConfigKeys() as $key)
        {
            $this->set($key, string($repository->get($key)));
        }

        $this->set('name', $project->getPayload()->getName());
        $this->set('slug', $project->getPayload()->getSlug());
        $this->set(
            'clone.url', $this->getCloneUrl($project->getPayload(), $project->getProvider())
        );

        $this->handleStorage();

        event(new ProjectWasPreconfigured($this));

        $this->update();
    }

    /**
     * Update from deploy config file if exists.
     *
     * @return $this
     * @throws \im\Primitive\String\Exceptions\StringException
     */
    public function update()
    {
        $file = $this->get('clone.storage').DS. $this->get('slug').DS.$this->get('deploy.filename');

        if (is_file($file) && is_readable($file))
        {
            $this->appendFromYaml(string($file)->contents())
                 ->mirrorIfHas();
        }

        return $this;
    }

    /**
     * Append configuration from yaml.
     *
     * @param string $yaml
     * @return $this
     */
    public function appendFromYaml($yaml)
    {
        foreach ($this->parseYaml($yaml) as $key => $value)
        {
            $this->set($key, $value);
        }

        return $this;
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
     * Parse Yaml file.
     *
     * @param string $yaml
     * @return array
     */
    public function parseYaml($yaml)
    {
        return Yaml::parse($yaml);
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
            'deploy.storage'
        ];
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
     * Make url to clone project.
     *
     * @param \Deploy\Contracts\PayloadContract $payload
     * @param string $provider
     * @return \im\Primitive\String\String
     */
    abstract public function getCloneUrl(PayloadContract $payload, $provider);
}
