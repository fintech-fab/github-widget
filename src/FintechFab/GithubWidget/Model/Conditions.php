<?php

namespace FintechFab\GithubWidget\Model;

use Eloquent;

/**
 * Class Conditions
 * Хранит данные из заголовка ответа API GitHub;
 * для отправки в заголовке повторных запросов к API GitHub.
 *
 * @package FintechFab\Models
 *
 * @property string  $repo
 * @property string  $repo_item
 * @property string  $condition
 * @method Conditions whereRepoItem static
 * @method Conditions whereRepo static
 *
 * //Most responses return an ETag header. Many responses also return a Last-Modified header.
 * //You can use the values of these headers to make subsequent requests to those resources
 * //using the If-None-Match and If-Modified-Since headers, respectively.
 */
class Conditions extends Eloquent
{
	protected $table = 'github_conditional_requests';
	protected $connection = 'ff-gitw';
	protected $primaryKey = 'repo_item';

}