<?php

	class User_Controller extends Base_Controller
	{
		public function action_index()
		{
			//$user = User::find(2);
			$users = User::all();
			
			foreach($users as $user)
			{
				echo $user->username;
				echo '<br />';
			}
		}

		public function action_raw()
		{
			$user = new Raw();
			var_dump($user->get_all_user());
		}
	}