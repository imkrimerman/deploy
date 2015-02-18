<?php namespace Deploy\Handlers\Events;

use Deploy\Events\ProjectWasPreconfigured;

use Deploy\Project\ProjectCloner;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class ClonePreconfiguredProject {

    /**
     * @var \Deploy\Project\ProjectCloner
     */
    protected $cloner;

    /**
     * Create the event handler.
     *
     * @param \Deploy\Project\ProjectCloner $cloner
     */
	public function __construct(ProjectCloner $cloner)
    {
        $this->cloner = $cloner;
    }

	/**
	 * Handle the event.
	 *
	 * @param  ProjectWasPreconfigured  $event
	 * @return void
	 */
	public function handle(ProjectWasPreconfigured $event)
	{
        $this->cloner->process($event->config);
	}

}
