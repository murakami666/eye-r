<?php
//デバッグフラグ(1-test)
$G_is_debug = "0";

define('BASE_URL','https://www.eye-r.com/');
define('BASE_ROOT','/var/www/html/eye-r.com/');

//DBに接続
define('DSN_DB','eye-r');
define('DSN_HOST','localhost');
define('DSN_USER','eye-r');
define('DSN_PASS','Z8K7C-R3S]HbDVl-');
define('DSN','mysql:host='.DSN_HOST.'; dbname='.DSN_DB.'; options=\'--client_encoding=UTF8\'');

require_once(BASE_ROOT."/common/func_db.php");
require_once(BASE_ROOT."/common/func_tool.php");
require_once(BASE_ROOT."/common/func/func_data.php");
require_once(BASE_ROOT."/common/func/func_brand.php");
require_once(BASE_ROOT."/common/func/func_user.php");

//-------------------------
//ログイン設定
//-------------------------
define("USER_COKKIE_VALUNE_ID","login_id");
define("USER_COKKIE_VALUNE_VAL","login_token");
define("USER_COOKIE_DOMAIN",".www.eye-r.com");
define("USER_COOKIE_TIME",0);
define("USER_COOKIE_PATH","/");

//-------------------------
//LINE ログイン
//-------------------------
define('LINE_LO_CLIENT_ID','2002483971');
define('LINE_LO_CLIENT_SECRET','b66fa08c877af79256b20ab5fa5dd041');
define('LINE_LO_TOKEN','akehc66wghebx46epn');//任意

//callback
define('LINE_LO_CALLBACK','https://www.eye-r.com/user/callback.php');


//-------------------------
//LINE Messaging API
//-------------------------
define('LINE_ME_CLIENT_ID','2002484040');
define('LINE_ME_CLIENT_SECRET','8ceb3cf4ead7b2586b70206809942198');
define('LINE_ME_TOKEN','pNOoRt/1JKOJgiLFpWFLon+wPlF5hTJcNRzdydEpwoadq3ns3Em48kwqKRvmC6dhxR1prvOY3+S+V+BdtersLiQEFphI36BafvvWko0gCuvup6RMjo6dKVMD8+bwHkkF5GpBsIOVAJxjNGW0HJ8YdQdB04t89/1O/w1cDnyilFU=');
define('LINE_ME_FREND_URL','https://line.me/R/ti/p/%40307pitqb');


//-------------------------
//設定
//-------------------------
$week_arr = ['日','月','火','水','木','金','土'];

//会員種別
$user_type_arr = [];
$user_type_arr[4] = [
    'name'=>'特別会員',
    'name_2'=>'特別会員',
    'brand_limit'=>60,
    'dc_limit'=>5,
    'dc_day_limit'=>7,
    'dc_disp_log'=>0
];
$user_type_arr[3] = [
    'name'=>'Ｓ会員　',
    'name_2'=>'Ｓ会員　',
    'brand_limit'=>45,
    'dc_limit'=>5,
    'dc_day_limit'=>5,
    'dc_disp_log'=>0
];

$user_type_arr[2] = [
    'name'=>'Ａ会員',
    'name_2'=>'Ａ会員',
    'brand_limit'=>30,
    'dc_limit'=>4,
    'dc_day_limit'=>4,
    'dc_disp_log'=>0
];

$user_type_arr[1] = [
    'name'=>'通常会員',
    'name_2'=>'通常会員',
    'brand_limit'=>15,
    'dc_limit'=>3,
    'dc_day_limit'=>3,
    'dc_disp_log'=>0
];


//-------------------------
//銘柄の一覧表示
//-------------------------
function get_brand_data_disp($data){
    $url = BASE_URL."bra".$data['brand_id']."/";
    return $url;
}


//-------------------------
//銘柄登録上限取得
//-------------------------
function get_user_brand_limit($data){
    global $user_type_arr;
    return $user_type_arr[$data['user_type']]['brand_limit'];
}

function get_user_dc_limit($data){
    global $user_type_arr;
    return $user_type_arr[$data['user_type']]['dc_limit'];
}

function get_user_dc_day_limit($data){
    global $user_type_arr;
    return $user_type_arr[$data['user_type']]['dc_day_limit'];
}

function get_user_dc_disp_log($data){
    global $user_type_arr;
    return $user_type_arr[$data['user_type']]['dc_disp_log'];
}

function get_user_dc_disp_log_list(){
    global $user_type_arr;
    $list = [];
    foreach($user_type_arr as $key => $value){
        if($value['dc_disp_log'] == 1){
            $list[] = $key;
        }
    }
    return $list;
}


//-------------------------
//DB接続
//-------------------------
$db = db_connect();
?>