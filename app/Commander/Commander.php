<?php namespace Deploy\Commander;

use Deploy\Contracts\VcsContract;
use Illuminate\Filesystem\Filesystem;


class Commander {

    /**
     * Command Queue.
     *
     * @var \Deploy\Commander\CommandQueue
     */
    protected $queue;

    /**
     * Vcs commander.
     *
     * @var \Deploy\Contracts\VcsContract
     */
    protected $vcs;

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Construct.
     *
     * @param \Deploy\Commander\CommandQueue $queue
     * @param \Deploy\Contracts\VcsContract $vcs
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     */
    public function __construct(CommandQueue $queue, VcsContract $vcs, Filesystem $filesystem)
    {
        $this->queue = $queue;
        $this->vcs = $vcs;
        $this->filesystem = $filesystem;
    }

    /**
     * Change current working directory.
     * If $dir is null than it changes to Deploy base path.
     *
     * @param null|string $dir
     * @return $this
     */
    public function dir($dir = null)
    {
        if ( ! is_null($dir) && $this->filesystem->isDirectory($dir))
        {
            chdir($dir);
        }

        chdir(base_path());

        return $this;
    }

    /**
     * Get Vcs Instance.
     *
     * @return VcsContract
     */
    public function getVcs()
    {
        return $this->vcs;
    }

    /**
     * Get Queue Instance.
     *
     * @return CommandQueue
     */
    public function getQueue()
    {
        return $this->queue;
    }
}
