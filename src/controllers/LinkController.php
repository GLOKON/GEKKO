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

use GLOKON\GEKKO\Models\Link;

class LinkController extends \BaseController {


	/**
	 * Shorten a new URL
	 *
	 * @return Response
	 */
	public function postShorten()
	{
		if(!\Auth::check())
			return \Response::json(array('error' => array('code' => 'NOT-AUTH', 'http_code' => '403', 'message' => 'Forbidden')), 403);

		$shortenValidator = \Validator::make(\Input::all(),
			array(
				'url' 			=> 'required|url'
			)
		);

		if($shortenValidator->fails())
			return \Response::json(array('error' => array('code' => 'SHORTEN-VALIDATOR-FAILED', 'http_code' => '400', 'message' => 'Bad Request', 'data' => array('validator_messages' => $shortenValidator->messages()))), 400);

		$bigURL = \Input::get('url');

		// User has gone over quota so cant shorten
		if(\Auth::user()->quota_max != 0 && (\Auth::user()->quota_used + 1) > \Auth::user()->quota_max)
			return \Response::json(array('error' => array('code' => 'QUOTA-USED', 'http_code' => '400', 'message' => 'Bad Request')), 403);

		$dbLink = \Link::where('destination', '=', $bigURL)->first();
		if (!isset($dbLink))
		{
			$dbLink = new \Link;
			$dbLink->user_id = \Auth::user()->id;
			$dbLink->code = $dbLink->generateCode();
			$dbLink->destination = $bigURL;
			$dbLink->clicks = "0";
			$dbLink->save();

			\Auth::user()->quota_used += 1;
			\Auth::user()->save();
		}

		$linkCode = $dbLink->code;
		$linkURL = \Request::root().'/'.$linkCode;
		return \Response::json(array('ok' => array('code' => 'LINK-SHORTENED', 'http_code' => '200', 'message' => 'OK', 'data' => array('url' => $linkURL, 'url_code' => $linkCode))), 200);
	}


	/**
	 * Delete Link
	 *
	 * @return Response
	 */
	public function postDelete()
	{
		if(!\Auth::check())
			return \Response::json(array('error' => array('code' => 'NOT-AUTH', 'http_code' => '403', 'message' => 'Forbidden')), 403);

		if(!\Input::has("linkid"))
			return \Response::json(array('error' => array('code' => 'MISSING-PARAMETERS', 'http_code' => '400', 'message' => 'Bad Request')), 400);

		$linkID = \Input::get("linkid");
		$shortenedLink = \Link::find($linkID);

		if(\Auth::user()->id != $shortenedLink->user_id)
			return \Response::json(array('error' => array('code' => 'NOT-AUTH', 'http_code' => '403', 'message' => 'Forbidden')), 403);

		$shortenedLink->delete();

		if((\Auth::user()->quota_used - 1) >= 0)
		{
			\Auth::user()->quota_used -= 1;
			\Auth::user()->save();
		}

		return \Response::json(array('ok' => array('code' => 'LINK-DELETED', 'http_code' => '200', 'message' => 'OK')), 200);
	}


	/**
	 * Get latest links from last ID
	 *
	 * @return Response
	 */
	public function getLatest()
	{
		if(!\Auth::check())
			return \Response::json(array('error' => array('code' => 'NOT-AUTH', 'http_code' => '403', 'message' => 'Forbidden')), 403);

		if(!\Input::has("lastid"))
			return \Response::json(array('error' => array('code' => 'MISSING-PARAMETERS', 'http_code' => '400', 'message' => 'Bad Request')), 400);

		$lastLinkID = \Input::get("lastid");
		$linkList = \Link::where('user_id', '=', \Auth::user()->id)->where('id', '>', $lastLinkID)->get();

		if(count($linkList) <= 0)
			return \Response::json(array('error' => array('code' => 'NO-LINKS', 'http_code' => '200', 'message' => 'OK')), 200);

		$returnArray = array();
		for($i = 0; $i < count($linkList); $i++)
		{
			$selectedLink = $linkList[$i];
			$tmpArray = array();
			$tmpArray["id"] = $selectedLink->id;
			$tmpArray["code"] = $selectedLink->code;
			$tmpArray["destination"] = $selectedLink->destination;
			$tmpArray["clicks"] = $selectedLink->clicks;
			$tmpArray["date"] = date('Y-m-d G:i:s', strtotime($selectedLink->created_at));
			$returnArray[] = $tmpArray;
		}
		return \Response::json(array('ok' => array('code' => 'LINKS-RETRIEVED', 'http_code' => '200', 'message' => 'OK', 'data' => array('links' => $returnArray))), 200);
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(\Auth::check())
		{
			$lastLink = \Auth::user()->links()->first();
			$links = \Auth::user()->linksByPage(10);
			if($lastLink != null)
			{
				return \View::make('gekko::user.index')->with('shortenedLinks', $links)->with('lastShortenedLinkID', $lastLink->id);
			}
			else
			{
				return \View::make('gekko::user.index')->with('shortenedLinks', $links)->with('lastShortenedLinkID', 0);
			}
		}
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
		$dbLink = \Link::where('code', '=', $id)->first();
		if(!isset($dbLink))
			Response::make("Link not found, please try again!", 404);

		$dbLink->clicks = bcadd($dbLink->clicks, "1");
		$dbLink->save();
		return \Redirect::away($dbLink->destination);
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
