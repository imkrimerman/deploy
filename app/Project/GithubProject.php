<?php namespace Deploy\Project;

class GithubProject extends Project {

    /**
     * Remote Provider.
     *
     * @var string
     */
    protected $provider = 'github';

    /**
     * Detect Project branches.
     *
     * @return \im\Primitive\Container\Container
     */
    protected function branchFromPayload()
    {
        // TODO: Implement branchFromPayload() method.
    }

    /**
     * Set Project pending state from branches.
     *
     * @param \im\Primitive\Container\Container $branches
     * @return \im\Primitive\String\String
     */
    public function stateFromCommits($branches)
    {
        // TODO: Implement stateFromCommits() method.
    }
}
