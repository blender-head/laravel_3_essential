<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your application using Laravel's RESTful routing and it
| is perfectly suited for building large applications and simple APIs.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|		Route::get('hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Route::post(array('hello', 'world'), function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Route::put('hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/

Route::get('/', function()
{
	return View::make('home.index');
});

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Route::get('/', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login');
});

Route::get('account/profile', array('as' => 'profile', 'do' => function()
{
	return View::make('account/profile');
}));

Route::get('profile', function()
{
	return Redirect::to_route('profile');
});

Route::get('user/(:any)/task/(:num)', function($username, $task_number)
{
	// $username will be replaced by the value of (:any)
	// $task_number will be replaced by the integer in place of (:num)
	$data = array(
		'username' => $username,
		'task' => $task_number
	);

	return View::make('tasks.for_user', $data);
});

Route::get('check_url', function() {
	//echo URL::base();
	//echo URL::current();
	//echo URL::full();
	echo URL::to_route('user/(:any)/task/(:num)', array(1,2));
});

Route::get('form_test', array(
	'as' => 'form_test',
	'do' => function() {
		echo Form::open_secure_for_files('my/route','post',array('id' => 'form_id'));

			echo Form::label('username', 'Username', array('id' => 'label_id'));
			echo Form::text('username', '', array('id' => 'username'));
			echo '<br />';
			echo Form::label('password', 'Password', array('id' => 'label_password'));
			echo Form::password('password', array('id' => 'password'));
			echo '<br />';
			echo Form::file('file', array('file_id'));
			echo '<br />';
			echo Form::submit('Login');

		echo Form::close();
	}
));

Route::get('fluent', array(
	'as' => 'fluent',
	'do' => function() {
		$data = DB::table('users')->where('username', '=', 'andre')->get();
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
));

Route::controller('user');