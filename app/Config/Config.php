<?php namespace Deploy\Config;

use im\Primitive\Container\Container;

class Config extends Container {

    /**
     * Append configuration from yaml.
     *
     * @param string $yaml
     * @return $this
     */
    public function appendFromYaml($yaml)
    {
        foreach ($this->parseYaml($yaml) as $key => $value)
        {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * Parse Yaml file.
     *
     * @param string $yaml
     * @return array
     */
    public function parseYaml($yaml)
    {
        return Yaml::parse($yaml);
    }
}
