<?php
require_once("tmp.php");
require_once("Config.php");

require_once("../login_check.php");

$p_items = array();
$p_items = data_trim_in($_POST);

$result['mode'] = "error";

if($p_items['id'] > 0){

    switch($p_items['mode']){
        case "reg" :


            $brand_list = [];
            $brand_list = get_user_brand_user_list($user_data['user_id']);

            if(count($brand_list) >= get_user_brand_limit($user_data)){

                $result['error_text'] = '登録は'.get_user_brand_limit($user_data).'件までです';

            }else{

                begin();

                regist_user_brand($user_data['user_id'],$p_items['id']);
    
                commit();
    
                $result['mode'] = "success";
    
                $items = [];
                $items['brand_id'] = $p_items['id'];
                $items['detail'] = 1;
                $data = [];
                $data = get_brand_list($items);
    
                $result['reg_html'] = '<dl><dd><a href="'.get_brand_data_disp($data).'?dc=y">['.$data['brand_code'].']'.$data['brand_name'].'</a></dd><dd><span class="del_btn" data-id="'.$data['brand_id'].'" data-name="'.$data['brand_name'].'">[解除]</span></dd></dl>';

            }






            break;
        
        case "del" :

            begin();

            delete_user_brand($user_data['user_id'],$p_items['id']);

            commit();

            $result['mode'] = "success";

            break;
    }





}

$result = json_encode($result);
print $result;

?>