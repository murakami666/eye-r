<?php
require_once("Config.php");

require_once("func/func_brand_edit_log.php");

$p_items = array();
$p_items = $_POST;

$p_items = data_trim_in($p_items);

//--------------------------------
//登録
//--------------------------------
if(strlen($p_items['regist'])>0){

	begin();
	
	$brand_id = regist_brand($p_items);

    $items = [];
    $items['log_type'] = 1;
    $items['log_brand_id'] = $brand_id;
    $items['log_b_brand_code'] = $p_items['brand_code'];
    $items['log_b_brand_name'] = $p_items['brand_name'];
    regist_brand_edit_log($items);

	commit();

}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="https://www.eye-r.com">
	<title>新規銘柄の登録</title>
    <link href="../style.css?t=<?= filemtime("../style.css");?>" rel="stylesheet">
    <link href="./edit.css?t=<?= filemtime("./edit.css");?>" rel="stylesheet">
    <script type="text/javascript" src="../script.js?t=<?= filemtime("../script.js")?>"></script>
</head>
<body>
    <div class="base">
<? require_once("../tmp_re_link.php")?>
        <h3>新規銘柄の登録</h3>
<? if(strlen($p_items['regist'])>0){ ?>
        <p class="resu_text">
            登録しました<br>
            <br>
            <br>
            [<a href="./regist.php">続けて登録</a>]　[<a href="./list.php">一覧へ</a>]
        </p>
<? }else{ ?>
        <form action="./regist.php" method="post" id="edit_form">
            <table border="0" cellspacing="1" cellpadding="0" class="base_table" id="edit_table">
                <tr>
                    <th>コード</th>
                    <td><input type="text" name="brand_code"  value="" maxlength="4"/></td>
                </tr>
                <tr>
                    <th>会社名</th>
                    <td><input type="text" name="brand_name"  value=""/></td>
                </tr>
            </table>
	        <input name="regist" type="submit" id="regist" value="新規登録"  onClick="return regCheck(this)"/>
        </form>
<? } ?>

    </div>
</body>
</html>