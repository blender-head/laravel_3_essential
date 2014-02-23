<?php

class Myauth extends Laravel\Auth\Drivers\Driver 
{
	public function attempt($arguments = array())
	{
		$username = $arguments['username'];
		$password = $arguments['password'];

		$result = my_login_method($username, $password);

		if($result)
		{
			return $this->login($result->id, array_get($arguments, 'remember'));
		}
			
		return false;
	}

	public function retrieve($id)
	{
		return get_my_user_object($id);
	}