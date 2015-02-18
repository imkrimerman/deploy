<?php namespace Deploy\Contracts;


interface QueueContract extends Contract {

    /**
     * Process (join) all commands.
     *
     * @param string $sequence
     * @return \im\Primitive\String\String
     */
    public function release($sequence = ';');

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
