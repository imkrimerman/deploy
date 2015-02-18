<?php namespace Deploy\Events;

use Deploy\Events\Event;

use Illuminate\Queue\SerializesModels;

class ChangedWorkingDir extends Event {

	use SerializesModels;

    /**
     * Working directory.
     *
     * @var string
     */
    public $dir;

    /**
	 * Create a new event instance.
	 *
	 * @param $dir
	 */
	public function __construct($dir)
	{
		$this->dir = $dir;
	}
}
