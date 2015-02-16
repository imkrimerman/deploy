<?php namespace Deploy\Providers;

use Deploy\Events\ChangedWorkingDir;
use Deploy\Events\CommandWasExecuted;
use Deploy\Events\PayloadWasReceived;
use Deploy\Events\ProjectWasCreated;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {

	/**
	 * The event handler mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		PayloadWasReceived::class => [
			'Deploy\Support\Logger@payloadReceived'
		],
		ProjectWasCreated::class => [
			'Deploy\Support\Logger@projectCreated'
		],
		ChangedWorkingDir::class => [
			'Deploy\Support\Logger@changedWorkingDir'
		],
		CommandWasExecuted::class => [
			'Deploy\Support\Logger@commandExecuted'
		]
	];

	/**
	 * Register any other events for your application.
	 *
	 * @param  \Illuminate\Contracts\Events\Dispatcher  $events
	 * @return void
	 */
	public function boot(DispatcherContract $events)
	{
		parent::boot($events);
	}

}
