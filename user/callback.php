<?php
require_once("tmp.php");
require_once("Config.php");

if($_GET['state'] && $_GET['state'] == LINE_LO_TOKEN){

    //------------------------
    //アクセストークンを取得する
    //------------------------
    $postData = array(
        'grant_type'    => 'authorization_code',
        'code'          => $_GET['code'],
        'redirect_uri'  => LINE_LO_CALLBACK."?newflg=".$_GET['newflg'],
        'client_id'     => LINE_LO_CLIENT_ID,
        'client_secret' => LINE_LO_CLIENT_SECRET,
    );
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    curl_setopt($ch, CURLOPT_URL, 'https://api.line.me/oauth2/v2.1/token');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($response);
    $accessToken = $json->access_token; //アクセストークンを取得

    //------------------------
    //アクセストークンを基にユーザ情報を取得する
    //------------------------
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $accessToken));
    curl_setopt($ch, CURLOPT_URL, 'https://api.line.me/v2/profile');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
     
    $json = json_decode($response);
    $userInfo= json_decode(json_encode($json), true); //ログインユーザ情報を取得する

    if(strlen($userInfo['userId']) > 0){
        
        //------------------------
        //過去にログインしているか確認
        //------------------------
        $items = [];
        $items['user_line_id'] = $userInfo['userId'];
        $items['detail'] = 1;
        $user_data = [];
        $user_data = get_user_list($items);

        begin();



        if($user_data['user_id']){

        }else{

            if($_GET['newflg'] == 0){
                header("Location: ".BASE_URL."user/error.php"); 
                exit();
            }


            $items = [];
            $items['user_line_id'] = $userInfo['userId'];

            $data = regist_user($items);
            
            $user_data['user_id'] = $data['user_id'];
            $user_data['user_line_login_text'] = $data['user_line_login_text'];
            

            
        }

        update_user_name($user_data['user_id'],$userInfo['displayName']);
        update_user_line_img($user_data['user_id'],$userInfo['pictureUrl']);
        update_user_line_accesstoken($user_data['user_id'],$accessToken);
        update_user_login_date($user_data['user_id']);

        commit();

        setcookie(USER_COKKIE_VALUNE_ID,$user_data['user_id'],USER_COOKIE_TIME,USER_COOKIE_PATH);
        setcookie(USER_COKKIE_VALUNE_VAL,$user_data['user_line_login_text'],USER_COOKIE_TIME,USER_COOKIE_PATH);
        
        sleep(1);

        header("Location: ".BASE_URL."user/my.php"); 
        
        exit();
        
    }else{
        header("Location: ".BASE_URL."user/"); 
        exit();
    }

}else{
    header("Location: ".BASE_URL."user/"); 
    exit();
}
?>