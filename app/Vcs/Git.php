<?php namespace Deploy\Vcs;

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
        return $this->command('pull origin');
    }

    /**
     * Push Command.
     *
     * @return string
     */
    public function push()
    {
        return $this->command('push origin HEAD');
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
     * Add File.
     *
     * @param string $file filename
     * @return string
     */
    public function add($file)
    {
        return $this->command("add {$file}");
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
     * Clone Project.
     *
     * @param string $url
     * @return string
     */
    public function _clone($url)
    {
        return $this->command("clone {$url}");
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

    /**
     * Get vcs path.
     *
     * @return string
     */
    public function getVcsPath()
    {
        return $this->git;
    }

    /**
     * Set vcs path.
     *
     * @param string $vcsPath
     * @return $this
     */
    public function setVcsPath($vcsPath)
    {
        $this->git = $vcsPath;

        return $this;
    }


}
