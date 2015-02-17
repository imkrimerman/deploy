<?php namespace Deploy\Contracts;

use Deploy\Config\ProjectConfigFactory;
use Deploy\Project\ProjectConfig;

interface ProjectContract extends Contract {

    /**
     * Construct.
     *
     * @param \Deploy\Contracts\PayloadContract $payload
     * @param \Deploy\Config\ProjectConfigFactory $factory
     */
    public function __construct(PayloadContract $payload, ProjectConfigFactory $factory);

    /**
     * Return Project configuration instance.
     * If $key specified [get] method will retrieve
     * configuration by $key.
     *
     * @param mixed|null $key
     * @return \Deploy\Project\ProjectConfig
     */
    public function getConfig($key = null);

    /**
     * Return Project branch.
     *
     * @return \im\Primitive\Container\Container
     */
    public function getBranches();

    /**
     * Return Project pending state.
     *
     * @return \im\Primitive\String\String
     */
    public function getStates();

    /**
     * Set Project pending state from branches.
     *
     * @param \im\Primitive\Container\Container $branches
     * @return \im\Primitive\String\String
     */
    public function statesFromCommits($branches);

    /**
     * Return payload.
     *
     * @return \Deploy\Contracts\PayloadContract
     */
    public function getPayload();

    /**
     * Return remote provider.
     *
     * @return string
     */
    public function getProvider();

    /**
     * Check if project exists in deploy.
     *
     * @return boolean
     */
    public function isExists();

    /**
     * Get clone url.
     *
     * @return string
     */
    public function getCloneUrl();
}
