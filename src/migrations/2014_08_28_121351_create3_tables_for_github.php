<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Create3TablesForGithub extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('github_events', function (Blueprint $table) {
			$table->integer('id')->unsigned()->primary(); //id из GitHub
			$table->string('repo', 100)->default('');
			$table->string('type', 20)->default(''); //Тип события
			$table->string('actor_login', 20)->default('');
			$table->timestamp('created')->default('0000-00-00 00:00:00');
			$table->string('payload', 200)->default(''); //Содержание события (полученные данные обрабатываются, сохраняя как текст)
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('github_events');
	}

}
