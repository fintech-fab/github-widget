<?php namespace FintechFab\GithubWidget;

use FintechFab\GithubWidget\Command\Commits;
use FintechFab\GithubWidget\Command\Common;
use FintechFab\GithubWidget\Command\Issues;
use FintechFab\GithubWidget\Command\Users;
use FintechFab\GithubWidget\Controller\Main;
use Illuminate\Support\ServiceProvider;
use Route;
use View;

class GithubWidgetServiceProvider extends ServiceProvider
{

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerCommands();
		$this->package('fintech-fab/github-widget', 'ff-gitw');
		View::addNamespace('ff-gitw', __DIR__ . '/Views');
		View::addExtension('html', 'php');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{


		$this->registerRoutes();

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

	private function registerCommands()
	{


		$this->app['command.ff-gitw.users'] = $this->app->share(function () {
			return new Users();
		});
		$this->commands('command.ff-gitw.users');


		$this->app['command.ff-gitw.issues'] = $this->app->share(function () {
			return new Issues();
		});
		$this->commands('command.ff-gitw.issues');


		$this->app['command.ff-gitw.commits'] = $this->app->share(function () {
			return new Commits();
		});
		$this->commands('command.ff-gitw.commits');


		$this->app['command.ff-gitw.common'] = $this->app->share(function () {
			return new Common();
		});
		$this->commands('command.ff-gitw.common');


	}

	private function registerRoutes()
	{

		Route::get('github', [
			'as'   => 'ff.gitw.index',
			'uses' => Main::class . '@index'
		]);

		Route::group(array('prefix' => '/github/api'), function(){
			Route::get('issues/last', Main::class . '@issuesLast');
			Route::get('comments/last', Main::class . '@commentsLast');
			Route::get('commits/last', Main::class . '@commitsLast');
		});

	}

}
