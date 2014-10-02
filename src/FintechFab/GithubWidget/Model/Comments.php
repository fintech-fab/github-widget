<?php

namespace FintechFab\GithubWidget\Model;

use Eloquent;


/**
 * Class Comments
 *
 * @package FintechFab\Models
 *
 * @property string  $repo
 * @property integer $id
 * @property string  $html_url
 * @property integer $issue_number
 * @property integer $created  //Поля метки времени из другой БД переименованы
 * @property integer $updated
 * @property string  $user_login
 * @property string  $prev     //preview, начало текста комментария
 *
 * @method Comments whereIssueNumber static
 * @method Comments whereRepo static
 *
 */
class Comments extends Eloquent implements ModelInterface
{
	public $timestamps = false; //Используются даные GitHub'а (поля "timestamps" из другой БД)
	protected $connection = 'ff-gitw';
	protected $table = 'github_comments';

	/**
	 * @return Issues
	 */
	public function issue()
	{
		return Issues::where("number", $this->issue_number)->first();
	}

	/**
	 * @return Members
	 */
	public function user()
	{
		return $this->belongsTo(Members::class, 'user_login', 'login');
	}


	public function getMyName()
	{
		return 'issue comment';
	}

	public function dataGitHub($inData)
	{
		if (!isset(Members::find($inData->user->login)->login)) {
			$user = new Members;
			$user->login = $inData->user->login;
			$user->repo = $inData->repo;
			$user->save();
		}
		$this->id = $inData->id;
		$this->repo = $inData->repo;
		$this->html_url = $inData->html_url;
		$n = explode('/', $inData->issue_url);
		$this->issue_number = $n[count($n) - 1];
		//Поля метки времени из другой БД должны быть переименованы
		$this->created = $inData->created_at;
		$this->updated = $inData->updated_at;

		$this->user_login = $inData->user->login;
		$this->prev = $this->trimCommentBody($inData->body);

		return true;
	}

	public function updateFromGitHub($inData)
	{
		if ((str_replace(" ", "T", $this->updated) . "Z") == $inData->updated_at) //<-------
		{
			return false;
		} else {
			$this->updated = $inData->updated_at;
			$this->prev = $this->trimCommentBody($inData->body);

			return true;
		}
	}

	private function trimCommentBody($str)
	{
		$body = strip_tags($str);

		return (mb_strlen($body) > 27)
			? (mb_substr($body, 0, 26) . "...")
			: $body;

	}

}