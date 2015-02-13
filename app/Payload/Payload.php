<?php namespace Deploy\Payload;

use Deploy\Contracts\PayloadContract;
use im\Primitive\Container\Container;

abstract class Payload extends Container implements PayloadContract {

    /**
     * Get project name.
     *
     * @return mixed
     */
    abstract public function getName();

    /**
     * Get project name.
     *
     * @return mixed
     */
    abstract public function getSlug();

    /**
     * Get payload commits.
     *
     * @return mixed
     */
    abstract public function getCommits();
}
