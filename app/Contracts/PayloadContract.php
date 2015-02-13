<?php namespace Deploy\Contracts;

use im\Primitive\Support\Contracts\ContainerContract;

interface PayloadContract extends ContainerContract {

    /**
     * @return \im\Primitive\String\String
     */
    public function getName();

    /**
     * @return \im\Primitive\String\String
     */
    public function getSlug();

    /**
     * @return \im\Primitive\Container\Container
     */
    public function getCommits();
}
