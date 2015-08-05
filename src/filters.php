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
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('gekko.auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('manage/login');
		}
	}
	else
	{
		if(!Auth::user()->is_activated)
		{
			Auth::logout();
			return Redirect::route('login')->with('flash_error', Lang::get('user.not_activated'));
		}
	}
});


Route::filter('gekko.auth.admin', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('manage/login');
		}
	}
	else
	{
		if(!Auth::user()->is_activated)
		{
			Auth::logout();
			return Redirect::route('login')->with('flash_error', Lang::get('user.not_activated'));
		}

		if(!Auth::user()->is_admin)
		{
			return Redirect::route('user.index')->with('flash_error', Lang::get('site.unauthorized'));
		}
	}
});