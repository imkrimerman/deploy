<?php namespace Deploy\Payload;

use im\Primitive\Container\Container;

abstract class Payload implements PayloadContract {

    /**
     * Payload
     *
     * @var \im\Primitive\Container\Container
     */
    protected $payload;

    /**
     * Constructor.
     *
     * @param \im\Primitive\Container\Container $payload
     */
    public function __construct(Container $payload)
    {
        $this->payload = $payload;
    }

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
