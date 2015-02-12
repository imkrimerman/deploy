<?php namespace Deploy\Payload;

class BitbucketPayload extends Payload {

    /**
     * Get project name.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->payload->get('repository.name');
    }

    /**
     * Get project name.
     *
     * @return mixed
     */
    public function getSlug()
    {
        return $this->payload->get('repository.slug');
    }

    /**
     * Get payload commits.
     *
     * @return mixed
     */
    public function getCommits()
    {
        return $this->payload->get('commits');
    }
}
