<?php namespace Deploy\Events;

use Deploy\Contracts\ProjectContract;
use Illuminate\Queue\SerializesModels;

class ProjectWasCreated extends Event {

	use SerializesModels;

	/**
	 * @var \Deploy\Contracts\ProjectContract
	 */
	public $project;

	/**
	 * Create a new event instance.
	 *
	 * @param \Deploy\Contracts\ProjectContract $project
	 */
	public function __construct(ProjectContract $project)
	{
		$this->project = $project;
	}
}
