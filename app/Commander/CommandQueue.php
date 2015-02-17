<?php namespace Deploy\Commander;

use Deploy\Contracts\QueueContract;
use im\Primitive\Container\Container;

class CommandQueue extends Container implements QueueContract {

    /**
     * Add command with alias to queue.
     *
     * @param string $command
     * @param mixed $alias
     * @return $this
     */
    public function alias($command, $alias)
    {
        $this->set($alias, $command);

        return $this;
    }

    /**
     * Alias for push.
     *
     * @param string $command
     * @return \Deploy\Commander\CommandQueue
     */
    public function after($command)
    {
        return $this->push($command);
    }

    /**
     * Alias for prepend.
     *
     * @param string $command
     * @return \Deploy\Commander\CommandQueue
     */
    public function before($command)
    {
        return $this->prepend($command);
    }

    /**
     * Process (join) all commands.
     *
     * @param string $sequence
     * @return \im\Primitive\String\String
     */
    public function commands($sequence = ';')
    {
        $sequence = string($sequence)->append(' ');

        return $this->join($sequence);
    }
}
