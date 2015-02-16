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
     * Project configuration.
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
    protected $states;

    /**
     * Project exist flag.
     *
     * @var bool
     */
    protected $exists;

    /**
     * Project clone url
     *
     * @var string
     */
    protected $cloneUrl;

    /**
     * Construct Project from Payload Instance.
     *
     * @param \Deploy\Contracts\PayloadContract $payload
     */
    public function __construct(PayloadContract $payload)
    {
        $this->payload = $payload;

        $this->branches = $this->branchesFromPayload($payload);

        $this->states = $this->statesFromBranches($this->branches);

        $this->config = new ProjectConfig($this);

        $this->exists = $this->config->get('exists');

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
     * If $key specified [get] method will retrieve
     * configuration by $key.
     *
     * @param mixed|null $key
     * @return \Deploy\Project\ProjectConfig
     */
    public function getConfig($key = null)
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
    public function getStates()
    {
        return $this->states;
    }

    /**
     * Return remote provider.
     *
     * @return \im\Primitive\String\String
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Get clone url.
     *
     * @return \im\Primitive\String\String
     */
    public function getCloneUrl()
    {
        return $this->cloneUrl;
    }

    /**
     * Check if project exists in deploy.
     *
     * @return bool
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
    abstract protected function branchesFromPayload();

    /**
     * Set Project pending state from branches.
     *
     * @param \im\Primitive\Container\Container $branches
     * @return \im\Primitive\String\String
     */
    abstract public function statesFromBranches($branches);
}
