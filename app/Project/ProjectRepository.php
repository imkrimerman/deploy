<?php namespace Deploy\Project;

use Deploy\Deploy\Configurator;
use Symfony\Component\Finder\Finder;

class ProjectRepository {

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
     * @param \Deploy\Project\Yaml $yaml
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
     * @return mixed
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
        $finder = Finder::create();

        $projects = iterator_to_array(
            $finder->files()->name($this->config->getFile())->in($this->config->getDirectory())
        );

        foreach ($projects as $key => $project)
        {
            $parsed = $this->yaml->parse($project);

            $projects[$parsed['name']] = $parsed;
        }

        return container($projects);
    }
}
