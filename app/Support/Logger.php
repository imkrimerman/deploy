<?php namespace Deploy\Support;

use Deploy\Events\PayloadWasReceived;
use Deploy\Events\ProjectWasCreated;
use Illuminate\Log\Writer;

class Logger extends Writer {

    /**
     * Log when payload was received.
     *
     * @param \Deploy\Events\PayloadWasReceived $event
     */
    public function payloadReceived(PayloadWasReceived $event)
    {
        $project = $event->payload->getName();

        $this->info("Payload for {$project} was received.");
    }

    /**
     * Log when project was created.
     *
     * @param \Deploy\Events\ProjectWasCreated $event
     */
    public function projectCreated(ProjectWasCreated $event)
    {
        $project = $event->project;

        $this->info("Project was successfully created.");
        $this->info("Project provider: {$project->getProvider()}");
        $this->info("Project pending state: {$project->getState()}");
    }

    /**
     * Dynamically pass log calls into the writer.
     *
     * @param  string  $level
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function write($level, $message, array $context = [])
    {
        return $this->log($level, 'Deploy -> '.$message, $context);
    }
}
