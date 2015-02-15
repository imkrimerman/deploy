<?php namespace Deploy\Events;

use Deploy\Contracts\PayloadContract;
use Illuminate\Queue\SerializesModels;

class PayloadWasReceived extends Event {

	use SerializesModels;

	/**
	 * Payload.
	 *
	 * @var \Deploy\Contracts\PayloadContract
	 */
	public $payload;

	/**
	 * Create a new event instance.
	 *
	 * @param \Deploy\Contracts\PayloadContract $payload
	 */
	public function __construct(PayloadContract $payload)
	{
		$this->payload = $payload;
	}
}
