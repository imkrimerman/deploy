<?php namespace Deploy\Project;

use Deploy\Contracts\PayloadContract;
use Deploy\Events\PayloadWasReceived;
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
                $project = app('Bitbucket.Project')->registerPayload($payload); break;
            case $payload instanceof GithubPayload:
                $project = app('Github.Project')->registerPayload($payload); break;
            default:
                throw new UnexpectedValueException('Can\'t detect payload.');
        }

        event(new ProjectWasCreated($project));

        return $project;
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
