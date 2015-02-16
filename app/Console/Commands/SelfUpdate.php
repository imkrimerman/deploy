<?php namespace Deploy\Console\Commands;

use Deploy\Commander\Commander;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SelfUpdate extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'self-update';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update Deployer.';

	/**
	 * Create a new command instance.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @param \Deploy\Commander\Commander $commander
	 * @return mixed
	 */
	public function fire(Commander $commander)
	{
		$commander->selfUpdate();
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [];
	}

}
