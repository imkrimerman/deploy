<?php namespace Deploy\Payload;

use im\Primitive\Container\Container;

interface PayloadContract {

    /**
     * @param \im\Primitive\Container\Container $payload
     */
    public function __construct(Container $payload);

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
