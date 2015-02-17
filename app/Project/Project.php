<?php namespace Deploy\Project;

use Deploy\Config\ProjectConfig;
use Deploy\Config\ProjectConfigFactory;
use Deploy\Contracts\PayloadContract;
use Deploy\Contracts\ProjectContract;
use RuntimeException;


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
     * Project branch.
     *
     * @var \im\Primitive\Container\Container
     */
    protected $branch;

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
     * Construct Project from Payload Instance.
     *
     * @param \Deploy\Contracts\PayloadContract $payload
     * @param \Deploy\Config\ProjectConfigFactory $factory
     * @throws \Deploy\Config\UnexpectedValueException
     */
    public function __construct(PayloadContract $payload, ProjectConfigFactory $factory)
    {
        $this->payload = $payload;

        $this->config = $factory->make($this);

        $this->cloneTemporary();

        event(new ProjectWasCreated($this));

        return $this;
    }

    /**
     * Clone Project to temporary storage.
     */
    protected function cloneTemporary()
    {
        if ( ! $this->config->has('clone.storage'))
        {
            throw new RuntimeException('Clone storage not configured.');
        }

        $current = getcwd();

        chdir($this->getConfig('clone.storage'));

        shell_exec("git clone {$this->getConfig('clone.url')}");

        chdir($current);
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
        if ( ! is_null($key))
        {
            return $this->config->get($key);
        }

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
    abstract public function statesFromCommits($branches);

}
