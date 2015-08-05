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

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Chrisbjr\ApiGuard\Models\ApiKey;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $fillable = array('username', 'email', 'password', 'quota_used');
	protected $guarded = array('password');

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	/**
	* Get the unique identifier for the user.
	*
	* @return mixed
	*/
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	* Get the password for the user.
	*
	* @return string
	*/
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	* Get the e-mail address where password reminders are sent.
	*
	* @return string
	*/
	public function getReminderEmail()
	{
		return $this->email;
	}

	/**
	* Mutator: Hash and Set password
	*
	* @return none
	*/
	public function setPasswordAttribute($value)
	{
		$this->attributes['password'] = Hash::make($value);
	}

	/**
	* Gets the pending email changes for a user
	*
	* @return EmailChange
	*/
	public function emailChanges()
	{
		return EmailChange::firstOrCreate(['user_id' => $this->getKey()]);
	}

	/**
	* Get all files uploaded by this user
	*
	* @return UploadedFile
	*/
	public function links()
	{
		return $this->hasMany('Link')->orderBy('created_at', 'desc');
	}

	/**
	* Get all files uploaded by this user
	*
	* @return UploadedFile
	*/
	public function linksByPage($limit = 10)
	{
		return $this->links()->paginate($limit);
	}

	/**
	* Get the api key that belongs to user
	*
	* @return ApiKey
	*/
	public function apiKey()
	{
		$apiKey = ApiKey::where('user_id', '=', $this->getKey())->first();
		if (isset($apiKey))
		{
			return $apiKey;
		}
		else
		{
			$apiKey = new ApiKey;
			$apiKey->key = $apiKey->generateKey();
			$apiKey->user_id = $this->getKey();
			$apiKey->level = 10;
			$apiKey->ignore_limits = 0; //False
			$apiKey->save();
			return $apiKey;
		}
	}

	/**
	* Generate random activation code
	*
	* @return string
	*/
	public function generateCode($length = 12)
	{
		$avail_chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$avail_chars_len = strlen($avail_chars);
		do {
			$newCode = "";
			for ($i = 0; $i < $length; $i++) {
				$newCode .= $avail_chars[mt_rand(0, $avail_chars_len - 1)];
			}
		}
		while ($this->codeExists($newCode));

		return $newCode;
	}

	/**
	 * Checks whether a key exists in the database or not
	 *
	 * @param $code
	 * @return bool
	 */
	private function codeExists($code)
	{
		$codeCount = self::where('activation_code', '=', $code)->limit(1)->count();
		if ($codeCount > 0) return true;
		return false;
	}

}
