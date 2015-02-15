<?php namespace Deploy\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('Illuminate\Contracts\Auth\Registrar', 'Deploy\Services\Registrar');
		$this->app->bind('Deploy\Contracts\VcsContract', 'Deploy\Vcs\Git');
		$this->app->bind('project.bitbucket', 'Deploy\Project\BitbucketProject');
		$this->app->bind('project.github', 'Deploy\Project\GithubProject');
	}
}
