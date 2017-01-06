<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDiscussesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('discusses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type');
			$table->boolean('pin')->default(0);
			$table->integer('post_id')->nullable();
			$table->integer('user_id');
			$table->string('title');
			$table->text('body');
			$table->text('keywords');
			$table->text('view_data')->nullable();
			$table->timestamp('comment_at')->nullable();
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
		Schema::drop('discusses');
	}

}
