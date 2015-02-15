<?php namespace Deploy\Commands;

use Deploy\Events\PayloadWasReceived;
use Deploy\Payload\PayloadFactory;
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
	 * @return void
	 */
	public function handle()
	{
		event(new PayloadWasReceived($this->payload));
	}
}
