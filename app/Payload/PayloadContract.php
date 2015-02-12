<?php namespace Deploy\Payload;

use im\Primitive\Container\Container;

interface PayloadContract {

    /**
     * @param \im\Primitive\Container\Container $payload
     */
    public function __construct(Container $payload);

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @return mixed
     */
    public function getSlug();

    /**
     * @return mixed
     */
    public function getCommits();
}
