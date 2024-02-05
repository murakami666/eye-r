<?php
require_once("Config.php");
require_once("func/func_brand_edit_log.php");

$log_list = [];
$log_list = get_brand_edit_log_list([]);

$log_type_arr = [];
$log_type_arr[1] = "登録";
$log_type_arr[2] = "更新";
$log_type_arr[3] = "削除";

foreach($log_list as $key => $value){

    $text = "";

    $text .= $value['log_b_brand_name']."(".$value['log_b_brand_code'].")";


    switch($value['log_type']){
        case 2 :
            $text .= "　→　".$value['log_a_brand_name']."(".$value['log_a_brand_code'].")";
            break;
    }

    $log_list[$key]['text'] = $text;

}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="https://www.eye-r.com">
	<title>銘柄の更新ログ</title>
    <link href="../style.css?t=<?= filemtime("../style.css");?>" rel="stylesheet">
    <link href="./brand.css?t=<?= filemtime("./brand.css");?>" rel="stylesheet">
</head>
<body>
    <div class="base">

<? require_once("../tmp_re_link.php")?>

        <h3>銘柄の更新ログ</h3>

        <p class="toptxt">
            【<span>説明</span>】90日分のログを残しています。<br>
        </p>
        
        <table border="0" cellspacing="1" cellpadding="0" class="base_table list_table">
            <tr>
                <th>行動</th>
                <th>変更内容</th>
                <th>日時</th>
                <th>IP</th>
            </tr>
<? foreach($log_list as $key => $value){ ?>
            <tr>
                <td><?= $log_type_arr[$value['log_type']]?></td>
                <td><?= $value['text']?></td>
                <td><?= date("m月d日 H:i",strtotime($value['log_regist_date']))?></td>
                <td><?= $value['log_ip']?></td>
            </tr>
<? } ?>
        </table>

    </div>
</body>
</html>