<?php namespace Deploy\Contracts;

use Deploy\Config\ProjectConfig;


interface ProjectConfigContract {

    /**
     * Parse Yaml file.
     *
     * @param string $yaml
     * @return array
     */
    public function parseYaml($yaml);

    /**
     * Append configuration from yaml.
     *
     * @param string $yaml
     * @return $this
     */
    public function appendFromYaml($yaml);

    /**
     * Get configuration keys.
     *
     * @return array
     */
    public function getConfigKeys();

    /**
     * Make url to clone project.
     *
     * @param \Deploy\Contracts\PayloadContract $payload
     * @param string $provider
     * @return \im\Primitive\String\String
     */
    public function getCloneUrl(PayloadContract $payload, $provider);

    /**
     * Update from deploy config file if exists.
     *
     * @return $this
     * @throws \im\Primitive\String\Exceptions\StringException
     */
    public function update();
}
