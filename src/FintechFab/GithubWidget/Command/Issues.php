<?php

namespace FintechFab\GithubWidget\Command;

class Issues extends Reader
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'fintech-fab:git-hub-issues';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command for receiving of issues from GitHub API';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->init();
		$this->issues();
	}

}