<?php namespace Deploy\Project;

class BitbucketProject extends Project {

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
}
