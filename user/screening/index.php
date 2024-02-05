<?php
require_once("tmp.php");
require_once("Config.php");

require_once("../login_check.php");


//連続日足配列
$dc_list = [3,4,5];

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="author" content="https://www.eye-r.com">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<title>銘柄スクリーニング</title>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="../style.css?t=<?= filemtime("../style.css");?>" rel="stylesheet">
	<link href="./style.css?t=<?= filemtime("./style.css");?>" rel="stylesheet">
	<script type="text/javascript" src="<?= BASE_URL?>common/js/jquery-2.2.4.min.js"></script>
</head>
<body>
<? require_once("tmp_top.php");?>
	<main>
		<h2 class="block_title">銘柄スクリーニング</h2>
		<div class="basic_list" id="list_a">
			<h3 class="ss_title">ストップ銘柄</h3>
			<ul>
				<li class="u"><a href="./us2/">2日連続ストップ高の銘柄</a></li>
				<li class="u"><a href="./us3/">3日連続ストップ高の銘柄</a></li>
				<li class="d"><a href="./ds2/">2日連続ストップ安の銘柄</a></li>
				<li class="d"><a href="./ds3/">3日連続ストップ安の銘柄</a></li>
			</ul>
		</div>
		<div class="basic_list" id="list_b">
			<h3 class="ss_title">連続日足銘柄</h3>
			<ul>
<? foreach($dc_list as $key => $value){ ?>
<? if(get_user_dc_limit($user_data) >= $value){ ?>
				<li class="u"><a href="./udc<?= $value?>/"><?= $value?>日連続陽線の銘柄</a></li>
<? } ?>
<? } ?>
<? foreach($dc_list as $key => $value){ ?>
<? if(get_user_dc_limit($user_data) >= $value){ ?>
				<li class="d"><a href="./ddc<?= $value?>/"><?= $value?>日連続陰線の銘柄</a></li>
<? } ?>
<? } ?>
			</ul>
		</div>
	</main>
<? require_once("tmp_bottom.php");?>
</body>
</html>