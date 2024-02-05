<?php
require_once("tmp.php");
require_once("Config.php");

require_once("./login_check.php");


$disp_flg = false;
if(strlen($_GET['brand']) > 0){

	$items = [];
	$items['brand_id'] = $_GET['brand'];
	$items['detail'] = 1;
	$brand_data = [];
	$brand_data = get_brand_list($items);

	if($brand_data['brand_id'] > 0){
		$disp_flg = true;
	}
}

if($disp_flg == false){
	header("Location: ".BASE_URL); 
	exit();
}

$items = [];
$items['brand_id'] = $brand_data['brand_id'];
$items['join'] = ['brand'];
$data_list = [];
$data_list = get_data_list($items);

/******************************/
/*登録済*/
/******************************/
$brand_list = [];
$brand_list = get_user_brand_user_list($user_data['user_id']);

$user_add_flg = false;
foreach($brand_list as $key => $value){
	if($brand_data['brand_id'] == $value['brand_id']){
		$user_add_flg = true;
		break;
	}
}


/******************************/
/*スクリーミングデータ*/
/******************************/
$dc_disp_flg = false;
if($_GET['dc'] == "y" || $_GET['dcm'] == "y"){
	
	$dc_disp_flg = true;

	require_once("Config_screaming.php");
	
	$items = [];
	$items['limit'] = get_user_dc_day_limit($user_data);
	$sc_date_list = [];
	$sc_date_list = get_sc_day($items);

	$items = [];
	$items['sc_brand'] = $brand_data['brand_id'];
	$items['sc_date_s'] = $sc_date_list[(count($sc_date_list)-1)]['sc_date'];
	$items['sc_date_e'] = $sc_date_list[0]['sc_date'];

	$sc_list = [];
	$sc_list = get_sc_data_list($items);

	//ログ取得
	if($_GET['dc'] == "y"){
		if(get_user_dc_disp_log($user_data) == 1){
		
			require_once("func/func_sc_disp_log.php");
	
			begin();
	
			$items = [];
			$items['log_user'] = $user_data['user_id'];
			$items['log_brand'] = $brand_data['brand_id'];
			regist_sc_disp_log($items);
	
			commit();
	
		}
	}

}

function get_sc_text($data,$de){
	if(strlen($data) > 0){
		if (substr($data, -2) == ".0") {
			return number_format(floor($data));
		}else{
			return number_format($data,$de);
		}
	}else{
		return '--';
	}
}

function get_udclass($data,$add,$de){
	if(strlen($data) > 0){
		if($data > 0 && is_numeric($data)){
			$t = "";
			if($add == 1){
				$t = "+";
			}
			if (substr($data, -2) == ".0") {
				return '<span class="up">'.$t.number_format(floor($data)).'</span>';
			}else{
				return '<span class="up">'.$t.number_format($data,$de).'</span>';
			}

		}elseif($data < 0 && is_numeric($data)){
			if (substr($data, -2) == ".0") {
				return '<span class="down">'.number_format(floor($data)).'</span>';
			}else{
				return '<span class="down">'.number_format($data,$de).'</span>';
			}

		}else{
			return $data;
		}
	}else{
		return '--';
	}
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="author" content="https://www.eye-r.com">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<title>[<?= $brand_data['brand_code']?>]<?= $brand_data['brand_name']?></title>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="<?= BASE_URL?>user/style.css?t=<?= filemtime("../user/style.css");?>" rel="stylesheet">
	<link href="<?= BASE_URL?>user/page_brand.css?t=<?= filemtime(BASE_ROOT."user/page_brand.css");?>" rel="stylesheet">
	<script type="text/javascript" src="<?= BASE_URL?>common/js/jquery-2.2.4.min.js"></script>
	<script type="text/javascript" src="<?= BASE_URL?>user/page_brand.js?t=<?= filemtime(BASE_ROOT."user/page_brand.js")?>"></script>
</head>
<body>
<? require_once("tmp_top.php");?>
	<main>
		<div id="page">
			<h2 class="block_title"><em><?= $brand_data['brand_name']?></em><span>[ <?= $brand_data['brand_code']?> ]</span></h2>
<? if($user_add_flg == true){ ?>
			<p class="p_cancell">≫ <span class="del_btn" data-id="<?= $brand_data['brand_id']?>" data-name="<?= $brand_data['brand_name']?>">配信を解除する</span></p>
<? } ?>
<? if($dc_disp_flg == true){ ?>
			<h2 class="ss_title">時系列（日足）</h2>
			<div id="score">
				<dl>
					<dt>日付</dt>
					<dd>始値</dd>
					<dd>高値</dd>
					<dd>安値</dd>
					<dd>終値</dd>
					<dd>前日比</dd>
					<dd>出来高</dd>
					<dd>約定回数</dd>
				</dl>
<? foreach((array)$sc_list as $key => $value){ ?>
				<dl>
					<dt><?= date("n/j",strtotime($value['sc_date']))?></dt>
					<dd><?= get_sc_text($value['sc_op'],1)?></dd>
					<dd><?= get_sc_text($value['sc_hp'],1)?></dd>
					<dd><?= get_sc_text($value['sc_lp'],1)?></dd>
					<dd><?= get_sc_text($value['sc_ep'],1)?></dd>
					<dd><?= get_udclass($value['sc_b_rate'],1,1)?></dd>
					<dd><?= get_sc_text($value['sc_vo'],0)?></dd>
					<dd><?= get_sc_text($value['sc_ne'],0)?></dd>
				</dl>
<? } ?>
			</div>
			<h2 class="ss_title">移動平均乖離率</h2>
			<div id="per">
				<p>25日<br><?= get_udclass($sc_list[0]['sc_dr_25'],0,2)?>%</p>
				<p>75日<br><?= get_udclass($sc_list[0]['sc_dr_75'],0,2)?>%</p>
				<p>200日<br><?= get_udclass($sc_list[0]['sc_dr_200'],0,2)?>%</p>
			</div>
<? } ?>
			<div id="info_list" class="list">
				<div class="base_table">
					<dl>
						<dt>日時</dt>
						<dt>表題</dt>
					</dl>
<? if(count($data_list) > 0){ ?>
<? foreach((array)$data_list as $key => $value){ ?>
					<dl>
						<dd><?= date("n月j日",strtotime($value['data_date']))?><br class="pc_none"><?= date("H:i",strtotime($value['data_time']))?></dd>
						<dd><a href="<?= $value['data_link']?>" target="_blank"><?= $value['data_text']?></a></dd>
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