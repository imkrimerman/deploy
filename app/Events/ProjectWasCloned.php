<?php namespace Deploy\Events;

use Deploy\Contracts\ProjectConfigContract;
use Illuminate\Queue\SerializesModels;

class ProjectWasCloned extends Event {

	use SerializesModels;

    /**
     * Project config.
     *
     * @var \Deploy\Contracts\ProjectConfigContract
     */
    public $config;

    /**
     * Create a new event instance.
     *
     * @param \Deploy\Contracts\ProjectConfigContract $config
     */
	public function __construct(ProjectConfigContract $config)
	{
        $this->config = $config;
    }

}
