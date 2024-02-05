<?php
require_once("tmp.php");
require_once("Config.php");

setcookie(USER_COKKIE_VALUNE_ID,"",time()-1,USER_COOKIE_PATH,USER_COOKIE_DOMAIN);
setcookie(USER_COKKIE_VALUNE_VAL,"",time()-1,USER_COOKIE_PATH,USER_COOKIE_DOMAIN);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="author" content="https://www.eye-r.com">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<title>会員ログイン</title>
	<link href="../style.css?t=<?= filemtime("../style.css");?>" rel="stylesheet">
	<link href="../index.css?t=<?= filemtime("../index.css");?>" rel="stylesheet">
</head>
<body>
	<h1>会員ログイン</h1>
	<div id="login_bg">
		<div id="login">
			<img src="../../img/logo_login.png?t=<?= filemtime("../../img/logo_login.png");?>">
			<h2><em>ログイン</em></h2>
			<p>
				当サービスをご利用いただくには<br>
				「LINEでログイン」してください。<br>
			</p>
			<a href="https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=<?= LINE_LO_CLIENT_ID?>&redirect_uri=<?= urlencode(LINE_LO_CALLBACK."?newflg=1")?>&state=<?= LINE_LO_TOKEN?>&scope=profile&bot_prompt=aggressive" class="lint_btn">
				<span><img src="../../img/line_132.png" alt="LINE"></span>
				<span>LINEでログイン</span>
			</a>
			<div class="boxtxt">
				<p><span>※</span>この画面をアプリ内ブラウザで開いている場合は、SafariやChromeなど別のブラウザで開いてください。</p>
				<p><span>※</span>Cookieをブロックしている場合はブロックを解除してください。</p>
			</div>
		</div>
	</div>
</body>
</html>