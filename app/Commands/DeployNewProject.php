<?php namespace Deploy\Commands;

use Deploy\Commander\Commander;
use Deploy\Contracts\ProjectContract;
use Illuminate\Contracts\Bus\SelfHandling;

class DeployNewProject extends Command implements SelfHandling {

	/**
	 * Project.
	 *
	 * @var \Deploy\Contracts\ProjectContract
	 */
	protected $project;

	/**
	 * Create a new command instance.
	 *
	 * @param \Deploy\Contracts\ProjectContract $project
	 */
	public function __construct(ProjectContract $project)
	{
		$this->project = $project;
	}

	/**
	 * Execute the command.
	 *
	 * @param \Deploy\Commander\Commander $commander
	 */
	public function handle(Commander $commander)
	{
		$commander->handleNewProject($this->project);
	}
}
