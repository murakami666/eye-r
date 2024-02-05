<?php
require_once("Config.php");
require_once("Config_screaming.php");

$sc_master = [];
$sc_master = get_sc_master();

$items = [];
$items['limit'] = 1;
$sc_date_list = [];
$sc_date_list = get_sc_day($items);

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
				return $t.number_format(floor($data));
			}else{
				return $t.number_format($data,$de);
			}
			
		}elseif($data < 0 && is_numeric($data)){
			if (substr($data, -2) == ".0") {
				return number_format(floor($data));
			}else{
				return number_format($data,$de);
			}
			
		}else{
			return $data;
		}
	}else{
		return '--';
	}
}

//-------------------------
//表示チェック
//-------------------------
$disp_flg = false;
$disp_mode = "";
switch($_GET['mode']){
	case "dc" :
		switch($_GET['type']){
			case "up" :
				$disp_flg = true;
				$disp_mode = "udc".$_GET['day'];
				$page_title = $_GET['day'].'日連続陽線の銘柄';
				break;
			case "dw" :
				$disp_flg = true;
				$disp_mode = "ddc".$_GET['day'];
				$page_title = $_GET['day'].'日連続陰線の銘柄';
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


	//----------------------------
	//書き出し
	//----------------------------
	$csvData = '';
	$csvData .= '銘柄コード,銘柄名,本日の終値,前日比,約定回数'."\n";

	foreach($brand_list as $key => $value){

		//データ取得
		$items = [];
		$items['sc_brand'] = $value['brand_id'];
		$items['sc_date'] = $sc_date_list[0]['sc_date'];
		$items['limit'] = 1;
		$sc_list = [];
		$sc_list = get_sc_data_list($items);

		$csvData .= $value['brand_code'].',';
		$csvData .= $value['brand_name'].',';
		$csvData .= '"'.get_sc_text($sc_list[0]['sc_ep'],1).'",';

		$csvData .= '"'.get_udclass($sc_list[0]['sc_b_rate'],1,1);

		//前日比計算
		$r = $sc_list[0]['sc_b_rate'];
		$t = $sc_list[0]['sc_ep'];
		$y = $sc_list[0]['sc_ep'] - $sc_list[0]['sc_b_rate'];

		$p = sprintf('%.2f',($r / $y)*100);

		if($sc_list[0]['sc_b_rate'] > 0){
			$p = "+".$p;
		}

		$csvData .= '('.$p.'%)",';
		$csvData .= '"'.get_sc_text($sc_list[0]['sc_ne'],0).'"'."\n";

	}

	$filename = $page_title.".csv";

	$file = fopen($filename, 'w');
	fwrite($file, $csvData);
	fclose($file);

	// ダウンロードヘッダーを設定
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="' . $filename . '"');

	// 生成したCSVファイルを出力
	readfile($filename);

	// 一時ファイルを削除
	unlink($filename);

}


?>