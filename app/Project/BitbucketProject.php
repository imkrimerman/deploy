<?php namespace Deploy\Project;

use RuntimeException;


class BitbucketProject extends Project {

    /**
     * Remote Provider.
     *
     * @var string
     */
    protected $provider = 'bitbucket';

    /**
     * Detect Project branches.
     *
     * @return \im\Primitive\Container\Container
     */
    protected function branchFromPayload()
    {
        $commits = $this->payload->getCommits();

        if ($commits->isEmpty()) return container();

        return $commits->get('0.branch');
    }

    /**
     * Set Project pending state from commits.
     *
     * @param \im\Primitive\Container\Container $commits
     * @return \im\Primitive\String\String
     */
    public function stateFromCommits($commits)
    {
        if ($commits->isEmpty()) return string('merge');

        return string('pull');
    }

}
