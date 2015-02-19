<?php namespace Deploy\Contracts;

interface VcsContract extends Contract {

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

    /**
     * Clone Project.
     *
     * @param $url
     * @param $branch
     * @return string
     */
    public function _clone($url, $branch);

    /**
     * Change branch.
     *
     * @param string $branch
     * @return string
     */
    public function checkout($branch);

    /**
     * Add File.
     *
     * @param string $file filename
     * @return string
     */
    public function add($file);

    /**
     * Commit Command.
     *
     * @param string $message
     * @param string $options
     * @return string
     */
    public function commit($message, $options = '');
}
