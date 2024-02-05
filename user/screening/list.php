<?php
require_once("tmp.php");

require_once("Config.php");
require_once("Config_screaming.php");

require_once("../login_check.php");

$sc_master = [];
$sc_master = get_sc_master();

//-------------------------
//表示チェック
//-------------------------
$disp_flg = false;
$disp_mode = "";
switch($_GET['mode']){
	case "stop" :
		switch($_GET['type']){
			case "up" :
				if(in_array($_GET['day'],[2,3])){
					$disp_flg = true;
					$disp_mode = "us".$_GET['day'];
					$page_title = $_GET['day'].'日連続ストップ高の銘柄';
				}
				break;
			case "dw" :
				if(in_array($_GET['day'],[2,3])){
					$disp_flg = true;
					$disp_mode = "ds".$_GET['day'];
					$page_title = $_GET['day'].'日連続ストップ安の銘柄';
				}
				break;
		}
		break;
	case "dc" :
		switch($_GET['type']){
			case "up" :
				if(in_array($_GET['day'],[3,4,5])){
					if(get_user_dc_limit($user_data) >= $_GET['day']){
						$disp_flg = true;
						$disp_mode = "udc".$_GET['day'];
						$page_title = $_GET['day'].'日連続陽線の銘柄';
					}
				}
				break;
			case "dw" :
			if(in_array($_GET['day'],[3,4,5])){
					if(get_user_dc_limit($user_data) >= $_GET['day']){
						$disp_flg = true;
						$disp_mode = "ddc".$_GET['day'];
						$page_title = $_GET['day'].'日連続陰線の銘柄';
					}
				}
				break;
		}
		break;
}

if($disp_flg == false){
	header("Location: ".BASE_URL);
	exit();
}


if($sc_master['ms_now_flg'] == 0){
	//-------------------------
	//一覧取得
	//-------------------------
	$items = [];
	switch($disp_mode){
		case "us2" :
			$items['brand_sc_up_stop_2'] = 1;
			break;
		case "us3" :
			$items['brand_sc_up_stop_3'] = 1;
			break;
		case "ds2" :
			$items['brand_sc_dw_stop_2'] = 1;
			break;
		case "ds3" :
			$items['brand_sc_dw_stop_3'] = 1;
			break;
		case "udc3" :
			$items['brand_sc_pu_3'] = 1;
			break;
		case "udc4" :
			$items['brand_sc_pu_4'] = 1;
			break;
		case "udc5" :
			$items['brand_sc_pu_5'] = 1;
			break;
		case "ddc3" :
			$items['brand_sc_pd_3'] = 1;
			break;
		case "ddc4" :
			$items['brand_sc_pd_4'] = 1;
			break;
		case "ddc5" :
			$items['brand_sc_pd_5'] = 1;
			break;
	}

	$items['sort'] = "brand_code";
	$brand_list = [];
	$brand_list = get_brand_list($items);
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="author" content="https://www.eye-r.com">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<title><?= $page_title?></title>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="../../style.css?t=<?= filemtime("../style.css");?>" rel="stylesheet">
	<link href="../list.css?t=<?= filemtime("./list.css");?>" rel="stylesheet">
	<script type="text/javascript" src="<?= BASE_URL?>common/js/jquery-2.2.4.min.js"></script>
</head>
<body>
<? require_once("tmp_top.php");?>
	<main>
		<h2 class="block_title"><?= $page_title?></h2>
<? if($sc_master['ms_now_flg'] == 0){ ?>
<? if(count($brand_list) > 0){ ?>
		<div class="basic_list">
			<ul>
<? foreach($brand_list as $key=> $value){ ?>
				<li><a href="<?= get_brand_data_disp($value)?>?dc=y">[<?= $value['brand_code']?>] <?= $value['brand_name']?></a></li>
<? } ?>
			</ul>
		</div>
<? }else{ ?>
		<div class="zero">
		<?= $page_title?>の情報はありません
		</div>
<? } ?>
<? } ?>
<? if($sc_master['ms_now_flg'] == 1){ ?>
		<div class="no_data">
			<p>データ生成中です</p>
			<a href="../" class="no_data_bt">戻る</a>
		</div>
<? } ?>
	</main>
<? require_once("tmp_bottom.php");?>
</body>
</html>