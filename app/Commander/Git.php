<?php namespace Deploy\Commander;

use Deploy\Contracts\VcsContract;

class Git implements VcsContract {

    /**
     * @var string
     */
    protected $git;

    /**
     * Construct.
     *
     * @param string $gitPath
     */
    public function __construct($gitPath = 'git')
    {
        $this->git = $gitPath;
    }

    /**
     * Pull Command.
     *
     * @return string
     */
    public function pull()
    {
        return "{$this->git} pull";
    }

    /**
     * Push Command.
     *
     * @return string
     */
    public function push()
    {
        return "{$this->git} push";
    }

    /**
     * Commit Command.
     *
     * @param string $message
     * @param string $options
     * @return string
     */
    public function commit($message, $options = '')
    {
        return "{$this->git} commit -a -m '{$message}' {$options}";
    }

    /**
     * Reset HEAD Command.
     *
     * @return string
     */
    public function reset()
    {
        return "{$this->git} reset --hard HEAD";
    }
}
