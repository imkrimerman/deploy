<?php namespace Deploy\Config;

use Deploy\Contracts\FactoryContract;
use Deploy\Contracts\ProjectContract;
use Deploy\Project\BitbucketProject;
use Deploy\Project\GithubProject;

class ProjectConfigFactory implements FactoryContract {

    public function make(ProjectContract $instance)
    {
        switch (true)
        {
            case $instance instanceof BitbucketProject:
                return new BitbucketConfig($instance);
            case $instance instanceof GithubProject:
                return new GithubConfig($instance);
            default:
                throw new UnexpectedValueException('Can\'t detect project instance: '.get_class($instance));
        }
    }

    public static function create()
    {
        return new static;
    }
}
