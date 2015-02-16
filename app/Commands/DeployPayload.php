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
	}

    /**
     * Fire event. Execute the command.
     *
     * @param \Deploy\Commander\Commander $commander
     */
	public function handle(Commander $commander)
	{
		event(new PayloadWasReceived($this->payload));

        $project = ProjectFactory::create()->make($this->payload);

        $commander->handle($project);
	}
}
