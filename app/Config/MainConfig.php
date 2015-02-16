<?php namespace Deploy\Config;

use Illuminate\Contracts\Config\Repository;
use im\Primitive\Container\Container;

class MainConfig extends Container {

    /**
     * Constructor MainConfig.
     *
     * @param \Illuminate\Contracts\Config\Repository $repository
     * @param array $keys
     */
    public function __construct(Repository $repository, array $keys = [])
    {
        $this->initialize([]);

        foreach ($this->getConfigKeys() + $keys as $key)
        {
            $this->set($key, string($repository->get($key)));
        }
    }

    /**
     * Get configuration keys.
     *
     * @return array
     */
    public function getConfigKeys()
    {
        return [
            'deploy.directory',
            'deploy.filename'
        ];
    }

}
