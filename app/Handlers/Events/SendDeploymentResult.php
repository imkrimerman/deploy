<?php namespace Deploy\Handlers\Events;

use Deploy\Events\CommandWasExecuted;

use Deploy\Support\StaticLogger;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use im\Primitive\String\String;


class SendDeploymentResult {

    /**
     * Mailer.
     *
     * @var \Illuminate\Mail\Mailer
     */
    protected $mail;

    /**
     * Static Logger.
     *
     * @var \Deploy\Support\StaticLogger
     */
    protected $staticLogger;

    /**
     * Create the event handler.
     *
     * @param \Illuminate\Mail\Mailer $mail
     */
	public function __construct(Mailer $mail)
	{
        $this->mail = $mail;

        $this->staticLogger = StaticLogger::instance();

        $this->notify = app('config')['deploy.notify'];
    }

	/**
	 * Handle the event.
	 *
	 * @param  CommandWasExecuted  $event
	 * @return void
	 */
	public function handle(CommandWasExecuted $event)
	{
        if (empty($this->notify)) return;

		$log = $this->staticLogger->join();

        $this->send($log);
	}

    /**
     * Send log.
     *
     * @param \im\Primitive\String\String $log
     */
    protected function send(String $log)
    {
        $this->mail->send($log(), [], function(Message $message)
        {
            $message->to($this->notify)->subject('Deployment finished');
        });
    }

}
