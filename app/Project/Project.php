<?php namespace Deploy\Project;

use Deploy\Contracts\RepositoryContract;
use Deploy\Payload\PayloadContract;
use im\Primitive\String\String;


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
     * Project exist flag.
     *
     * @var bool
     */
    public $exists;

    /**
     * Project branches.
     *
     * @var \im\Primitive\Container\Container
     */
    protected $branches;

    /**
     * Project pending state.
     *
     * @var string
     */
    protected $state;
    
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
        $this->branches = $this->detectBranches();
        $this->state = $this->stateFromBranches($this->branches);
        $this->exists = $this->config->get('exists');

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
     * Return Project branch.
     *
     * @return string
     */
    public function getBranches()
    {
        return $this->branches;
    }

    /**
     * Return Project pending state.
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
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

    /**
     * Detect Project branches.
     *
     * @return \im\Primitive\Container\Container
     */
    abstract protected function detectBranches();

    /**
     * Set Project pending state from branches.
     *
     * @param \im\Primitive\Container\Container $branches
     * @return \im\Primitive\String\String
     */
    abstract public function stateFromBranches($branches);
}
