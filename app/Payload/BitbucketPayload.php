<?php namespace Deploy\Payload;

class BitbucketPayload extends Payload {

    /**
     * Get project name.
     *
     * @return \im\Primitive\String\String
     */
    public function getName()
    {
        return string($this->payload->get('repository.name'));
    }

    /**
     * Get project name.
     *
     * @return \im\Primitive\String\String
     */
    public function getSlug()
    {
        return string($this->payload->get('repository.slug'));
    }

    /**
     * Get payload commits.
     *
     * @return \im\Primitive\Container\Container
     */
    public function getCommits()
    {
        return container($this->payload->get('commits'));
    }
}
