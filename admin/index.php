<?php
require_once("Config.php");
require_once("Config_screaming.php");

$p_items = array();
$p_items = $_POST;


//-------------------------
//一覧取得
//-------------------------
$items = [];
$items['limit'] = 30;
$user_list = [];
$user_list = get_user_list($items);

//-------------------------
//スクリーニング判定
//-------------------------
$sc_master = [];
$sc_master = get_sc_master();

if($sc_master['ms_now_flg'] == 0){

	$items = [];
	$items['limit'] = 1;
	$sc_date_list = [];
	$sc_date_list = get_sc_day($items);

}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="https://www.eye-r.com">
	<title>管理ページ</title>
    <link href="./style.css?t=<?= filemtime("./style.css");?>" rel="stylesheet">
    <link href="./index.css?t=<?= filemtime("./index.css");?>" rel="stylesheet">
    <script type="text/javascript" src="../script.js?t=<?= filemtime("../script.js")?>"></script>
</head>
<body>
    <div class="base">

        <div class="re_link">
            <div class="rl_img">
                <a href="<?= BASE_URL?>" target="_blank"><img src="/img/logo_login.png" alt="eye-R"></a>
            </div>
        </div>

        <div class="menu_box top_box">
            <div class="box">
                <h1 class="i_sq">新規会員の登録状況</h1>
                <dl>
<? foreach((array)$user_list as $key => $value){ ?>
                    <dt><?= date("m月d日　H時i分",strtotime($value['user_regist_date']))?>　<?= $value['user_name']?></dt>
<? } ?>
                </dl>
            </div>
        </div>


        <div class="menu_box">
            <div class="box">
                <h1 class="i_sq">お知らせ</h1>
                <div><a href="./info/">マイページの更新情報</a></div>
                <div><a href="./send/">会員に一斉送信</a></div>
                <div><a href="./log/send.php">一斉送信の送信ログ</a></div>
            </div>
            <div class="box">
                <h1>銘柄の管理</h1>
                <div><a href="./brand/regist.php">新規銘柄の登録</a></div>
                <div><a href="./brand/list.php">登録銘柄の一覧(編集・削除)</a></div>
                <div><a href="./log/brand.php">銘柄の更新ログ</a></div>
            </div>
            <div class="box">
                <h1>会員の管理</h1>
                <div><a href="./user/list.php">会員一覧</a></div>
            </div>
            <div class="box">
                <h1>その他の管理</h1>
				<div><a href="./data/">収集に成功したIRのログ</a></div>
				<br>
				 <h1>CSV書き出し<? if($sc_master['ms_now_flg'] == 0){ print '(収集日：'.date("n月j日",strtotime($sc_date_list[0]['sc_date'])).')';}?></h1>
<? if($sc_master['ms_now_flg'] == 1){ ?>
                <div>データ生成中です</div>
<? }else{ ?>
				<div class="up"><a href="./sc/?mode=dc&type=up&day=3">3日連続<span>陽線</span>の銘柄</a></div>
				<div class="up"><a href="./sc/?mode=dc&type=up&day=4">4日連続<span>陽線</span>の銘柄</a></div>
				<div class="up"><a href="./sc/?mode=dc&type=up&day=5">5日連続<span>陽線</span>の銘柄</a></div>
				<div class="dw"><a href="./sc/?mode=dc&type=dw&day=3">3日連続<span>陰線</span>の銘柄</a></div>
				<div class="dw"><a href="./sc/?mode=dc&type=dw&day=4">4日連続<span>陰線</span>の銘柄</a></div>
				<div class="dw"><a href="./sc/?mode=dc&type=dw&day=5">5日連続<span>陰線</span>の銘柄</a></div>
<? } ?>
				<br>
            </div>
        </div>
    </div>
</body>
</html>