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
	<title>会員ページ</title>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="../style.css?t=<?= filemtime("../style.css");?>" rel="stylesheet">
	<link href="./style.css?t=<?= filemtime("./style.css");?>" rel="stylesheet">
	<script type="text/javascript" src="<?= BASE_URL?>common/js/jquery-2.2.4.min.js"></script>
	<script type="text/javascript" src="./script.js?t=<?= filemtime("./script.js")?>"></script>
</head>
<body>
<? require_once("tmp_top.php");?>
	<main>
		<div id="brand">
			<h2 class="block_title" style="margin-bottom: 15px;">配信される銘柄の変更</h2>
			<div class="search_box">
				<h3><span class="material-icons">search</span>銘柄検索</h3>
				<div class="input">
					<input type="text" name="search_word" id="search_word" value="" placeholder="銘柄コードか会社名で検索"><br>
					<span class="app">《現在の契約は<?= get_user_brand_limit($user_data)?>銘柄まで登録可能》</span>
				</div>
				<div class="resu">
					<div id="search_word_list">
						<div class="base_table resu_list">
							<dl>
								<dt>会社名</dt>
								<dt></dt>
							</dl>
						</div>
					</div>
				</div>
			</div>
			<div id="info_list" class="brand">
				<div class="base_table brand_list">
					<dl>
						<dt>会社名</dt>
						<dt></dt>
					</dl>
<? foreach((array)$brand_list as $key => $value){
	print '<dl><dd><a href="'.get_brand_data_disp($value).'?dc=y">['.$value['brand_code'].']'.$value['brand_name'].'</a></dd><dd><span class="del_btn" data-id="'.$value['brand_id'].'" data-name="'.$value['brand_name'].'">[解除]</span></dd></dl>'."\n";
} ?>
				</div>
				<div class="brand_list_zoro zero">
					登録はありません<br>
					<span>コードもしくは会社名で検索して配信を希望される銘柄を登録してください</span>
				</div>
			</div>
		</div>
	</main>
<? require_once("tmp_bottom.php");?>
</body>
</html>