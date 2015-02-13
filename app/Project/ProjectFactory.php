<?php namespace Deploy\Project;

use Deploy\Contracts\PayloadContract;
use Deploy\Payload\BitbucketPayload;
use Deploy\Payload\GithubPayload;
use UnexpectedValueException;


class ProjectFactory {

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
                return app('project.bitbucket')->registerPayload($payload);
            case $payload instanceof GithubPayload:
                return app('project.github')->registerPayload($payload);
            default:
                throw new UnexpectedValueException('Can\'t detect payload.');
        }
    }

    /**
     * Return new ProjectFactory instance.
     *
     * @return static
     */
    public static function create()
    {
        return new static;
    }
}
