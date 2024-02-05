<?php
require_once("Config.php");

require_once("func/func_brand_edit_log.php");

$p_items = array();
$p_items = $_POST;

//--------------------------------
//削除
//--------------------------------
if(strlen($p_items['delete'])>0){


    $items = [];
    $items['brand_id'] = $p_items['brand_id'];
    $items['detail'] = 1;
    $brand_data = [];
    $brand_data = get_brand_list($items);


	begin();
	
	delete_brand($p_items['brand_id']);

    $items = [];
    $items['log_type'] = 3;
    $items['log_brand_id'] = $brand_data['brand_id'];
    $items['log_b_brand_code'] = $brand_data['brand_code'];
    $items['log_b_brand_name'] = $brand_data['brand_name'];
    regist_brand_edit_log($items);

	commit();

}


//-------------------------
//一覧取得
//-------------------------
$items = [];
$items['sort'] = 'brand_code';
$brand_list = [];
$brand_list = get_brand_list($items);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="https://www.eye-r.com">
	<title>銘柄管理　一覧</title>
    <link href="../style.css?t=<?= filemtime("../style.css");?>" rel="stylesheet">
    <link href="./list.css?t=<?= filemtime("./list.css");?>" rel="stylesheet">
    <script type="text/javascript" src="../script.js?t=<?= filemtime("../script.js")?>"></script>
</head>
<body>
    <div class="base">
<? require_once("../tmp_re_link.php")?>
        <h3>銘柄管理　一覧</h3>

        <table border="0" cellspacing="1" cellpadding="0" class="base_table list_table">
            <tr>
                <th>コード</th>
                <th>会社名</th>
                <th></th>
                <th></th>
            </tr>
<? foreach($brand_list as $key => $value){ ?>
            <tr>
                <td><?= $value['brand_code']?></td>
                <td><?= $value['brand_name']?></td>
                <td class="bt_s">
                    <form action="./edit.php" method="post">
                        <input type="submit" name="edit" value="編集">
                        <input type="hidden" name="brand_id" value="<?= $value['brand_id']?>">
                    </form>
                </td>
                <td class="bt_s">
                    <form action="./list.php" method="post">
                        <input type="submit" name="delete" value="削除" onClick="return delCheck();">
                        <input type="hidden" name="brand_id" value="<?= $value['brand_id']?>">
                    </form>
                </td>
            </tr>
<? } ?>
        </table>

    </div>
</body>
</html>