<?php
require_once("Config.php");
require_once(BASE_ROOT."/common/func/func_info.php");

$p_items = array();
$p_items = data_trim($_POST);


//--------------------------------
//登録
//--------------------------------
if(strlen($p_items['regist'])>0){

    $p_items['info_date'] = $p_items['info_year']."-".$p_items['info_mon']."-".$p_items['info_day'];

    begin();

    regist_info($p_items);

    commit();
}

//--------------------------------
//編集
//--------------------------------
if(strlen($p_items['edit'])>0){

    $items = [];
    $items['info_id'] = $p_items['info_id'];
    $items['detail'] = 1;
    $info_data = [];
    $info_data = get_info_list($items);


}


//--------------------------------
//更新
//--------------------------------
if(strlen($p_items['update'])>0){

    $p_items['info_date'] = $p_items['info_year']."-".$p_items['info_mon']."-".$p_items['info_day'];

    begin();

    update_info($p_items['info_id'],$p_items);

    commit();
}


//--------------------------------
//削除
//--------------------------------
if(strlen($p_items['delete'])>0){

	begin();
	
	delete_info($p_items['info_id']);

	commit();

}



if(strlen($p_items['edit']) > 0){
    $date = strtotime($info_data['info_date']);
}else{
    $date = time();
}


$info_data['year'] = date("Y",$date);
$info_data['mon'] = date("n",$date);
$info_data['day'] = date("j",$date);


//-------------------------
//一覧取得
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
	<title>マイページの更新情報</title>
    <link href="../style.css?t=<?= filemtime("../style.css");?>" rel="stylesheet">
    <link href="./style.css?t=<?= filemtime("./style.css");?>" rel="stylesheet">
    <script type="text/javascript" src="../script.js?t=<?= filemtime("../script.js")?>"></script>
</head>
<body>
    <div class="base">
<? require_once("../tmp_re_link.php")?>
        <h3>マイページの更新情報</h3>

        <form action="./" method="post" id="edit_form">
            <table border="0" cellspacing="1" cellpadding="0" class="base_table edit_table">
                <tr>
                    <th>日付</th>
                    <td>
                        <input type="text" name="info_year" value="<?= $info_data['year']?>">年
                        <select name="info_mon"><?= get_select_list_2(1,12,$info_data['mon'])?></select>月
                        <select name="info_day"><?= get_select_list_2(1,31,$info_data['day'])?></select>日
                    </td>
                </tr>
                <tr>
                    <th>内容</th>
                    <td><textarea name="info_text" rows="10"><?= $info_data['info_text']?></textarea></td>
                </tr>
            </table>
<? if(strlen($p_items['edit']) > 0){ ?>
            <input name="update" type="submit" id="update" value="更新"  onClick="return updCheck(this)"/></td>
	        <input type="hidden" name="info_id" value="<?= $info_data['info_id']?>">
<? }else{ ?>
            <input name="regist" type="submit" id="regist" value="新規登録"  onClick="return regCheck(this)"/>
<? } ?>
        </form>

        <table border="0" cellspacing="1" cellpadding="0" class="base_table list_table">
            <tr>
                <th>日付</th>
                <th>内容</th>
                <th></th>
                <th></th>
            </tr>
<? foreach($info_list as $key => $value){ ?>
            <tr>
                <td><?= date("Y年m月d日",strtotime($value['info_date']))?></td>
                <td><?= mb_strimwidth(strip_tags(nl2br($value['info_text'])),0,60,'…')?></td>
                <td class="bt_s">
                    <form action="./" method="post">
                        <input type="submit" name="edit" value="編集">
                        <input type="hidden" name="info_id" value="<?= $value['info_id']?>">
                    </form>
                </td>
                <td class="bt_s">
                    <form action="./" method="post">
                        <input type="submit" name="delete" value="削除" onClick="return delCheck();">
                        <input type="hidden" name="info_id" value="<?= $value['info_id']?>">
                    </form>
                </td>
            </tr>
<? } ?>
        </table>

    </div>
</body>
</html>