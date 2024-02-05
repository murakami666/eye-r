<?php
require_once("tmp.php");
require_once("Config.php");
require_once(BASE_ROOT."/common/func/func_info.php");

require_once("./login_check.php");


define("NOW",strtotime(date("Y-m-d H:i:00",time())));


//-------------------------
//アクセストークンの有効性確認
//-------------------------
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.line.me/oauth2/v2.1/verify?access_token='.$user_data['user_line_accesstoken']);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
curl_close($ch);

if($httpcode != "200"){

	setcookie(USER_COKKIE_VALUNE_ID,"",time()-1,USER_COOKIE_PATH,USER_COOKIE_DOMAIN);
    setcookie(USER_COKKIE_VALUNE_VAL,"",time()-1,USER_COOKIE_PATH,USER_COOKIE_DOMAIN);
    header("Location: ".BASE_URL."user/"); 
    exit();

}


//-------------------------
//友達登録
//-------------------------
$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $user_data['user_line_accesstoken']));
curl_setopt($ch, CURLOPT_URL, 'https://api.line.me/friendship/v1/status');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$flg = json_decode($response, true);

$line_friend_flg = false;
if($flg['friendFlag'] == true){
	$line_friend_flg = true;
}



//-------------------------
//登録済み一覧取得
//-------------------------
$brand_list = [];
$brand_list = get_user_brand_user_list($user_data['user_id']);

$daytime_select_list = [];
if(count($brand_list) > 0){

	//-------------------------
	//データ一覧取得
	//-------------------------
	$items = [];
	$items['join'] = ['brand'];
	$items['data_date_min'] = date("Y-m-d",strtotime("-30 day",NOW));

	foreach((array)$brand_list as $key => $value){
		$items['brand_id_arr'][] = $value['brand_id'];
	}
	
	$data_list = [];
	$data_list = get_data_list($items);

	foreach((array)$data_list as $key => $value){
		$daytime_select_list[] = strtotime($value['data_date']);
	}
}


//重複削除
$daytime_select_list = array_unique($daytime_select_list);

if(count($daytime_select_list) > 0){

	$list = [];
	$list['all'] = "全て表示";
	foreach((array)$daytime_select_list as $key => $value){
		$list[$value] = date("m月j日",$value);
	}

	$daytime_select_text =  get_select_list($list,"");
	$daytime_cnt_text = "";

}else{
	$daytime_select_text = '<option value="none">--</option>';
	$daytime_cnt_text = '<div class="zero">開示された情報はありません</div>';
}


//-------------------------
//お知らせ一覧取得
//-------------------------
$items = [];
$info_list = [];
$info_list = get_info_list($items);


?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="author" content="https://www.eye-r.com">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<title>マイページ｜eye-R</title>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="./style.css?t=<?= filemtime("./style.css");?>" rel="stylesheet">
	<link href="./my.css?t=<?= filemtime("./my.css");?>" rel="stylesheet">
	<script type="text/javascript" src="<?= BASE_URL?>common/js/jquery-2.2.4.min.js"></script>
	<script type="text/javascript" src="./my.js?t=<?= filemtime("./my.js")?>"></script>
</head>
<body>
<div id="bg">
	<header>
		<div id="header_in">
			<div id="header_logo">
				<div id="header_logo">
					<a href="" id="home"><img src="../img/header_logo.png?t=<?= filemtime("../img/header_logo.png");?>"></a>
					<span class="hbt"><em>IRを即時通知</em></span>
				</div>
			</div>
			<div id="prof">
				<p class="img"><img src="<?= $user_data['user_line_img']?>"></p>
				<p class="name"><?= $user_data['user_name']?></p>
			</div>
		</div>
	</header>
	<main>
<? if(count($info_list) > 0){ ?>
		<div id="news">
<? foreach($info_list as $key => $value){ ?>
			<div class="nbox">
				<p class="date"><?= date("Y年m月d日",strtotime($value['info_date']))?></p>
				<div class="txt">
					<?= nl2br($value['info_text'])?>
				</div>
			</div>
<? } ?>
		</div>
<? } ?>
<? if($line_friend_flg == false){ ?>
		<div class="line_noti">
			<h3><span class="material-icons">warning</span><em>LINE通知の設定</em></h3>
			<p>LINEを友達登録いたただくと、登録した銘柄の開示情報通知を受け取ることが可能です。<br class="pc_none">「<a href="<?= LINE_ME_FREND_URL?>">こちら</a>」より友達登録をお願いします</p>
		</div>
<? } ?>
		<div class="disclo">
			<dl>
				<dt>開示日</dt>
				<dd>
					<span class="select_bg"><select name="daytime_select">
						<?= $daytime_select_text?>
					</select></span>
				</dd>
			</dl>
		</div>
		<div id="mypage_info">
			<h2>新着ＩR</h2>
			<?= $daytime_cnt_text?>
		</div>
	</main>
<? require_once("tmp_bottom.php");?>
</body>
</html>