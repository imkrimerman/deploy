<?php namespace Deploy\Commands;

use Deploy\Commander\Commander;
use Deploy\Events\PayloadWasReceived;
use Deploy\Payload\PayloadFactory;
use Deploy\Project\ProjectFactory;
use Illuminate\Contracts\Bus\SelfHandling;

class DeployPayload extends Command implements SelfHandling {

	/**
	 * @var \Deploy\Contracts\PayloadContract
     */
	protected $payload;

	/**
	 * Create a new command instance.
	 *
	 * @param string $payload
	 */
	public function __construct($payload)
	{
		$this->payload = PayloadFactory::create()->make($payload);

        event(new PayloadWasReceived($this->payload));
	}

    /**
     * Fire event. Execute the command.
     *
     * @param \Deploy\Commander\Commander $commander
     * @param \Deploy\Project\ProjectFactory $factory
     */
	public function handle(Commander $commander, ProjectFactory $factory)
	{
        $project = $factory->make($this->payload);

        $commander->handle($project);
	}
}
