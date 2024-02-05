<?php
$disp_flg = false;

if(isset($_COOKIE[USER_COKKIE_VALUNE_ID]) && strlen($_COOKIE[USER_COKKIE_VALUNE_ID]) > 0 && isset($_COOKIE[USER_COKKIE_VALUNE_VAL]) && strlen($_COOKIE[USER_COKKIE_VALUNE_VAL]) > 0){

    $items = [];
    $items['user_id'] = $_COOKIE[USER_COKKIE_VALUNE_ID];
    $items['user_line_login_text'] = $_COOKIE[USER_COKKIE_VALUNE_VAL];
    $items['detail'] = 1;
    $user_data = [];
    $user_data = get_user_list($items);

    if($user_data['user_id']){
        $disp_flg = true;
    }
}

if($disp_flg == false){

    setcookie(USER_COKKIE_VALUNE_ID,"",time()-1,USER_COOKIE_PATH,USER_COOKIE_DOMAIN);
    setcookie(USER_COKKIE_VALUNE_VAL,"",time()-1,USER_COOKIE_PATH,USER_COOKIE_DOMAIN);

    header("Location: ".BASE_URL."user/"); 
    exit();
}


if(strlen($user_data['user_line_img']) == 0){
    $user_data['user_line_img'] = BASE_URL."img/icon.png";
}


?>