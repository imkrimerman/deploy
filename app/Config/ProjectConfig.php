<?php namespace Deploy\Config;

use Deploy\Contracts\PayloadContract;
use Guzzle\Http\Client;
use im\Primitive\String\String;
use Symfony\Component\Yaml\Yaml;
use im\Primitive\Container\Container;
use Deploy\Contracts\ProjectContract;

abstract class ProjectConfig extends Container {

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

        $this->set(
            'clone.url', $this->makeCloneUrl($project->getPayload(), $project->getProvider())
        );

        $this->handleStorage();
    }

    /**
     * Map working dir to mirrored if exists.
     *
     * @param \im\Primitive\Container\Container $config
     * @return \Deploy\Project\ProjectConfig
     */
    protected function mirrorIfHas(Container $config)
    {
        if ($config->has('mirror'))
        {
            $config->set('path', $config->get('mirror'));
        }

        return $config;
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
        $storage = $this->get('deploy.storage');

        if ( ! is_dir($storage))
        {
            mkdir($storage);
        }

        $uuid = uuid();

        $this->set('clone.uuid', $uuid);

        mkdir($storage.DS.$uuid);

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
    abstract protected function makeCloneUrl(PayloadContract $payload, $provider);
}
