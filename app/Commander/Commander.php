<?php namespace Deploy\Commander;

use Deploy\Contracts\VcsContract;
use Illuminate\Filesystem\Filesystem;


class Commander {

    /**
     * Command Queue.
     *
     * @var \Deploy\Commander\CommandQueue
     */
    private $queue;

    /**
     * Vcs commander.
     *
     * @var \Deploy\Contracts\VcsContract
     */
    private $vcs;

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $filesystem;

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
    protected function dir($dir = null)
    {
        if ( ! is_null($dir) && $this->filesystem->isDirectory($dir))
        {
            chdir($dir);
        }

        chdir(base_path());

        return $this;
    }
}
