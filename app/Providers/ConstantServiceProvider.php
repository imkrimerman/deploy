<?php namespace Deploy\Providers;

use Illuminate\Support\ServiceProvider;

class ConstantServiceProvider extends ServiceProvider {

    protected $constants = [
        'DS' => DIRECTORY_SEPARATOR,
    ];

    /**
     * Register the constants.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->constants as $constant => $value)
        {
            $this->define($constant, $value);
        }
    }

    /**
     * Define constant if not defined.
     *
     * @param string $constant
     * @param mixed $value
     */
    public function define($constant, $value)
    {
        if ( ! defined($constant)) define($constant, $value);
    }
}
