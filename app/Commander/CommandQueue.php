<?php namespace Deploy\Commander;

use Deploy\Contracts\QueueContract;

class CommandQueue implements QueueContract {

    /**
     * Queue.
     *
     * @var \im\Primitive\Container\Container
     */
    protected $queue;

    /**
     * Construct.
     */
    public function __construct()
    {
        $this->queue = container();
    }

    /**
     * Add command with alias to queue.
     *
     * @param string $command
     * @param mixed $alias
     * @return $this
     */
    public function alias($command, $alias)
    {
        $this->queue->set($alias, $command);

        return $this;
    }

    /**
     * Pull command from Queue.
     *
     * @param mixed $alias
     * @param null|mixed  $default
     * @return mixed|null
     * @throws \im\Primitive\Support\Exceptions\OffsetNotExistsException
     */
    public function pull($alias, $default = null)
    {
        if ( ! $this->queue->has($alias)) return $default;

        return $this->queue->pull($alias);
    }

    /**
     * Push command to a Queue.
     *
     * @param string $command
     * @return $this
     */
    public function push($command)
    {
        return $this->queue->push($command);
    }

    /**
     * Prepend command to queue.
     *
     * @param string $command
     * @return $this
     */
    public function prepend($command)
    {
        return $this->queue->prepend($command);
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
     * Pop first queued command.
     *
     * @return string
     */
    public function pop()
    {
        return $this->queue->shift();
    }

    /**
     * Process (join) all commands.
     *
     * @param string $sequence
     * @return \im\Primitive\String\String
     */
    public function processAll($sequence = '|')
    {
        $sequence = string($sequence)->surround(' ');

        return $this->queue->join($sequence);
    }
}
