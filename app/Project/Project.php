<?php namespace Deploy\Project;

use Deploy\Deploy\Configurator;
use im\Primitive\Container\Container;
use Symfony\Component\Yaml\Yaml;

abstract class Project implements ProjectContract {

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
}
