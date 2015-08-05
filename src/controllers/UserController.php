<?php

/*
 * This file is part of GEKKO URL Shortener
 *
 * (c) Daniel McAssey <hello@glokon.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GLOKON\GEKKO\Controllers;

use Chrisbjr\ApiGuard\Models\ApiKey;

class UserController extends \BaseController {


	/**
	 * Confirm email change
	 *
	 * @return Redirect
	 */
	public function getConfirmEmail()
	{
		$confirmValidator = \Validator::make(\Input::all(),
			array(
				'token'			=> 'required|min:1',
			)
		);
		if($confirmValidator->fails())
			return \Redirect::route('user.index')->with('flash_error', \Lang::get('gekko::user.confirm_email_no_token'))->withInput();

		$emailChangeData = \EmailChange::where('token', '=', \Input::get('token'))->first();

		if(is_null($emailChangeData))
			return \Redirect::route('user.index')->with('flash_error', \Lang::get('gekko::user.confirm_email_token_mismatch'))->withInput();

		$expiry_minutes = \Config::get('auth.reminder.expire', 60);
		$expiry_time = $emailChangeData->updated_at->timestamp + ($expiry_minutes * 60);

		if($expiry_time < time())
			return \Redirect::route('user.index')->with('flash_error', \Lang::get('gekko::user.confirm_email_token_expired'))->withInput();

		$new_email = $emailChangeData->new_email;
		$emailChangeData->user->email = $new_email;
		$emailChangeData->user->save();
		$emailChangeData->token = "";
		$emailChangeData->new_email = "";
		$emailChangeData->save();

		return \Redirect::route('user.index')->with('flash_notice', \Lang::get('gekko::user.confirm_email_success', array('email' => $new_email)))->withInput();
	}


	/**
	 * Change the user password
	 *
	 * @return Response
	 */
	public function postChangePassword()
	{
		if(!\Auth::check())
			return \Response::json(array('error' => array('code' => 'NOT-AUTH', 'http_code' => '403', 'message' => 'Forbidden')), 403);

		$passwordValidator = \Validator::make(\Input::all(),
			array(
				'old_password' 			=> 'required',
				'new_password'			=> 'required|min:8',
				'new_password_confirm'	=> 'required|same:new_password'
			)
		);

		if($passwordValidator->fails())
			return \Response::json(array('error' => array('code' => 'PASSWORD-VALIDATOR-FAILED', 'http_code' => '400', 'message' => 'Bad Request', 'data' => array('validator_messages' => $passwordValidator->messages()))), 400);

		$current_password = \Input::get("old_password");
		$new_password = \Input::get("new_password");

		if(!\Hash::check($current_password, \Auth::user()->getAuthPassword()))
			return \Response::json(array('error' => array('code' => 'MISMATCH-PASSWORD', 'http_code' => '400', 'message' => 'Bad Request')), 400);

		\Auth::user()->password = $new_password;
		if(!\Auth::user()->save())
			return \Response::json(array('error' => array('code' => 'GENERIC-ERROR', 'http_code' => '500', 'message' => 'Internal Server Error')), 400);

		\Mail::send('emails.auth.passwordchanged', array('username' => \Auth::user()->username), function($message)
		{
			$message->to(\Auth::user()->email, \Auth::user()->username)->subject(\Lang::get('gekko::site.title') . ' - ' . \Lang::get('gekko::email.subject_password_change'));
		});

		return \Response::json(array('ok' => array('code' => 'PASSWORD-CHANGED', 'http_code' => '200', 'message' => 'OK')), 200);
	}


	/**
	 * Change the user profile details
	 *
	 * @return Response
	 */
	public function postUpdateProfile()
	{
		if(\Auth::check())
		{
			$profileValidator = \Validator::make(\Input::all(),
				array(
					'email' => 'required|email|unique:users',
				)
			);
			if($profileValidator->fails() && \Auth::user()->email != \Input::get("email"))
				return \Response::json(array('error' => array('code' => 'PROFILE-VALIDATOR-FAILED', 'http_code' => '400', 'message' => 'Bad Request', 'data' => array('validator_messages' => $profileValidator->messages()))), 400);

			$changes = false;

			if(\Auth::user()->email != \Input::get("email"))
			{
				$changes = true;
				\Auth::user()->emailChanges()->generateToken();
				$email_change = \Auth::user()->emailChanges();
				$email_change->new_email = \Input::get("email");
				$email_change->save();
				\Mail::send('emails.auth.emailchanged', array('username' => \Auth::user()->username, 'token' => \Auth::user()->emailChanges()->token), function($message)
				{
					$message->to(\Input::get("email"), \Auth::user()->username)->subject(\Lang::get('gekko::site.title') . ' - ' . \Lang::get('gekko::email.subject_email_change'));
				});
			}

			if(!$changes)
				return \Response::json(array('error' => array('code' => 'NO-CHANGES', 'http_code' => '200', 'message' => 'OK')), 200);

			\Auth::user()->save();
			return \Response::json(array('ok' => array('code' => 'PROFILE-UPDATED', 'http_code' => '200', 'message' => 'OK')), 200);
		}
		return \Response::json(array('error' => array('code' => 'NOT-AUTH', 'http_code' => '403', 'message' => 'Forbidden')), 403);
	}


	/**
	 * Register a user
	 *
	 * @return Redirect
	 */
	public function postRegister()
	{
		if(!\Auth::check()) // Make sure user isnt logged in
		{
			$registerValidator = \Validator::make(\Input::all(),
				array(
					'username' 				=> 'required|alpha_num|unique:users|between:4,32',
					'email'					=> 'required|email|unique:users',
					'password'				=> 'required|min:8',
					'password_confirm'		=> 'required|same:password'
				)
			);
			if($registerValidator->fails())
				return \Redirect::route('register')->with('flash_error', $registerValidator->messages())->withInput();

			$userCreationInput = \Input::only(['username', 'email', 'password']);
			$user = \User::create($userCreationInput);
			if(!isset($user))
				return \Redirect::route('register')->with('flash_error', \Lang::get('gekko::user.register_failure'));

			if($user->id == 1) // First user = Admin
			{
				$user->is_admin = true; // Make Admin
				$user->quota_max = 0; // Infinity max urls
				$user->is_activated = true; // Make Admin user automatically activated
			}
			else
			{
				$user->activation_code = $user->generateCode();

				\Mail::send('emails.auth.confirmemail', array('username' => $user->username, 'activation_code' => $user->activation_code), function($message) use ($user)
				{
					$message->to($user->email, $user->username)->subject(\Lang::get('gekko::site.title') . ' - ' . \Lang::get('gekko::email.subject_email_confirm'));
				});
			}

			$user->save();

			return \Redirect::route('login')->with('flash_notice', \Lang::get('gekko::user.register_success'));
		}
	}


	/**
	 * Confirm a user
	 *
	 * @return Redirect
	 */
	public function getConfirm($id)
	{
		if(!\Auth::check()) // Make sure user isnt logged in
		{
			$user = \User::where('activation_code', '=', $id)->first();
			if(!isset($user))
				return \Redirect::route('login')->with('flash_error', \Lang::get('gekko::user.register_failure_invalid_code'));

			$user->activation_code = '';
			$user->is_activated = true;
			$user->save();

			return \Redirect::route('login')->with('flash_notice', \Lang::get('gekko::user.register_success_confirm'));
		}
	}


	/**
	 * Generate a new API Key for current logged in user
	 *
	 * @return Response
	 */
	public function postGenerateNewApiKey()
	{
		if(\Auth::check())
		{
			$apiKey = \Auth::user()->apiKey();
			$apiKey->key = $apiKey->generateKey();
			$apiKey->save();
			return \Response::json(array('ok' => array('code' => 'API-KEY-GENERATED', 'http_code' => '200', 'message' => 'OK', 'data' => array('api_key' => $apiKey->key))), 200);
		}

		return \Response::json(array('error' => array('code' => 'NOT-AUTH', 'http_code' => '403', 'message' => 'Forbidden')), 403);
	}


	/**
	 * Return API key from current logged in user
	 *
	 * @return Response
	 */
	public function getApiKey()
	{
		if(\Auth::check())
		{
			$apiKey = \Auth::user()->apiKey();
			return \Response::json(array('ok' => array('code' => 'API-KEY-RETRIEVED', 'http_code' => '200', 'message' => 'OK', 'data' => array('api_key' => $apiKey->key))), 200);
		}

		return \Response::json(array('error' => array('code' => 'NOT-AUTH', 'http_code' => '403', 'message' => 'Forbidden')), 403);
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
