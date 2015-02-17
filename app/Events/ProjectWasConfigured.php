<?php namespace Deploy\Events;

use Deploy\Contracts\ProjectConfigContract;
use Illuminate\Queue\SerializesModels;

class ProjectWasConfigured extends Event {

	use SerializesModels;

    /**
     * Config.
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
