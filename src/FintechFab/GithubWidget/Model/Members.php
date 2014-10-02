<?php

namespace FintechFab\GithubWidget\Model;

use Eloquent;

//use FintechFab\Models\Issues;

/**
 * Class Members
 *
 * @package FintechFab\Models
 *
 * @property string   $repo
 * @property string   $login
 * @property string   $avatar_url
 * @property integer  $contributions //This is a pull request merged but user does not have collaborator access
 *
 */
class Members extends Eloquent implements ModelInterface
{

	protected $table = 'github_members';
	protected $connection = 'ff-gitw';
	protected $primaryKey = 'login';

	/**
	 * @return Issues
	 */
	public function issues()
	{
		return Issues::where("user_login", $this->login)->get();
	}

	/**
	 * @return Members
	 */
	public function users()
	{
		return Issues::where("user_login", $this->login)->get();
	}

	public function getMyName()
	{
		return 'user';
	}

	public function dataGitHub($inData)
	{
		$this->repo = $inData->repo;
		$this->login = $inData->login;
		$this->avatar_url = $inData->avatar_url;
		if (!empty($inData->contributions)) {
			$this->contributions = $inData->contributions;
		}

		return true;
	}

	public function updateFromGitHub($inData)
	{
		$changed = false;
		if ($this->avatar_url != $inData->avatar_url) {
			$this->avatar_url = $inData->avatar_url;
			$changed = true;
		}
		if (!empty($inData->contributions)) {
			if ($this->contributions != $inData->contributions) {
				$this->contributions = $inData->contributions;
				$changed = true;
			}
		}

		return $changed;
	}


}