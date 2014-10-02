<?php

namespace FintechFab\GithubWidget\Command;

class Common extends Reader
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'fintech-fab:git-hub-common';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command for receiving of all data from GitHub API';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->init();
		$this->users();
		$this->issues();
		$this->issuesEvents();
		$this->events();
		$this->comments();
	}

}