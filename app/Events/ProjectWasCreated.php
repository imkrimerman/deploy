<?php namespace Deploy\Events;

use Deploy\Project\ProjectContract;
use Illuminate\Queue\SerializesModels;

class ProjectWasCreated extends Event {

	use SerializesModels;

	/**
	 * @var \Deploy\Project\Project
	 */
	public $project;

	/**
	 * Create a new event instance.
	 *
	 * @param \Deploy\Project\ProjectContract $project
	 */
	public function __construct(ProjectContract $project)
	{
		$this->project = $project;
	}
}
