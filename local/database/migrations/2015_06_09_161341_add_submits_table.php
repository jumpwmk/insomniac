<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubmitsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('submits', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('task_id');
			$table->integer('user_id');
			$table->text('result')->nullable();
			$table->text('compile_result')->nullable();
			$table->double('time')->default(0);
			$table->integer('memory')->default(0);
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
		Schema::drop('submits');
	}

}
