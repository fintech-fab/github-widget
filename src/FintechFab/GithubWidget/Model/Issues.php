<?php

namespace FintechFab\GithubWidget\Model;

use Eloquent;

//use FintechFab\Models\Members;

/**
 * Class Issues
 *
 * @package FintechFab\Models
 *
 * @property integer $number
 * @property string  $html_url
 * @property string  $title
 * @property string  $state
 * @property integer $created
 * @property integer $updated
 * @property integer $closed
 * @property string  $user_login
 * @property string  $repo
 *
 * @method Issues whereState static
 * @method Issues whereRepo static
 *
 */
class Issues extends Eloquent implements ModelInterface
{
	public $timestamps = false;
	public $incrementing = false;
	protected $connection = 'ff-gitw';
	protected $table = 'github_issues';
	protected $primaryKey = ['number','repo'];

	/**
	 * @return Members
	 */
	public function user()
	{
		return $this->belongsTo(Members::class, 'user_login', 'login');

	}

	/**
	 * @return Comments[]
	 */
	public function comments()
	{
		return Comments::where("issue_number", $this->number)->orderBy("created")->get();

	}

	public function getMyName()
	{
		return 'issue';
	}


	public function dataGitHub($inData)
	{
		if (!isset(Members::find($inData->user->login)->login)) {
			$user = new Members;
			$user->login = $inData->user->login;
			$user->repo = $inData->repo;
			$user->save();
		}
		$this->repo = $inData->repo;
		$this->html_url = $inData->html_url;
		$this->number = $inData->number;
		$this->title = $inData->title;
		$this->state = $inData->state;
		$this->created = $inData->created_at;
		$this->updated = $inData->updated_at;
		if (!empty($inData->closed_at)) {
			$this->closed = $inData->closed_at;
		}
		$this->user_login = $inData->user->login;

		return true;
	}

	public function updateFromGitHub($inData)
	{
		$this->repo = $inData->repo;
		$changed = false;

		if ($this->html_url != $inData->html_url) {
			$this->html_url = $inData->html_url;
			$changed = true;
		}
		if ($this->title != $inData->title) {
			$this->title = $inData->title;
			$changed = true;
		}
		if ($this->state != $inData->state) {
			$this->state = $inData->state;
			$changed = true;
		}
		if ((str_replace(" ", "T", $this->updated) . "Z") != $inData->updated_at) //<--------
		{
			$this->updated = $inData->updated_at;
			$changed = true;
		}
		if (is_null($this->closed) && (!is_null($inData->closed_at))) {
			$this->closed = $inData->closed_at;
			$changed = true;
		}

		return $changed;
	}


}