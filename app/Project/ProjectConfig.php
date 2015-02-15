<?php namespace Deploy\Project;

use Deploy\Contracts\PayloadContract;
use Deploy\Contracts\ProjectContract;
use Guzzle\Http\Client;
use im\Primitive\Container\Container;
use im\Primitive\Object\Object;
use InvalidArgumentException;

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
        $project = $project->getPayload()->getSlug();

        return $this->getConfig($project);
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

        return container([
            'file' => [
                'name' => $payload->getName(),
                'slug' => $payload->getSlug()
            ],
            'path' => $root.DS.$payload->getSlug(),
            'exists' => false
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
        if ($this->repository->has($project))
        {
            $config = $this->repository->get($project);
        }
        else
        {
            $config = $this->makeConfigFromPayload($project->getPayload());
        }

        $latest = $this->getRemoteConfig($project, $config);

        if (is_null($latest) || $this->isEqualConfig($latest, $config))
        {
            return $config;
        }

        return $latest;
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

        $response = (new Client())->get($api);

        $remote = container($this->repository->parseConfig($response));

        if ($remote->isEmpty()) return null;

        return $remote;
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
        $branch = $config->get('branch');
        $file = app('configurator')->getFile();

        if (is_null($branch)) throw new InvalidArgumentException('Branch is not specified.');

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

}
