<?php namespace Deploy\Project;

use Deploy\Config\ProjectConfig;
use Deploy\Config\ProjectConfigFactory;
use Deploy\Contracts\PayloadContract;
use Deploy\Contracts\ProjectContract;
use Deploy\Events\ProjectWasCreated;
use Illuminate\Filesystem\Filesystem;
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

        event(new ProjectWasCreated($this));

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
        if ( ! is_null($key))
        {
            return $this->config->get($key);
        }

        return $this->config;
    }

    /**
     * Check if config has $key
     *
     * @param string $key
     * @return bool
     */
    public function hasConfig($key)
    {
        return $this->config->has($key);
    }

    /**
     * Return Project branch.
     *
     * @return \im\Primitive\Container\Container
     */
    public function getBranch()
    {
        return $this->config->get('branch');
    }

    /**
     * Return Project pending state.
     *
     * @return \im\Primitive\String\String
     */
    public function getState()
    {
        return $this->config->get('state');
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
     * Check if project exists in deploy.
     *
     * @return bool
     */
    public function exists()
    {
        return $this->config->get('exist');
    }

    /**
     * Detect Project branches.
     *
     * @return \im\Primitive\Container\Container
     */
    abstract protected function branchFromPayload();

    /**
     * Set Project pending state from branches.
     *
     * @param \im\Primitive\Container\Container $branches
     * @return \im\Primitive\String\String
     */
    abstract public function stateFromCommits($branches);

    /**
     * Destruct.
     * Delete temporary created project clone.
     */
    public function __destruct()
    {
        (new Filesystem)->deleteDirectory($this->getConfig('clone.storage'));
    }
}
