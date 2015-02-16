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
     * @var \Deploy\Contracts\PayloadContract
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
     * @var \im\Primitive\String\String
     */
    protected $state;

    /**
     * Project exist flag.
     *
     * @var bool
     */
    protected $exists;

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
        $this->exists = $this->config->get('exists');
        $this->state = $this->stateFromBranches($this->branches);

        $this->config->configure($this);

        return $this;
    }

    /**
     * Get payload.
     *
     * @return \Deploy\Contracts\PayloadContract
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
     * @return \im\Primitive\String\String
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
     * Check if project exists in deploy.
     *
     * @return boolean
     */
    public function isExists()
    {
        return $this->exists;
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
