<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contests', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('type');
			$table->integer('task');
			$table->timestamp('start_register');
			$table->timestamp('end_register');
			$table->timestamp('start_contest');
			$table->timestamp('end_contest');
			$table->boolean('visible')->default(0);
			$table->text('data')->nullable();
			$table->text('detail')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('contests');
	}

}
