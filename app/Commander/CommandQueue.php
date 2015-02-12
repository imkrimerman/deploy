<?php namespace Deploy\Commander;

class CommandQueue {

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
     * Add command to queue. Alias can be specified with second argument.
     *
     * @param string $command
     * @param null|mixed $alias
     * @return $this
     */
    public function add($command, $alias = null)
    {
        if ( ! is_null($alias))
        {
            $this->queue->set($alias, $command);
        }
        else
        {
            $this->enqueue($command);
        }

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
    public function enqueue($command)
    {
        return $this->queue->push($command);
    }

    /**
     * Get first queued command.
     *
     * @return string
     */
    public function dequeue()
    {
        return $this->queue->shift();
    }
}
