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
	// lets get our posts and eager load the
	// author
	$posts = Post::with('author')->all();

	return View::make('pages.home')->with('posts', $posts);
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

Route::get('account/test_template', 'account@page');

Route::get('test/inject', array(
	'do' => function() {
		Section::inject('title', 'My Site');
		return View::make('account.page');
	}
));

Route::get('make_password', function() {
	echo Hash::make('12345678');
});

Route::get('login', function() {
	return View::make('login');
});

Route::post('login', function() {
	// get POST data
	$userdata = array(
		'username' => Input::get('username'),
		'password' => Input::get('password')
	);

	if ( Auth::attempt($userdata) )
	{
		// we are now logged in, go to home
		return Redirect::to('home');
	}
	else
	{
		// auth failure! lets go back to the login
		// pass any error notification you want
		// i like to do it this way :)
		return Redirect::to('login')->with('login_errors', true);
	}
});

Route::get('logout', function() {
	Auth::logout();
	return Redirect::to('login');
});

Route::get('home', array('before' => 'auth', 'do' => function() {
	return View::make('home');
}));

Route::get('view/(:num)', function($post) {
	// this is our single view
	$post = Post::find($post);
	return View::make('pages.full')->with('post', $post);
});

Route::get('admin', array('before' => 'auth', 'do' => function() {
	// show the create new post form
	$user = Auth::user();
	return View::make('pages.new')->with('user', $user);
}));

Route::post('admin', array('before' => 'auth', 'do' => function() {
	// let's get the new post from the POST data
	// this is much safer than using mass assignment
	$new_post = array(
		'title' => Input::get('title'),
		'body' => Input::get('body'),
		'author_id' => Input::get('author_id')
	);

	// let's setup some rules for our new data
	// I'm sure you can come up with better ones
	$rules = array(
		'title' => 'required|min:3|max:128',
		'body' => 'required'
	);

	// make the validator
	$v = Validator::make($new_post, $rules);

	if ( $v->fails() )
	{
		// redirect back to the form with
		// errors, input and our currently
		// logged in user
		return Redirect::to('admin')->with('user', Auth::user())
									->with_errors($v)
									->with_input();
	}
	
	// create the new post
	$post = new Post($new_post);
	$post->save();
	
	// redirect to viewing our new post
	return Redirect::to('view/'.$post->id);
}));

Route::get('login', function() {
	// show the login form
	return View::make('pages.login');
});

Route::post('login', function() {
	// handle the login form
	$userdata = array(
		'username' => Input::get('username'),
		'password' => Input::get('password')
	);
	
	if ( Auth::attempt($userdata) )
	{
		return Redirect::to('admin');
	}
	else
	{
		return Redirect::to('login')->with('login_errors', true);
	}
});

Route::get('logout', function() {
	// logout from the system
	Auth::logout();
	return Redirect::to('/');
});