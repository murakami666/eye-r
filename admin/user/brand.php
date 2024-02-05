<?php
require_once("Config.php");

$p_items = array();
$p_items = $_GET;

$disp_flg = false;

if(strlen($p_items['user']) > 0){

    $items = [];
    $items['user_id'] = $p_items['user'];
    $items['detail'] = 1;
    $user_data = [];
    $user_data = get_user_list($items);

    if($user_data['user_id']){
        $disp_flg = true;
    }

}

if($disp_flg == false){
    header("Location: ".BASE_URL."admin/user/list.php"); 
    exit();
}


//登録済み銘柄一覧
$brand_list = [];
$brand_list = get_user_brand_user_list($user_data['user_id']);


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="https://www.eye-r.com">
	<title>会員情報</title>
    <link href="../style.css?t=<?= filemtime("../style.css");?>" rel="stylesheet">
    <link href="./brand.css?t=<?= filemtime("./brand.css");?>" rel="stylesheet">
    <script type="text/javascript" src="<?= BASE_URL?>common/js/jquery-2.2.4.min.js"></script>
</head>
<body>
    <div class="base">

<? require_once("../tmp_re_link.php")?>

        <h3>会員情報</h3>

        <div class="prof_box">
            <div class="img"><img src="<?= $user_data['user_line_img']?>"></div>
			<div class="name">
                <span><?= $user_data['user_name']?></span><br>
                登録日時：<?= date("Y-m-d H:i:s",strtotime($user_data['user_regist_date']))?><br>
                最終ログイン：<?= date("Y-m-d H:i:s",strtotime($user_data['user_login_date']))?><br>
            </div>
        </div>

        <table border="0" cellspacing="1" cellpadding="0" class="base_table list_table">
            <tr>
                <th>コード</th>
                <th>会社名</th>
                <th>登録日時</th>
            </tr>
<? foreach($brand_list as $key => $value){ ?>
            <tr>
                <td><?= $value['brand_code']?></td>
                <td><?= $value['brand_name']?></td>
                <td><?= date("Y-m-d H:i:s",strtotime($value['ub_regist_date']))?></td>
            </tr>
<? } ?>
        </table>

    </div>
</body>
</html>