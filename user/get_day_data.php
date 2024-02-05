<?php
require_once("tmp.php");
require_once("Config.php");

require_once("./login_check.php");

$p_items = array();
$p_items = data_trim_in($_POST);

$result['mode'] = "error";

if(strlen($p_items['datetime']) > 0){

    //-------------------------
    //登録済み一覧取得
    //-------------------------
    $brand_list = [];
    $brand_list = get_user_brand_user_list($user_data['user_id']);

    if(count($brand_list) > 0){
        //-------------------------
        //データ一覧取得
        //-------------------------
        $items = [];
        $items['join'] = ['brand'];

        if($p_items['datetime'] != "all"){
            $items['data_date'] = date("Y-m-d",$p_items['datetime']);
        }

        foreach((array)$brand_list as $key => $value){
            $items['brand_id_arr'][] = $value['brand_id'];
        }
        
        $data_list = [];
        $data_list = get_data_list($items);

        $text = "";
        foreach((array)$data_list as $key => $value){
            $text .= '<div class="data_tr"><p class="date">'.date("m月d日 H:i",strtotime($value['data_date']." ".$value['data_time'])).'</p><p class="title"><a href="'.$value['data_link'].'" target="_blank">'. $value['data_text'].'</a></p><p class="name"><a href="'.get_brand_data_disp($value).'" target="_blank">['.$value['data_code'].']'.$value['data_name'].'</a></p></div>';
        }

        $result['reg_html'] = $text;

        $result['mode'] = "success";
    }

}

$result = json_encode($result);
print $result;

?>