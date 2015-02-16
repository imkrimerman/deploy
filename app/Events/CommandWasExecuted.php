<?php namespace Deploy\Events;

use Deploy\Events\Event;

use Illuminate\Queue\SerializesModels;
use im\Primitive\String\String;

class CommandWasExecuted extends Event {

	use SerializesModels;

	/**
	 * @var \Deploy\Events\String
	 */
	public $command;

	/**
	 * @var
	 */
	public $output;

	/**
	 * Create a new event instance.
	 *
	 * @param \im\Primitive\String\String $command
	 * @param \im\Primitive\String\String $output
	 */
	public function __construct(String $command, String $output)
	{
		$this->command = $command;
		$this->output = $output;
	}
}
