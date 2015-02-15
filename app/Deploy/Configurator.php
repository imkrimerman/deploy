<?php namespace Deploy\Deploy;

use Illuminate\Contracts\Config\Repository;

class Configurator {

    /**
     * Configuration repository.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $repository;

    /**
     * Deployment directory.
     *
     * @var \im\Primitive\String\String
     */
    protected $directory;

    /**
     * Deploy configuration file name.
     *
     * @var \im\Primitive\String\String
     */
    protected $file;

    /**
     * Constructor.
     *
     * @param \Illuminate\Contracts\Config\Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
        $this->directory = string($this->repository->get('deploy.directory'));
        $this->file = string($this->repository->get('deploy.file'));

        app()->instance('configurator', 'Deploy\Deploy\Configurator');
    }

    /**
     * Get deployment directory name.
     *
     * @return \im\Primitive\String\String
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Get configuration file name.
     *
     * @return \im\Primitive\String\String
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Get Configuration Repository.
     *
     * @return \Illuminate\Contracts\Config\Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }
}
