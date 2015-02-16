<?php namespace Deploy\Support;

use Deploy\Events\ChangedWorkingDir;
use Deploy\Events\CommandWasExecuted;
use Deploy\Events\PayloadWasReceived;
use Deploy\Events\ProjectWasCreated;

class Logger {

    /**
     * Logger.
     *
     * @var \Illuminate\Log\Writer
     */
    protected $log;

    /**
     * Construct.
     */
    public function __construct()
    {
        $this->log = app('log');
    }

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
        $this->info("Project pending state: {$project->getStates()}");
    }

    /**
     * Log when working directory is changed.
     *
     * @param \Deploy\Events\ChangedWorkingDir $event
     */
    public function changedWorkingDir(ChangedWorkingDir $event)
    {
        $this->info("Deployer changed working directory to: {$event->dir}");
    }

    /**
     * Log when command was executed.
     *
     * @param \Deploy\Events\CommandWasExecuted $event
     */
    public function commandExecuted(CommandWasExecuted $event)
    {
        $this->info("Executed command: {$event->command}");
        $this->info("Shell output: {$event->output}");
    }

    /**
     * Log info.
     *
     * @param string $message
     * @param array $context
     */
    public function info($message, array $context = [])
    {
        $this->write('info', $message, $context);
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
        return $this->log->write($level, 'Deploy -> '.$message, $context);
    }
}
