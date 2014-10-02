<?php

namespace FintechFab\GithubWidget\Model;

use Eloquent;


/**
 * Class Events
 *
 * @package FintechFab\Models
 *
 * @property integer $id
 * @property string  $type
 * @property string  $actor_login
 * @property integer $created
 * @property string  $payload
 * @property string  $repo
 *
 * @method Events whereType static
 * @method Events whereRepo static
 *
 */
class Events extends Eloquent implements ModelInterface
{
	public $timestamps = false;
	protected $connection = 'ff-gitw';
	protected $table = 'github_events';

	/**
	 * @return Members
	 */
	public function user()
	{
		return Members::find($this->actor_login);
	}


	public function getMyName()
	{
		return 'event';
	}

	public function dataGitHub($inData)
	{
		if (!self::isAcceptData($inData)) {
			return false;
		}

		if (!isset(Members::find($inData->actor->login)->login)) {
			$user = new Members;
			$user->login = $inData->actor->login;
			$user->repo = $inData->repo;
			$user->save();
		}

		$this->repo = $inData->repo;
		$this->id = $inData->id;
		$this->type = $inData->type;
		$this->actor_login = $inData->actor->login;
		$this->created = $inData->created_at;

		if ($inData->type == 'IssuesEvent') {
			if ($inData->payload->action == 'opened') {
				$action = "Открыта задача: ";
			} elseif ($inData->payload->action == 'closed') {
				$action = "Задача закрыта: ";
			} else {
				$action = "Action \"{$inData->payload->action}\": ";
			}
			$this->payload = $action . $inData->payload->issue->number . ' ' . $inData->payload->issue->title;
		}

		return true;
	}

	public function updateFromGitHub($inData)
	{
		return false;
	}

	/**
	 * @param object $inData
	 *
	 * @return bool
	 */
	public static function isAcceptData($inData)
	{
		if ($inData->type != 'IssuesEvent') //Возможные события также "CreateEvent", "DeleteEvent":  $inData->payload->ref_type ("branch")
		{
			return false;
		}

		return true;
	}

}