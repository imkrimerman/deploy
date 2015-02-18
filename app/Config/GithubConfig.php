<?php namespace Deploy\Config;

use Deploy\Contracts\PayloadContract;

class GithubConfig extends ProjectConfig {

    /**
     * Set branch.
     *
     * @return $this
     */
    protected function setBranch()
    {
        // TODO: Implement setBranch() method.
    }

    /**
     * Set project pending state.
     *
     * @return $this
     */
    protected function setState()
    {
        // TODO: Implement setState() method.
    }

    /**
     * Make url to clone project.
     *
     * @param \Deploy\Contracts\PayloadContract $payload
     * @param string $provider
     * @return \im\Primitive\String\String
     */
    public function getCloneUrl(PayloadContract $payload, $provider)
    {
        // TODO: Implement getCloneUrl() method.
    }
}
