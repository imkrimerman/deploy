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
    protected function makeCloneUrl(PayloadContract $payload, $provider)
    {
        $owner = $payload->getOwner();
        $slug = $payload->getSlug();

        return string("git@bitbucket.org:{$owner}/{$slug}.git");
    }

}
