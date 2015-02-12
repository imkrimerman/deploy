<?php namespace Deploy\Contracts;

interface RepositoryContract {

    /**
     * @return \im\Primitive\Container\Container
     */
    public function all();

    /**
     * @param $item
     * @return bool
     */
    public function has($item);

    /**
     * @param $item
     * @return mixed
     */
    public function get($item);
}
