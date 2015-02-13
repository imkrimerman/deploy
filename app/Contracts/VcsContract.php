<?php namespace Deploy\Contracts;

interface VcsContract {

    /**
     * Construct.
     *
     * @param string $vcsPath
     */
    public function __construct($vcsPath = '');

    /**
     * Pull Command.
     *
     * @return string
     */
    public function pull();

    /**
     * Push Command.
     *
     * @return string
     */
    public function push();

    /**
     * Commit Command.
     *
     * @param string $message
     * @param string $options
     * @return string
     */
    public function commit($message, $options = '');

    /**
     * Reset HEAD Command.
     *
     * @return string
     */
    public function reset();

    /**
     * Make new command.
     *
     * @param string $command
     * @return string
     */
    public function command($command);
}
