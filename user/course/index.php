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
	<title>契約コースの変更</title>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="../style.css?t=<?= filemtime("../style.css");?>" rel="stylesheet">
	<link href="./style.css?t=<?= filemtime("./style.css");?>" rel="stylesheet">
	<script type="text/javascript" src="<?= BASE_URL?>common/js/jquery-2.2.4.min.js"></script>
</head>
<body>
<? require_once("tmp_top.php");?>
	<main>
		<div id="course">
			<h2 class="block_title">契約コースの変更</h2>
			<form action="./" method="post" class="pub_box">
				<ul>
					<li><div for="pub_1" class="pub_label check"><div><span class="check_m"></span></div><div class="course1"><span>あいうえおコース</span></div><div><span>月額</span><em>100,000<font>円</font></em><p>あいうえおかきくけこ</p></div></div></li>
					<li><label for="pub_1" class="pub_label"><div><input type="radio" name="pub" value="4" id="pub_4"><span class="dummy"></span></div><div class="course1"><span>あいうえおコース</span></div><div><span>月額</span><em>60,000<font>円</font></em><p>あいうえおかきくけこ</p></div></label></li>
					<li><label for="pub_2" class="pub_label"><div><input type="radio" name="pub" value="2" id="pub_2"><span class="dummy"></span></div><div class="course2"><span>あいうえおコース</span></div><div><span>月額</span><em>20,000<font>円</font></em><p>あいうえおかきくけこ</p></div></label></li>
					<li><label for="pub_3" class="pub_label"><div><input type="radio" name="pub" value="6" id="pub_6"><span class="dummy"></span></div><div class="course3"><span>あいうえおコース</span></div><div></div></label></li>
				</ul>
				<div class="bt_bg">
					<button type="submit" name="check" value="check" class="bt">掲載コース変更を申し込む</button>
				</div>
				<p class="out">[ <a href="">退会する</a> ]</p>
			</form>
		</div>
	</main>
<? require_once("tmp_bottom.php");?>
</body>
</html>