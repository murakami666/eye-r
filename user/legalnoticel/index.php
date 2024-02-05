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
	<title>特定商取引法の表記</title>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="../style.css?t=<?= filemtime("../style.css");?>" rel="stylesheet">
	<link href="./style.css?t=<?= filemtime("./style.css");?>" rel="stylesheet">
	<script type="text/javascript" src="<?= BASE_URL?>common/js/jquery-2.2.4.min.js"></script>
</head>
<body>
<? require_once("tmp_top.php");?>
	<main>
		<div id="legalnoticel">
			<h2 class="block_title">特定商取引法の表記</h2>
			<dl>
				<dt>販売業社</dt>
				<dd>LIBRUS株式会社</dd>
			</dl>
			<dl>
				<dt>販売責任者</dt>
				<dd>鎌田光一郎</dd>
			</dl>
			<dl>
				<dt>所在地</dt>
				<dd>東京都港区新橋6丁目13-12 VORT新橋Ⅱ 4F</dd>
			</dl>
			<dl>
				<dt>メールアドレス</dt>
				<dd></dd>
			</dl>
			<dl>
				<dt>販売代金</dt>
				<dd></dd>
			</dl>
			<dl>
				<dt>お支払い方法</dt>
				<dd>クレジット決済</dd>
			</dl>
			<dl>
				<dt>キャンセル</dt>
				<dd>商品の特性上、キャンセルは一切お受けできません。</dd>
			</dl>
			<dl>
				<dt>保証</dt>
				<dd></dd>
			</dl>
			<dl>
				<dt>返品・交換について</dt>
				<dd>商品の特性上、キャンセルは一切お受けできません。</dd>
			</dl>
			<dl>
				<dt>サービスの推奨環境</dt>
				<dd></dd>
			</dl>
		</div>
	</main>
<? require_once("tmp_bottom.php");?>
</body>
</html>