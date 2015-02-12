<?php namespace Deploy\Project;

use Deploy\Payload\BitbucketPayload;
use Deploy\Payload\GithubPayload;
use Deploy\Payload\PayloadContract;

class ProjectFactory {

    /**
     * Make new Payload instance. Depends on payload.
     *
     * @param \Deploy\Payload\PayloadContract $payload
     * @return \Deploy\Project\ProjectContract
     */
    public function make(PayloadContract $payload)
    {
        switch(true)
        {
            case $payload instanceof BitbucketPayload:
                return app('project.bitbucket')->payload($payload);
            case $payload instanceof GithubPayload:
                return app('project.github')->payload($payload);
            default:
                throw new \UnexpectedValueException('Can\'t detect payload.');
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
