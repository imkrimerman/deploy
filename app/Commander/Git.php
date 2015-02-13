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
        return $this->command('pull');
    }

    /**
     * Push Command.
     *
     * @return string
     */
    public function push()
    {
        return $this->command('push');
    }

    /**
     * Change branch.
     *
     * @param string $branch
     * @return string
     */
    public function checkout($branch)
    {
        return $this->command("checkout {$branch}");
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
        if ( ! empty($options)) $options = ' '.$options;

        return $this->command("commit -a -m '{$message}'{$options}");
    }

    /**
     * Reset HEAD Command.
     *
     * @return string
     */
    public function reset()
    {
        return $this->command('reset --hard HEAD');
    }

    /**
     * Make new command.
     *
     * @param string $command
     * @return string
     */
    public function command($command)
    {
        return "{$this->git} {$command}";
    }
}
