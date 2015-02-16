<?php namespace Deploy\Config;

use Deploy\Contracts\PayloadContract;
use InvalidArgumentException;
use Guzzle\Http\Client;
use Symfony\Component\Yaml\Yaml;
use im\Primitive\Container\Container;
use Deploy\Contracts\ProjectContract;

class ProjectConfig extends MainConfig {

    /**
     * Hidden properties.
     *
     * @var array
     */
    protected $hidden;

    /**
     * Make Project configuration.
     *
     * @param \Deploy\Contracts\ProjectContract $project
     */
    public function __construct(ProjectContract $project)
    {
        parent::__construct(app('config'));

        $this->forProject($project);
    }

    /**
     * Get latest configuration.
     *
     * @param \Deploy\Contracts\ProjectContract $project
     * @return \im\Primitive\Container\Container
     */
    protected function forProject(ProjectContract $project)
    {
        $this->set(
            'cloneUrl', $this->makeCloneUrl($project->getPayload(), $project->getProvider())
        );

        $latest = $this->getRemoteConfig($project);
    }

    /**
     * Get remote configuration file.
     *
     * @param \Deploy\Contracts\ProjectContract $project
     * @return \im\Primitive\Container\Container
     */
    protected function getRemoteConfig(ProjectContract $project)
    {
        foreach ($project->getBranches() as $branch)
        {
            $api = $this->makeRemoteApiUrl(
                $project->getPayload(), $project->getProvider(), $branch
            );

            $response = (new Client())->get($api)->getResponse();
        }
    }

    /**
     * @param \Deploy\Contracts\PayloadContract $payload
     * @param string $provider
     * @param string $branch
     * @return string
     */
    protected function makeRemoteApiUrl(PayloadContract $payload, $provider, $branch)
    {
        $owner = $payload->getOwner();
        $slug = $payload->getSlug();

        switch ($provider)
        {
            case 'bitbucket':
                return "https://bitbucket.org/api/1.0/repositories/{$owner}/{$slug}/raw/{$branch}/{$this->filename}";
            default:
                throw new InvalidArgumentException('Provider: '.$provider.' is not defined or not resolved.');
        }
    }

    /**
     * @param \Deploy\Contracts\PayloadContract $payload
     * @param $provider
     * @return \im\Primitive\String\String
     */
    protected function makeCloneUrl(PayloadContract $payload, $provider)
    {
        $owner = $payload->getOwner();
        $slug = $payload->getSlug();

        switch ($provider)
        {
            case 'bitbucket':
                return string("git@bitbucket.org:{$owner}/{$slug}.git");
            default:
                throw new InvalidArgumentException('Provider: ' . $provider . ' is not defined or not resolved.');
        }
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
     * Convert Configuration to Yaml.
     *
     * @param int $options
     * @return string
     */
    public function toYaml($options = 2)
    {
        return Yaml::dump($this->value(), $options);
    }

    /**
     * Write Configuration to file.
     *
     * @param \im\Primitive\Support\Contracts\StringContract|string $path
     * @param int $options
     * @return bool
     */
    public function toFile($path, $options = 2)
    {
        if (is_dir(pathinfo($path, PATHINFO_DIRNAME)))
        {
            return (bool) file_put_contents($path, $this->toYaml($options));
        }

        return false;
    }

    /**
     * Get Configuration properties.
     *
     * @return array
     */
    public function value()
    {
        $properties = parent::value();

        foreach ($properties as $key => $value)
        {
            $properties[$key] = $this->getSearchable($value);
        }

        return $properties;
    }
}
