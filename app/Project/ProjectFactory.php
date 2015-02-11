<?php namespace Deploy\Project;

use im\Primitive\Container\Container;
use Symfony\Component\Yaml\Yaml;


class ProjectFactory {

    /**
     * Make new Project instance. Depends on payload.
     *
     * @param \im\Primitive\Container\Container $payload
     * @return \Deploy\Project\ProjectContract
     */
    public function make(Container $payload)
    {
        switch(true)
        {
            case $payload->has('canon_url'):
                return new BitbucketProject($payload);
            default:
                throw new \UnexpectedValueException('Can\'t detect payload.');
        }
    }

    /**
     * Return new ProjectFactory instance.
     *
     * @return static
     */
    public function create()
    {
        return new static;
    }
}
