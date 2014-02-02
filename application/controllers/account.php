<?php

	class Account_Controller extends Base_Controller
	{
		public function action_index()
		{
			echo "This is the profile page.";
		}

		public function action_login()
		{
			echo "This is the login form.";
		}

		public function action_logout()
		{
			echo "This is the logout action.";
		}

		public function action_welcome($place, $name)
		{
			$data = array(
				'name' => $name,
				'place' => $place
			);

			//return View::make('account.welcome')->with('place', $place)->with('name',$name);
			return View::make('account.welcome', $data);
		}

		public function action_page()
		{
			return View::make('account.page');
		}
	}