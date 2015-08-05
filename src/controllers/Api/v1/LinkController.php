<?php

/*
 * This file is part of GEKKO URL Shortener
 *
 * (c) Daniel McAssey <hello@glokon.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GLOKON\GEKKO\Controllers\Api\v1;

use Chrisbjr\ApiGuard\Controllers\ApiGuardController;
use Chrisbjr\ApiGuard\Models\ApiKey;
use Chrisbjr\ApiGuard\Transformers\ApiKeyTransformer;

use GLOKON\GEKKO\Models\Link;

class LinkController extends ApiGuardController {

	protected $apiMethods = [
		'postShorten' => [
			'keyAuthentication' => true,
		]
	];

	/**
	 * Shorten a new URL
	 *
	 * @return Response
	 */
	public function postShorten()
	{
		// No big url
		if(!\Input::has('bigurl'))
			return \Response::json(array('error' => array('code' => 'MISSING-PARAMETERS', 'http_code' => '400', 'message' => 'Bad Request')), 400);

		$bigURL = \Input::get('bigurl');
		$user = $this->apiKey->user;

		// No user linked to API key - SHOULD NEVER HAPPEN
		if(!isset($user))
			return \Response::json(array('error' => array('code' => 'NOT-AUTH', 'http_code' => '403', 'message' => 'Forbidden: SHOULD NEVER HAPPEN!')), 403);

		// User has gone over quota so cant shorten
		if($user->quota_max != 0 && ($user->quota_used + 1) > $user->quota_max)
			return \Response::json(array('error' => array('code' => 'QUOTA-USED', 'http_code' => '400', 'message' => 'Bad Request')), 403);

		if (filter_var($bigURL, FILTER_VALIDATE_URL) === false)
			return \Response::json(array('error' => array('code' => 'URL-INVALID', 'http_code' => '400', 'message' => 'Bad Request')), 400);

		$dbLink = \Link::where('destination', '=', $bigURL)->first();
		if (!isset($dbLink))
		{
			$dbLink = new \Link;
			$dbLink->user_id = $user->id;
			$dbLink->code = $dbLink->generateCode();
			$dbLink->destination = $bigURL;
			$dbLink->clicks = "0";
			$dbLink->save();

			$user->quota_used += 1;
			$user->save();
		}

		$linkCode = $dbLink->code;
		$linkURL = \Request::root().'/'.$linkCode;
		return \Response::json(array('ok' => array('code' => 'LINK-SHORTENED', 'http_code' => '200', 'message' => 'OK', 'data' => array('url' => $linkURL, 'url_code' => $linkCode))), 200);
	}

}
