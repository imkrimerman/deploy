<?php namespace Deploy\Project;

use Deploy\Contracts\PayloadContract;
use Deploy\Contracts\ProjectContract;

abstract class Project implements ProjectContract {

    /**
     * Remote Provider.
     *
     * @var string
     */
    protected $provider;

    /**
     * Received payload.
     *
     * @var \Deploy\Payload\PayloadContract
     */
    protected $payload;

    /**
     * Project configuration
     *
     * @var \Deploy\Project\ProjectConfig
     */
    protected $config;

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
     * Project exist flag.
     *
     * @var bool
     */
    public $exists;

    /**
     * Construct.
     *
     * @param \Deploy\Project\ProjectConfig $config
     */
    public function __construct(ProjectConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Add payload to a Project
     *
     * @param \Deploy\Contracts\PayloadContract $payload
     * @return $this
     */
    public function registerPayload(PayloadContract $payload)
    {
        $this->payload = $payload;
        $this->branches = $this->detectBranches();
        $this->state = $this->stateFromBranches($this->branches);
        $this->exists = $this->config->get('exists');

        $this->config->configure($this);

        return $this;
    }

    /**
     * Get payload.
     *
     * @return \Deploy\Payload\PayloadContract
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Return Project configuration.
     *
     * @return \Deploy\Project\ProjectConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Return Project branch.
     *
     * @return \im\Primitive\Container\Container
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
     * Return remote provider.
     *
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
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
