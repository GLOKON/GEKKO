<?php

/*
 * This file is part of GEKKO URL Shortener
 *
 * (c) Daniel McAssey <hello@glokon.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

## API Routes
Route::group(['prefix' => 'api/v1', 'namespace' => 'GLOKON\GEKKO\Controllers\Api\v1'], function() {
	Route::post( 'shorten', array(
		'as' => 'api.shortenURL',
		'uses' => 'LinkController@postShorten'
	) );
});

## User Control Panel Routes
Route::group(['prefix' => \Config::get('gekko::gekko.path'), 'namespace' => 'GLOKON\GEKKO\Controllers'], function() {
	## Guest Routes
	Route::group(['before' => 'guest'], function() {
		## Login Route
		Route::post('login', function() {
			if(Input::has('username') && Input::has('password'))
			{
				$userInput = array(
					'username' => Input::get('username'),
					'password' => Input::get('password')
				);

				if (Auth::attempt($userInput, ((Input::get('rememberme') == 'on') ? true : false)))
				{
					if (Auth::check())
					{
						if(Auth::user()->is_activated)
						{
							return Redirect::route('user.index')->with('flash_notice', Lang::get('gekko::user.login_success'));
						}
						else
						{
							Auth::logout();
							return Redirect::route('login')->with('flash_error', Lang::get('gekko::user.not_activated'));
						}
					}
				}
			}

			return Redirect::route('login')->with('flash_error', Lang::get('gekko::user.login_failure'))->withInput();
		});

		Route::get('login', array('as' => 'login', function() {
			return View::make('gekko::user.login');
		}));

		## Register Routes only allowed if user registration is enabled
		if(Config::get("gekko::gekko.registration")) {
			Route::get( 'register/confirm/{id}', array(
				'as' => 'register.confirm',
				'uses' => 'UserController@getConfirm'
			) )->where('id', '[a-zA-Z0-9]+');

			Route::post( 'register', array(
				'as' => 'register.post',
				'uses' => 'UserController@postRegister'
			) );

			Route::get('register', array('as' => 'register', function() {
				return View::make('gekko::user.register');
			}));
		}

		## Default route for guests is login
		Route::get('/', array('as' => 'login.index', function() {
			return Redirect::route('login');
		}));
	});

	## Auth Routes
	Route::group(['before' => 'gekko.auth'], function() {
		## Auth -> Logout
		Route::get('logout', array('as' => 'logout', function()
		{
			Auth::logout();
			return Redirect::route('login')->with('flash_notice', Lang::get('gekko::user.logout_success'));
		}));

		## Auth -> AJAX Routes
		Route::group(['prefix' => 'ajax'], function()
		{
			Route::post( 'user/generateApiKey', array(
				'as' => 'user.newApiKey',
				'uses' => 'UserController@postGenerateNewApiKey'
			) );

			Route::get( 'user/getApiKey', array(
				'as' => 'user.getApiKey',
				'uses' => 'UserController@getApiKey'
			) );

			Route::post( 'user/changePassword', array(
				'as' => 'user.changePassword',
				'uses' => 'UserController@postChangePassword'
			) );

			Route::post( 'user/updateProfile', array(
				'as' => 'user.updateProfile',
				'uses' => 'UserController@postUpdateProfile'
			) );

			Route::post( 'link/shorten', array(
				'as' => 'link.shortenLink',
				'uses' => 'LinkController@postShorten'
			) );

			Route::post( 'link/delete', array(
				'as' => 'link.deleteLink',
				'uses' => 'LinkController@postDelete'
			) );

			Route::post( 'link/getLatest', array(
				'as' => 'link.getLatestLink',
				'uses' => 'LinkController@getLatest'
			) );
		});

		## Auth -> Homepage
		Route::get( '/', array(
			'as' => 'user.index',
			'uses' => 'LinkController@index'
		) );
	});

	## Admin Routes
	Route::group(['prefix' => 'admin', 'namespace' => 'GLOKON\GEKKO\Controllers\Admin', 'before' => 'gekko.auth|gekko.admin'], function() {
		Route::get( '/', array(
			'as' => 'admin.index',
			'uses' => 'AdminController@index'
		) );
	});
});

Route::get( '/{id}', array(
	'as' => 'index.show',
	'uses' => 'GLOKON\GEKKO\Controllers\LinkController@show'
) )->where('id', '[a-zA-Z0-9]+');
