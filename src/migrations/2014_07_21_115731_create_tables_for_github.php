<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTablesForGithub extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('github_members', function (Blueprint $table) {
			$table->string('login', 20)->primary();
			$table->string('repo', 100)->default('');
			$table->string('avatar_url', 100)->default('');
			$table->integer('contributions')->default(0);
			$table->timestamps();
		});
		Schema::create('github_issues', function (Blueprint $table) {

			$table->string('repo', 100)->default('');
			$table->integer('number')->default(0);

			$table->string('html_url', 100)->default('');
			$table->string('title', 100)->default('');
			$table->string('state', 10)->default('');
			$table->timestamp('created')->default('0000-00-00 00:00:00');
			$table->timestamp('updated')->default('0000-00-00 00:00:00');
			$table->timestamp('closed')->nullable();
			$table->string('user_login', 20)->default('');
			$table->foreign('user_login')->references('login')->on('github_members');

			$table->primary(['repo', 'number']);

		});
		Schema::create('github_comments', function (Blueprint $table) {
			$table->integer('id')->unsigned()->primary(); //id из GitHub
			$table->string('repo', 100)->default('');
			$table->string('html_url', 100)->default('');
			$table->integer('issue_number')->default(0);
			$table->timestamp('created')->default('0000-00-00 00:00:00');
			$table->timestamp('updated')->default('0000-00-00 00:00:00');
			$table->string('user_login', 20)->default('');
			$table->string('prev', 30);
		});
		/**
		 * GET /issues/events
		 *  event:referenced
		 */
		Schema::create('github_refcommits', function (Blueprint $table) {
			$table->integer('id')->unsigned()->primary(); //id из GitHub
			$table->string('repo', 100)->default('');
			$table->string('commit_id', 40)->default(0);
			$table->string('actor_login', 20)->default('');
			$table->timestamp('created')->default('0000-00-00 00:00:00');
			$table->integer('issue_number')->default(0);
			$table->string('message', 256)->default('');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('github_refcommits');
		Schema::dropIfExists('github_comments');
		Schema::dropIfExists('github_issues');
		Schema::drop('github_members');
	}

}
