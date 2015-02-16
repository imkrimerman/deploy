<?php namespace Deploy\Contracts;

interface QueueContract {

    /**
     * Push command to a Queue.
     *
     * @param string $command
     * @return $this
     */
    public function push($command);

    /**
     * Pop first queued command.
     *
     * @return string
     */
    public function pop();

    /**
     * Process (join) all commands.
     *
     * @param string $sequence
     * @return \im\Primitive\String\String
     */
    public function processAll($sequence = '|');
}
