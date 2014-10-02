<?php

namespace FintechFab\GithubWidget\Model;

use Eloquent;


/**
 * Class Refcommits
 *
 * @package FintechFab\Models
 *
 * @property string $repo
 * @property integer $id
 * @property string  $actor_login
 * @property string  $commit_id
 * @property integer $created
 * @property integer $issue_number
 * @property string  $message
 *
 * @method Refcommits whereIssueNumber static
 * @method Refcommits whereRepo static
 */
class Refcommits extends Eloquent implements ModelInterface
{
	public $timestamps = false;
	protected $connection = 'ff-gitw';
	protected $table = 'github_refcommits';

	/**
	 * @return Issues
	 */
	public function issue()
	{
		return $this->belongsTo(Issues::class, 'issue_number', 'number');
	}

	/**
	 * @return Members
	 */
	public function user()
	{
		return $this->belongsTo(Members::class, 'actor_login', 'login');
	}


	public function getMyName()
	{
		return 'issue commit';
	}

	public function dataGitHub($inData)
	{
		if ($inData->event != 'referenced') {
			return false;
		}

		if (!isset(Members::find($inData->actor->login)->login)) {
			$user = new Members;
			$user->login = $inData->actor->login;
			$user->repo = $inData->repo;
			$user->save();
		}

		$this->id = $inData->id;
		$this->repo = $inData->repo;
		$this->commit_id = $inData->commit_id;
		$this->actor_login = $inData->actor->login;
		$this->created = $inData->created_at;
		$this->issue_number = $inData->issue->number;
		if (isset($inData->message)) {
			$this->message = $inData->message;
		}

		return true;
	}

	public function updateFromGitHub($inData)
	{
		if (isset($inData->message)) {
			if ($this->message == '') {
				$this->message = $inData->message;

				return true;
			}
		}

		return false;
	}
}