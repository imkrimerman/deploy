<?php namespace Deploy\Contracts;

use Deploy\Vcs\Git;


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

    /**
     * Get vcs path.
     *
     * @return string
     */
    public function getVcsPath();

    /**
     * Set vcs path.
     *
     * @param string $vcsPath
     * @return $this
     */
    public function setVcsPath($vcsPath);
}
