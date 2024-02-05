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
	<link href="./style.css?t=<?= filemtime("./style.css");?>" rel="stylesheet">
	<link href="./index.css?t=<?= filemtime("./index.css");?>" rel="stylesheet">
</head>
<body>
	<h1>会員ログイン</h1>
	<div id="login_bg">
		<div id="login">
			<img src="../img/logo_login.png?t=<?= filemtime("../img/logo_login.png");?>">
			<p style="color: #ff0000;">
				誠に申し訳ございません。<br>
				現在、新規会員登録は停止しております<br>
			</p>
		</div>
	</div>
</body>
</html>