<?php
require_once("Config.php");
require_once("func/func_sc_disp_log.php");

define("NOW",strtotime(date("Y-m-d 00:00:00",time())));

//--------------------------
//日付一覧作成
//--------------------------
$daytime_select_list = [];
for($i=0;$i<90;$i++){

    $date = strtotime("-".$i." day",NOW);

    $daytime_select_list[$date] = date("m月j日",$date)."(".$week_arr[date("w",$date)].")";
}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="https://www.eye-r.com">
	<title>銘柄詳細の表示ログ</title>
    <link href="../style.css?t=<?= filemtime("../style.css");?>" rel="stylesheet">
    <link href="./sc.css?t=<?= filemtime("./sc.css");?>" rel="stylesheet">
    <script type="text/javascript" src="<?= BASE_URL?>common/js/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="./sc.js?t=<?= filemtime("./sc.js")?>"></script>
</head>
<body>
    <div class="base">

<? require_once("../tmp_re_link.php")?>

        <h3>銘柄詳細の表示ログ</h3>

        <p class="toptxt">
            【<span>説明</span>】90日分のログを残しています。<br>
        </p>

        <div class="disclo">
            <dl>
                <dt>表示日</dt>
                <dd>
                    <span class="select_bg"><select name="daytime_select">
                        <?= get_select_list($daytime_select_list,"")?>
                    </select></span>
                </dd>
            </dl>
        </div>
        
        <table border="0" cellspacing="1" cellpadding="0" class="base_table list_table">
            <tr>
                <th>名前</th>
                <th>銘柄</th>
                <th>PV</th>
            </tr>
        </table>

    </div>
</body>
</html>