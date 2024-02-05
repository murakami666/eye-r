<?php
require_once("tmp.php");
require_once("Config.php");

require_once("./login_check.php");





if(strlen($_GET['y']) > 0 && strlen($_GET['m']) > 0 && strlen($_GET['d'])){

	define("NOW",strtotime($_GET['y']."-".$_GET['m']."-".$_GET['d']." 00:00:00"));

	//-------------------------
	//登録済み一覧取得
	//-------------------------
	$brand_list = [];
	$brand_list = get_user_brand_user_list($user_data['user_id']);

	$daytime_select_list = [];
	if(count($brand_list) > 0){
		
		$items = [];
		$items['data_date'] = date("Y-m-d",NOW);
		$items['join'] = ['brand'];

		foreach((array)$brand_list as $key => $value){
			$items['brand_id_arr'][] = $value['brand_id'];
		}
		
		$data_list = [];
		$data_list = get_data_list($items);

	}



}else{
	header("Location: ".BASE_URL);
	exit();
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="author" content="https://www.eye-r.com">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<title><?= date("Y年m月d日",NOW)?>に開示された情報</title>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="<?= BASE_URL?>user/style.css?t=<?= filemtime(BASE_ROOT."user/style.css");?>" rel="stylesheet">
	<link href="<?= BASE_URL?>user/page.css?t=<?= filemtime(BASE_ROOT."user/page.css");?>" rel="stylesheet">
</head>
<body>
<? require_once("tmp_top.php");?>
	<main>
		<div id="page">
			<h2 class="s_title"><?= date("Y年m月d日",NOW)?>に開示された情報</h2>
			<div id="info_list2" class="list">
				<div class="base_table">
					<dl>
						<dt>時間</dt>
						<dt>会社名</dt>
						<dt>表題</dt>
					</dl>
<? if(count($data_list) > 0){ ?>
<? foreach((array)$data_list as $key => $value){ ?>
					<dl>
						<dd><?= date("H:i",strtotime($value['data_date']." ".$value['data_time']))?></dd>
						<dd><a href="<?= get_brand_data_disp($value)?>" target="_blank">[<?= $value['data_code']?>]<?= $value['data_name']?></a></dd>
						<dd><p><a href="<?= $value['data_link']?>" target="_blank"><?= $value['data_text']?></a></p></dd>
					</dl>
<? } ?>
<? }else{ ?>
					<div class="zero">
						開示された情報はありません
					</div>
<? } ?>
				</div>
			</div>
		</div>
	</main>
<? require_once("tmp_bottom.php");?>
</body>
</html>