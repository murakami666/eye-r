<?php
require_once("Config.php");
require_once("func/func_user_all_send_log.php");

$log_list = [];
$log_list = get_user_all_send_log_list([]);


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="https://www.eye-r.com">
	<title>一斉送信の送信ログ</title>
    <link href="../style.css?t=<?= filemtime("../style.css");?>" rel="stylesheet">
    <link href="./send.css?t=<?= filemtime("./send.css");?>" rel="stylesheet">
</head>
<body>
    <div class="base">

<? require_once("../tmp_re_link.php")?>

        <h3>一斉送信の送信ログ</h3>

        <p class="toptxt">
            【<span>説明</span>】直近10回分のログを残しています。<br>
        </p>
        
        <table border="0" cellspacing="1" cellpadding="0" class="base_table list_table">
            <tr>
                <th>送信内容</th>
                <th>送信会員数</th>
                <th>日時</th>
                <th>IP</th>
            </tr>
<? foreach($log_list as $key => $value){ ?>
            <tr>
                <td><?= nl2br($value['log_text'])?></td>
                <td><?= $value['log_user_num']?></td>
                <td><?= date("m月d日 H:i",strtotime($value['log_regist_date']))?></td>
                <td><?= $value['log_ip']?></td>
            </tr>
<? } ?>
        </table>

    </div>
</body>
</html>