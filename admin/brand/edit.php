<?php
require_once("Config.php");

require_once("func/func_brand_edit_log.php");

$p_items = array();
$p_items = $_POST;

$p_items = data_trim_in($p_items);


$items = [];
$items['brand_id'] = $p_items['brand_id'];
$items['detail'] = 1;
$brand_data = [];
$brand_data = get_brand_list($items);


//--------------------------------
//登録
//--------------------------------
if(strlen($p_items['update'])>0){

	begin();
	
	update_brand($brand_data['brand_id'],$p_items);

    $items = [];
    $items['log_type'] = 2;
    $items['log_brand_id'] = $brand_data['brand_id'];
    $items['log_b_brand_code'] = $brand_data['brand_code'];
    $items['log_b_brand_name'] = $brand_data['brand_name'];
    $items['log_a_brand_code'] = $p_items['brand_code'];
    $items['log_a_brand_name'] = $p_items['brand_name'];
    regist_brand_edit_log($items);

	commit();

}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="https://www.eye-r.com">
	<title>銘柄管理　編集</title>
    <link href="../style.css?t=<?= filemtime("../style.css");?>" rel="stylesheet">
    <link href="./edit.css?t=<?= filemtime("./edit.css");?>" rel="stylesheet">
    <script type="text/javascript" src="../script.js?t=<?= filemtime("../script.js")?>"></script>
</head>
<body>
    <div class="base">
<? require_once("../tmp_re_link.php")?>
        <h3>銘柄管理　編集</h3>
<? if(strlen($p_items['update'])>0){ ?>
        <p class="resu_text">
            登録しました<br>
            <br>
            <br>
            [<a href="./list.php">一覧へ</a>]
        </p>
<? }else{ ?>
        <form action="./edit.php" method="post" id="edit_form">
            <table border="0" cellspacing="1" cellpadding="0" class="base_table" id="edit_table">
                <tr>
                    <th>コード</th>
                    <td><input type="text" name="brand_code"  value="<?= $brand_data['brand_code']?>" maxlength="4"/></td>
                </tr>
                <tr>
                    <th>会社名</th>
                    <td><input type="text" name="brand_name"  value="<?= $brand_data['brand_name']?>"/></td>
                </tr>
            </table>
            <input name="update" type="submit" id="update" value="更新"  onClick="return updCheck(this)"/></td>
	        <input type="hidden" name="brand_id" value="<?= $brand_data['brand_id']?>">
        </form>
<? } ?>

    </div>
</body>
</html>