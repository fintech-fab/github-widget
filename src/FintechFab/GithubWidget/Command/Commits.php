<?php

namespace FintechFab\GithubWidget\Command;

class Commits extends Reader
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'fintech-fab:git-hub-commits';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command for receiving of commits from GitHub API';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->init();
		$this->issuesEvents();
		$this->events();
		$this->comments();
	}

}