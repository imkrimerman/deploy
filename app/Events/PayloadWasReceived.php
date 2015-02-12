<?php namespace Deploy\Events;

use Illuminate\Queue\SerializesModels;
use Deploy\Payload\PayloadContract;

class PayloadWasReceived extends Event {

	use SerializesModels;

	/**
	 * Payload.
	 *
	 * @var \Deploy\Payload\PayloadContract
	 */
	public $payload;

	/**
	 * Create a new event instance.
	 *
	 * @param \Deploy\Payload\PayloadContract $payload
	 */
	public function __construct(PayloadContract $payload)
	{
		$this->payload = $payload;
	}
}
