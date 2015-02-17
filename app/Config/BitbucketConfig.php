<?php namespace Deploy\Config;

use Deploy\Contracts\PayloadContract;

class BitbucketConfig extends ProjectConfig {

    /**
     * Make url to clone project.
     *
     * @param \Deploy\Contracts\PayloadContract $payload
     * @param string $provider
     * @return \im\Primitive\String\String
     */
    public function getCloneUrl(PayloadContract $payload, $provider)
    {
        $owner = $payload->getOwner();
        $slug = $payload->getSlug();

        return string("git@bitbucket.org:{$owner}/{$slug}.git");
    }

    /**
     * Set branch.
     *
     * @return $this
     * @throws \im\Primitive\Container\Exceptions\EmptyContainerException
     */
    protected function setBranch()
    {
        if ($this->has('branch'))
        {
            $branch = $this->get('branch');
        }
        else
        {
            $branch = $this->get('deploy.branch');
        }

        $this->set('branch', $branch);

        return $this;
    }

    /**
     * Set project pending state.
     *
     * @return $this
     */
    protected function setState()
    {
        if ( ! is_dir($this->path))
        {
            $this->set('state', 'clone');
        }
        else
        {
            $this->set('state', 'pull');
        }

        return $this;
    }
}
