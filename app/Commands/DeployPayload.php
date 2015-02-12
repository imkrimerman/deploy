<?php namespace Deploy\Commands;

use Deploy\Events\PayloadWasReceived;
use Deploy\Payload\PayloadFactory;
use Illuminate\Contracts\Bus\SelfHandling;

class DeployPayload extends Command implements SelfHandling {

	/**
	 * @var \im\Primitive\Container\Container
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
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		event(new PayloadWasReceived($this->payload));
	}
}
