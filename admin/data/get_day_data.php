<?php
require_once("Config.php");

$p_items = array();
$p_items = data_trim_in($_POST);

$result['mode'] = "error";

if(strlen($p_items['datetime']) > 0){

    //-------------------------
    //データ一覧取得
    //-------------------------
    $items = [];
    $items['join'] = ['brand'];
    $items['data_date'] = date("Y-m-d",$p_items['datetime']);
    $data_list = [];
    $data_list = get_data_list($items);

    $text = "";
    foreach((array)$data_list as $key => $value){
        $text .= '<tr class="data_tr"><td>'.date("H:i",strtotime($value['data_date']." ".$value['data_time'])).'</td><td>['.$value['data_code'].']<a href="'.get_brand_data_disp($value).'" target="_blank">'.$value['data_name'].'</a></td><td><a href="'.$value['data_link'].'" target="_blank">'. $value['data_text'].'</a></td></tr>';
    }

    $result['reg_html'] = $text;

    $result['mode'] = "success";
    
}

$result = json_encode($result);
print $result;

?>