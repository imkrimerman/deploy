<?php namespace Deploy\Project;

use Deploy\Contracts\RepositoryContract;
use Deploy\Payload\PayloadContract;

abstract class Project implements ProjectContract {

    /**
     * Received payload.
     *
     * @var \Deploy\Payload\PayloadContract
     */
    protected $payload;

    /**
     * Project Repository.
     *
     * @var \Deploy\Contracts\RepositoryContract
     */
    protected $repository;

    /**
     * Project configuration
     *
     * @var \im\Primitive\Container\Container
     */
    protected $config;

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
     * Add payload to a Project
     *
     * @param \Deploy\Payload\PayloadContract $payload
     * @return $this
     */
    public function payload(PayloadContract $payload)
    {
        $this->payload = $payload;
        $this->config = $this->configure();

        return $this;
    }

    /**
     * Return Project configuration.
     *
     * @return \im\Primitive\Container\Container
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get Project configuration from Project Repository.
     *
     * @return \im\Primitive\Container\Container
     */
    protected function configure()
    {
        $project = $this->payload->getSlug();

        if ( ! $this->repository->has($project))
        {
            return container($this->makeConfigFromPayload());
        }

        return container($this->repository->get($project));
    }

    /**
     * Make configuration from payload.
     *
     * @return array
     */
    protected function makeConfigFromPayload()
    {
        $root = $this->repository->getConfigurator()->getDirectory();

        return [
            'file' => [
                'name' => $this->payload->getName(),
                'slug' => $this->payload->getSlug()
            ],
            'path' => $root.DIRECTORY_SEPARATOR.$this->payload->getSlug(),
            'exists' => false
        ];
    }
}
