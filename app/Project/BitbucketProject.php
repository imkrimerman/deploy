<?php namespace Deploy\Project;

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
        if ( ! $this->exists) return 'clone';

        $state = $branches->isEmpty() ? 'merge' : 'pull';

        return string($state);
    }
}
