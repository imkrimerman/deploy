<?php namespace Deploy\Events;

use Deploy\Events\Event;

use Illuminate\Queue\SerializesModels;

class ProjectWasCloned extends Event {

	use SerializesModels;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

}
