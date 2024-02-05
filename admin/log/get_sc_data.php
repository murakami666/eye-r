<?php
require_once("Config.php");
require_once("func/func_sc_disp_log.php");

$p_items = array();
$p_items = data_trim_in($_POST);

$result['mode'] = "error";

if(strlen($p_items['datetime']) > 0){

    //--------------------------
    //会員取得
    //--------------------------
    $items = [];
    $items['user_type_arr'] = get_user_dc_disp_log_list();
    $user_list = [];
    $user_list = get_user_list($items);

    //--------------------------
    //銘柄名取得
    //--------------------------
    $items = [];
    $arr = [];
    $arr = get_brand_list($items);

    $brand_list = [];
    foreach((array)$arr as $value){
        $brand_list[$value['brand_id']] = [
            'brand_id'=>$value['brand_id'],
            'brand_code'=>$value['brand_code'],
            'brand_name'=>$value['brand_name']
        ];
    }


    //--------------------------
    //ログ取得
    //--------------------------
    $items = [];
    $items['log_date'] = date("Y-m-d",$p_items['datetime']);
    $arr = [];
    $arr = get_sc_disp_log_day_list($items);

    $log_list = [];
    foreach((array)$arr as $value){
        $log_list[$value['log_user']][] = [
            'brand' => $brand_list[$value['log_brand']],
            'pv' => $value['count']
        ];
    }


    $text = '';
    if(count($log_list) > 0){
        foreach($user_list as $value){
            if(count($log_list[$value['user_id']]) > 0){
                $list = $log_list[$value['user_id']];
                $text .= '<tr class="data_tr"><td rowspan="'.count($list).'">'.$value['user_name'].'</td>';
                $flg = false;
                foreach($list as $s_value){
                    if($flg == true){
                        $text .= '</tr><tr class="data_tr">';
                    }else{
                        $flg = true;
                    }
                    $text .= '<td><a href="'.get_brand_data_disp($s_value['brand']).'?dcm=y" target="_blank">['.$s_value['brand']['brand_code'].']'.$s_value['brand']['brand_name'].'</td><td>'.$s_value['pv'].'</td>';
                }    
            }
            $text .= '</tr>';
        }
    }

    $result['reg_html'] = $text;

    $result['mode'] = "success";
    
}

$result = json_encode($result);
print $result;

?>