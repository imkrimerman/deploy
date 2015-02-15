<?php namespace Deploy\Payload;

class BitbucketPayload extends Payload {

    /**
     * Get project name.
     *
     * @return \im\Primitive\String\String
     */
    public function getName()
    {
        return string($this->get('repository.name'));
    }

    /**
     * Get project name.
     *
     * @return \im\Primitive\String\String
     */
    public function getSlug()
    {
        return string($this->get('repository.slug'));
    }

    /**
     * Get payload commits.
     *
     * @return \im\Primitive\Container\Container
     */
    public function getCommits()
    {
        return container($this->get('commits'));
    }

    /**
     * Get project owner.
     *
     * @return  \im\Primitive\String\String
     */
    public function getOwner()
    {
        return string($this->get('repository.owner'));
    }
}
