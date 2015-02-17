<?php namespace Deploy\Contracts;

use Deploy\Commander\CommandQueue;


interface QueueContract extends Contract {

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
    public function commands($sequence = '|');

    /**
     * Prepend command to queue.
     *
     * @param string $command
     * @return $this
     */
    public function prepend($command);

    /**
     * Alias for push.
     *
     * @param string $command
     * @return \Deploy\Commander\CommandQueue
     */
    public function after($command);

    /**
     * Alias for prepend.
     *
     * @param string $command
     * @return \Deploy\Commander\CommandQueue
     */
    public function before($command);
}
