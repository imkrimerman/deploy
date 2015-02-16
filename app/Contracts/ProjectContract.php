<?php namespace Deploy\Contracts;

use Deploy\Project\ProjectConfig;

interface ProjectContract {

    /**
     * Construct.
     *
     * @param \Deploy\Project\ProjectConfig $repository
     */
    public function __construct(ProjectConfig $repository);

    /**
     * Return Project configuration.
     *
     * @return \Deploy\Project\ProjectConfig
     */
    public function getConfig();

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
    public function getState();

    /**
     * Set Project pending state from branches.
     *
     * @param \im\Primitive\Container\Container $branches
     * @return \im\Primitive\String\String
     */
    public function stateFromBranches($branches);

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
}
