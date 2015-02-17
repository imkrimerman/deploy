<?php namespace Deploy\Contracts;

interface PayloadContract extends Contract {

    /**
     * Get project name.
     *
     * @return \im\Primitive\String\String
     */
    public function getName();

    /**
     * Get project slug.
     *
     * @return \im\Primitive\String\String
     */
    public function getSlug();

    /**
     * Get project commits.
     *
     * @return \im\Primitive\Container\Container
     */
    public function getCommits();

    /**
     * Get project owner.
     *
     * @return  \im\Primitive\String\String
     */
    public function getOwner();

}
