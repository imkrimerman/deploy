<?php namespace Deploy\Contracts;

use Deploy\Project\ProjectRepository;

interface ProjectContract {

    /**
     * Construct.
     *
     * @param \Deploy\Project\ProjectRepository $repository
     */
    public function __construct(ProjectRepository $repository);

    /**
     * Return Project configuration.
     *
     * @return \im\Primitive\Container\Container
     */
    public function getConfig();

    /**
     * Return Project branch.
     *
     * @return string
     */
    public function getBranches();

    /**
     * Return Project pending state.
     *
     * @return string
     */
    public function getState();

    /**
     * Set Project pending state from branches.
     *
     * @param \im\Primitive\Container\Container $branches
     * @return \im\Primitive\String\String
     */
    public function stateFromBranches($branches);
}
