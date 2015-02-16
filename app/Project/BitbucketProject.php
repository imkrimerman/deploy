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
    protected function detectBranches()
    {
        $commits = $this->payload->getCommits();

        $branches = container();

        if ($commits->isEmpty()) return $branches;

        $commits->each(function($commit) use ($branches)
        {
            $branches->push($commit['branch']);
        });

        return $branches->unique();
    }

    /**
     * Set Project pending state from branches.
     *
     * @param \im\Primitive\Container\Container $branches
     * @return \im\Primitive\String\String
     */
    public function stateFromBranches($branches)
    {
        switch (true)
        {
            case $this->exists && $this->branches->isEmpty():
                return string('merge');
            case $this->exists && ! $this->branches->isEmpty():
                return string('pull');
            case $this->exists && ! $this->config->configured:
                return string('setup');
            case ! $this->exists:
                return string('clone');
            default:
                throw new RuntimeException('Unknown project state.');
        }
    }
}
