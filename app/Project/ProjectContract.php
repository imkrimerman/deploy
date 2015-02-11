<?php namespace Deploy\Project;

use im\Primitive\Container\Container;

interface ProjectContract {

    public function __construct(Container $payload);
}
