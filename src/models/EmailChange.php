<?php

/*
 * This file is part of GEKKO URL Shortener
 *
 * (c) Daniel McAssey <hello@glokon.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GLOKON\GEKKO\Models;

class EmailChange extends \Eloquent {
	protected $fillable = array('user_id', 'token', 'new_email');

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'email_changes';

	/**
	* Get the user that the email change belongs to.
	*
	* @return user
	*/
	public function user() {
		return $this->belongsTo('User');
	}

	/**
	* Generate a token for email change
	*
	* @return String
	*/
	public function generateToken()
	{
		$this->token = md5(uniqid(mt_rand(), true));
		$this->save();
		return $this->token;
	}

}