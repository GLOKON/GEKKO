<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Email Change</h2>

		<div>
			An email change was detected on your account "<b>{{ $username }}</b>".<br />
			To complete the email change, please confirm this by visiting this link: <a href="{{ URL::to(Config::get("gekko::gekko.path").'/user/confirm-email', array($token), Config::get("gekko::gekko.https")) }}" target="_blank">{{ URL::to(Config::get("gekko::gekko.path").'/user/confirm-email', array($token), Config::get("gekko::gekko.https")) }}</a><br/>
			This link will expire in {{ Config::get('auth.reminder.expire', 60) }} minutes.<br />
			<br /><br />If you did not request this change please contact us immediately.
		</div>
	</body>
</html>