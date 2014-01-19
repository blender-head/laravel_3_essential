<?php

	class Raw
	{
		public function get_all_user()
		{
			$users = DB::query('SELECT * FROM users');
			return $users;
		}
	}