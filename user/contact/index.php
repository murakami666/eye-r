<?php
require_once("tmp.php");
require_once("Config.php");

require_once("../login_check.php");


//-------------------------
//登録済み一覧取得
//-------------------------
$brand_list = [];
$brand_list = get_user_brand_user_list($user_data['user_id']);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="author" content="https://www.eye-r.com">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<title>お問い合わせ</title>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="../style.css?t=<?= filemtime("../style.css");?>" rel="stylesheet">
	<link href="./style.css?t=<?= filemtime("./style.css");?>" rel="stylesheet">
	<script type="text/javascript" src="<?= BASE_URL?>common/js/jquery-2.2.4.min.js"></script>
</head>
<body>
<? require_once("tmp_top.php");?>
	<main>
		<div id="contact">
			<h2 class="block_title">お問い合わせ</h2>
			<div class="form">
				<form method="post" action="./" id="check_form">
					<div class="cbox co_name">
						<h4>お名前<em class="hissu">必須</em></h4>
						<p><input type="text" name="co_name" id="co_name" class="w2" placeholder="例）山田　太郎"></p>
					</div>
					<div class="cbox co_mail">
						<h4>メールアドレス<em class="hissu">必須</em></h4>
						<p><input type="email" name="co_mail" id="co_mail" class="w2" placeholder="例）example@eye-r.com(半角英数)"></p>
					</div>
					<div class="cbox co_comment">
						<h4>内容<em class="hissu">必須</em></h4>
						<p><textarea name="co_comment" id="co_comment" rows="4"></textarea></p>
					</div>
					<div class="submit">
						<span class="error_text"></span>
						<input type="submit" value="内容を確認する" class="submit_bt">
						<input type="hidden" name="check" id="check" value="check">
					</div>
				</form>
			</div>
		</div>
	</main>
<? require_once("tmp_bottom.php");?>
</body>
</html>