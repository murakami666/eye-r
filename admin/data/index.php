<?php
require_once("Config.php");

define("NOW",strtotime(date("Y-m-d 00:00:00",time())));

//日付一覧作成
$daytime_select_list = [];
for($i=0;$i<30;$i++){


    $date = strtotime("-".$i." day",NOW);

    $daytime_select_list[$date] = date("m月j日",$date)."(".$week_arr[date("w",$date)].")";
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="https://www.eye-r.com">
	<title>収集に成功したIRのログ</title>
    <link href="../style.css?t=<?= filemtime("../style.css");?>" rel="stylesheet">
    <link href="./style.css?t=<?= filemtime("./style.css");?>" rel="stylesheet">
    <script type="text/javascript" src="../script.js?t=<?= filemtime("../script.js")?>"></script>
    <script type="text/javascript" src="<?= BASE_URL?>common/js/jquery-2.2.4.min.js"></script>
	<script type="text/javascript" src="./script.js?t=<?= filemtime("./script.js")?>"></script>
</head>
<body>
    <div class="base">
<? require_once("../tmp_re_link.php")?>
    <h3>収集に成功したIRのログ</h3>

        <div class="disclo">
            <dl>
                <dt>開示日</dt>
                <dd>
                    <span class="select_bg"><select name="daytime_select">
                        <?= get_select_list($daytime_select_list,"")?>
                    </select></span>
                </dd>
            </dl>
        </div>

        <table border="0" cellspacing="1" cellpadding="0" class="base_table" id="data_table">
            <tr>
                <th>時刻</th>
                <th>会社名</th>
                <th>表題</th>
            </tr>
<? foreach((array)$data_list as $key => $value){ ?>
            <tr>
                <td><?= date("H:i",strtotime($value['data_date']." ".$value['data_time']))?></td>
                <td><?= $value['data_code']?></td>
                <td><?= $value['data_name']?></td>
                <td><a href="<?= $value['data_link']?>" target="_blank"><?= $value['data_text']?></a></td>
            </tr>
<? } ?>
        </table>
    </div>
</body>
</html>