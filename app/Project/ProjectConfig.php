<?php namespace Deploy\Project;

use Deploy\Contracts\PayloadContract;
use Deploy\Contracts\ProjectContract;
use Guzzle\Http\Client;
use im\Primitive\Container\Container;
use im\Primitive\Object\Object;
use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;


class ProjectConfig extends Object {

    /**
     * Project Repository.
     *
     * @var \Deploy\Contracts\RepositoryContract
     */
    protected $repository;

    /**
     * Construct.
     *
     * @param \Deploy\Project\ProjectRepository $repository
     */
    public function __construct(ProjectRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Make Project configuration.
     *
     * @param \Deploy\Contracts\ProjectContract $project
     * @return \im\Primitive\Container\Container
     */
    public function configure(ProjectContract $project)
    {
        $this->initialize($this->getConfig($project));
    }

    /**
     * Make configuration options from payload.
     *
     * @param \Deploy\Contracts\PayloadContract $payload
     * @return \im\Primitive\Container\Container
     */
    protected function makeConfigFromPayload(PayloadContract $payload)
    {
        $root = $this->repository->getConfigurator()->getDirectory();

        $path = $root.DS.$payload->getSlug();

        $exists = is_dir($path) ? true : false;

        return container([
            'file' => [
                'name' => $payload->getName(),
                'slug' => $payload->getSlug()
            ],
            'path' => $path,
            'exists' => $exists,
            'configured' => false
        ]);
    }

    /**
     * Get latest configuration.
     *
     * @param \Deploy\Contracts\ProjectContract $project
     * @return \im\Primitive\Container\Container
     */
    protected function getConfig(ProjectContract $project)
    {
        $payload = $project->getPayload();
        $slug = $payload->getSlug();

        if ($this->repository->has($slug))
        {
            $config = $this->repository->get($slug);
        }
        else
        {
            $config = $this->makeConfigFromPayload($payload);
        }

        $latest = $this->getRemoteConfig($project, $config);

        if (is_null($latest) || $this->isEqualConfig($latest, $config))
        {
            return $config->set('configured', true);
        }

        return $latest->set('configured', true);
    }

    /**
     * Get remote configuration file.
     *
     * @param \Deploy\Contracts\ProjectContract $project
     * @param \im\Primitive\Container\Container $config
     * @return \im\Primitive\Container\Container
     */
    protected function getRemoteConfig(ProjectContract $project, Container $config)
    {
        $api = $this->getRemoteApiUrl($project, $config);

        $response = (new Client())->get($api)->getResponse();

        if (is_null($response)) return null;

        return container($this->repository->parseConfig($response->getBody(true)));
    }

    /**
     * @param \Deploy\Contracts\ProjectContract $project
     * @param \im\Primitive\Container\Container $config
     * @return string
     */
    protected function getRemoteApiUrl(ProjectContract $project, Container $config)
    {
        $payload = $project->getPayload();
        $provider = $project->getProvider();

        $owner = $payload->getOwner();
        $slug = $payload->getSlug();
        $branch = $config->get('file.branch');
        $file = app('configurator')->getFile();

        if (is_null($branch))
        {
            throw new InvalidArgumentException('Branch is not specified.');
        }

        switch ($provider)
        {
            case 'bitbucket':
                return "https://bitbucket.org/api/1.0/repositories/{$owner}/{$slug}/raw/{$branch}/{$file}";
            default:
                throw new InvalidArgumentException('Provider: '.$provider.' is not defined or not resolved.');
        }
    }

    /**
     * Check if two configuration is equal.
     *
     * @param \im\Primitive\Container\Container $latest
     * @param \im\Primitive\Container\Container $config
     * @return bool
     */
    protected function isEqualConfig(Container $latest, Container $config)
    {
        return string($latest->toJson())->base64()->get() === string($config->toJson())->base64()->get();
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
        $hidden = ['repository'];

        $vars = parent::value();

        foreach ($vars as $property => $value)
        {
            if (in_array($property, $hidden)) unset($vars[$property]);
        }

        return $vars;
    }
}
