<?php

class Create_Users {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table) {
			// auto incremental id (PK)
			$table->increments('id');
			
			// varchar 32
			$table->string('username', 32);
			$table->string('email', 320);
			$table->string('password', 64);
			
			// int
			$table->integer('role');
			
			// boolean
			$table->boolean('active');
			
			// created_at | updated_at DATETIME
			$table->timestamps();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}