<?php
require_once("tmp.php");
require_once("Config.php");

require_once("../login_check.php");

$p_items = array();
$p_items = $_POST;


$result['mode'] = "error";

if(strlen($p_items['s_word']) > 0){

    //銘柄検索
    $items = [];
    $items['code_or_name_like'] = $p_items['s_word'];
    $items['sort'] = "brand_code";
    $items['limit'] = 20;
    $brand_list = [];
    $brand_list = get_brand_list($items);

    if(count($brand_list) > 0){

        //登録済み一覧除外
        $arr = [];
        $arr = get_user_brand_user_list($user_data['user_id']);

        $list = [];
        foreach((array)$arr as $key => $value){
            $list[] = $value['brand_id'];
        }

        $brand_list_text = "";
        foreach((array)$brand_list as $key => $value){

            $brand_list_text .= '<dl><dd><p>['.$value['brand_code'].']'.$value['brand_name'].'</p></dd><dd class="btbox">';

            if(in_array($value['brand_id'],$list)){
                $brand_list_text .= '<em class="non_reg">登録済</em>';
            }else{
                $brand_list_text .= '<span class="reg_btn" data-id="'.$value['brand_id'].'">登録</span>';
            }

            $brand_list_text .= '</dd></dl>'."\n";
        }

        if(strlen($brand_list_text) > 0){
            $result['brand_list_text'] = '<dl><dt>会社名</dt><dt></dt></dl>';
            $result['brand_list_text'] .= $brand_list_text;
            $result['mode'] = "success";
        }
        
    }

   

}

$result = json_encode($result);
print $result;

?>