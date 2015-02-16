<?php namespace Deploy\Project;

use Deploy\Contracts\RepositoryContract;
use Deploy\Deploy\Configurator;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class ProjectRepository implements RepositoryContract{

    /**
     * Deploy configurator.
     *
     * @var \Deploy\Deploy\Configurator
     */
    protected $config;

    /**
     * Yaml Parser.
     *
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected $yaml;

    /**
     * All projects.
     *
     * @var \im\Primitive\Container\Container
     */
    protected $projects;

    /**
     * Constructor.
     *
     * @param \Deploy\Deploy\Configurator $config
     * @param \Symfony\Component\Yaml\Yaml $yaml
     */
    public function __construct(Configurator $config, Yaml $yaml)
    {
        $this->config = $config;
        $this->yaml = $yaml;
        $this->projects = $this->getProjects();
    }

    /**
     * Check if project with given $name exists.
     *
     * @param mixed $name
     * @return bool
     */
    public function has($name)
    {
        return $this->projects->has($name);
    }

    /**
     * Retrieve project by $name.
     *
     * @param mixed $name
     * @return \im\Primitive\Container\Container
     */
    public function get($name)
    {
        return $this->projects->get($name);
    }

    /**
     * Return all projects.
     *
     * @return \im\Primitive\Container\Container
     */
    public function all()
    {
        return $this->projects;
    }

    /**
     * Scan for all projects that have configuration file.
     *
     * @return array
     */
    protected function getProjects()
    {
        $projects = iterator_to_array($this->getFinder());

        foreach ($projects as $path => $fileInfo)
        {
            unset($projects[$path]);

            $file = string($path)->contents()->value();

            $parsed = $this->parseConfig($file);

            $projects[$parsed['slug']] = [
                'file' => $parsed,
                'path' => pathinfo($path, PATHINFO_DIRNAME),
                'fileInfo' => $fileInfo,
                'exists' => true
            ];
        }

        return container($projects);
    }

    /**
     * Get Projects Finder.
     *
     * @param int $depth
     * @return \Symfony\Component\Finder\Finder
     */
    protected function getFinder($depth = 1)
    {
        $find = Finder::create()->ignoreDotFiles(false)->files()->depth($depth);

        return $find->in($this->config->getDirectory())
                    ->name($this->config->getFile()->value());

    }

    /**
     * Return parsed Yaml configuration.
     *
     * @param string $yamlConfig
     * @return array
     */
    public function parseConfig($yamlConfig)
    {
        return $this->yaml->parse($yamlConfig);
    }

    /**
     * Return Configurator.
     *
     * @return Configurator
     */
    public function getConfigurator()
    {
        return $this->config;
    }
}
