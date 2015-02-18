<?php namespace Deploy\Support;

class StaticLogger {

    /**
     * Logger.
     *
     * @var \im\Primitive\Container\Container
     */
    protected $log;

    /**
     * Logger instance.
     *
     * @var \Deploy\Support\StaticLogger
     */
    private static $instance;

    /**
     * Construct.
     */
    protected function __construct()
    {
        $this->log = container();
    }

    /**
     * Write log message.
     *
     * @param string $message
     */
    public function write($message)
    {
        $this->log->push($message);
    }

    /**
     * Join log.
     *
     * @return \im\Primitive\String\String
     */
    public function join()
    {
        return $this->log->join("\n");
    }

    /**
     * Get Static Logger instance.
     *
     * @return \Deploy\Support\StaticLogger
     */
    public static function instance()
    {
        if (static::$instance === null)
        {
            static::$instance = new static;
        }

        return static::$instance;
    }
}
