<?php
require_once("Config.php");

$p_items = array();
$p_items = $_POST;

//--------------------------------
//種別一覧作成
//--------------------------------
$user_type_select_list = [];
foreach((array)$user_type_arr as $key => $value){
    $user_type_select_list[$key] = $value['name'];
}

//--------------------------------
//改ページ
//--------------------------------
//表示件数
$limit = 50;

$page_limit_back = 4;
$page_limit_next = 5;

if($p_items['page'] && is_numeric($p_items['page'])){
	$page = (int)$p_items['page'];
}else{
	$page = 1;
}


//--------------------------------
//並び順設定
//--------------------------------
$user_sort_arr = [];
$user_sort_arr[1] = '登録順(昇順)';
$user_sort_arr[2] = '登録順(降順)';
$user_sort_arr[3] = '最終ログイン順(昇順)';
$user_sort_arr[4] = '最終ログイン順(降順)';

switch($p_items['sort']){
    case 3 :
        $sort = 3;
        $sort_db = "user_login_date_desc";
        break;
    case 4 :
        $sort = 4;
        $sort_db = "user_login_date";
        break;
    case 1 :
        $sort = 1;
        $sort_db = "user_regist_date_desc";
        break;
    default :
        $sort = 2;
        $sort_db = "user_regist_date";
        break;
}




//--------------------------------
//削除
//--------------------------------
if(strlen($p_items['delete'])>0){
    
	begin();
	
	delete_user($p_items['user_id']);

	commit();
    
}


//-------------------------
//一覧取得
//-------------------------
$items = [];
$items['mode'] = "cnt";
$all_cnt = get_user_list($items);

//---------------------------
//一覧取得
//---------------------------
$items['mode'] = "";
$items['page'] = $page;
$items['limit'] = $limit;
$items['sort'] = $sort_db;
$user_list = [];
$user_list = get_user_list($items);

foreach($user_list as $key => $value){

    //登録済み銘柄
    $user_list[$key]['brand'] = count(get_user_brand_user_list($value['user_id']));

}

//---------------------------
//改ページ作成
//---------------------------
$page_list = get_page_list($page,$all_cnt,$limit,$page_limit_back,$page_limit_next);


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="https://www.eye-r.com">
	<title>会員一覧</title>
    <link href="../style.css?t=<?= filemtime("../style.css");?>" rel="stylesheet">
    <link href="./list.css?t=<?= filemtime("./list.css");?>" rel="stylesheet">
    <script type="text/javascript" src="../script.js?t=<?= filemtime("../script.js")?>"></script>
    <script type="text/javascript" src="<?= BASE_URL?>common/js/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="./list.js?t=<?= filemtime("./list.js")?>"></script>
</head>
<body>
    <div class="base">

<? require_once("../tmp_re_link.php")?>

        <h3>会員一覧</h3>

        <form method="post" action="./list.php" id="sort_from">
            <div class="disclo">
                <dl>
                    <dt>表示順</dt>
                    <dd>
                        <span class="select_bg"><select name="sort">
                                <?= get_select_list($user_sort_arr,$sort)?>
                        </select></span>
                    </dd>
                </dl>
		    </div>
        </form>

        <table border="0" cellspacing="1" cellpadding="0" class="base_table list_table">
            <tr>
                <th>名前</th>
                <th>登録日</th>
                <th>最終ログイン</th>
                <th>登録銘柄</th>
                <th>種別</th>
                <th></th>
            </tr>
<? foreach($user_list as $key => $value){ ?>
            <tr>
                <td><?= $value['user_name']?></td>
                <td><?= date("Y-m-d",strtotime($value['user_regist_date']))?></td>
                <td><?= date("Y-m-d H:i:s",strtotime($value['user_login_date']))?></td>
                <td><a href="./brand.php?user=<?= $value['user_id']?>" class="cnt_text"><?= $value['brand']?></a></td>
                <td>
                    <select name="user_type" data-id="<?= $value['user_id']?>" data-name="<?= $value['user_name']?>" class="user_type_select" id="user_type_select_<?= $value['user_id']?>">
<? foreach((array)$user_type_select_list as $s_key => $s_value){ ?>
                        <option value="<?= $s_key?>" data-name="<?= $s_value?>" <? if($s_key == $value['user_type']){print ' selected';}?>><?= $s_value?></option>
<? } ?>
                    </select>
                </td>
                <td>
                    <form action="./list.php" method="post" class="del_form">
                        <input type="submit" name="delete" value="会員削除" data-text="<?= $value['user_name']?>">
                        <input type="hidden" name="user_id" value="<?= $value['user_id']?>">
                        <input type="hidden" name="sort" value="<?= $sort?>">
                    </form>
                </td>
            </tr>
<? } ?>
        </table>


        <div id="page">
            <?= $page_list?>
        </div>

        <form method="post" action="./list.php" name="page_form">
            <input type="hidden" name="page">
            <input type="hidden" name="sort" value="<?= $sort?>">
        </form>

    </div>
</body>
</html>