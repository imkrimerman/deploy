<?php namespace Deploy\Project;

use Deploy\Config\ProjectConfigFactory;
use Deploy\Contracts\FactoryContract;
use Deploy\Contracts\PayloadContract;
use Deploy\Events\PayloadWasReceived;
use Deploy\Payload\BitbucketPayload;
use Deploy\Payload\GithubPayload;
use UnexpectedValueException;

class ProjectFactory implements FactoryContract {

    /**
     * Make new Payload instance. Depends on payload.
     *
     * @param \Deploy\Contracts\PayloadContract $payload
     * @return \Deploy\Contracts\ProjectContract
     */
    public function make(PayloadContract $payload)
    {
        switch(true)
        {
            case $payload instanceof BitbucketPayload:
                return new BitbucketProject($payload, new ProjectConfigFactory);
            case $payload instanceof GithubPayload:
                return new GithubProject($payload, new ProjectConfigFactory);
            default:
                throw new UnexpectedValueException('Can\'t detect payload.');
        }
    }

    /**
     * Create Factory Instance.
     *
     * @return static
     */
    public static function create()
    {
        return new static;
    }
}
