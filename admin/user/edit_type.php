<?php
require_once("Config.php");

$p_items = array();
$p_items = $_POST;

$result['mode'] = "error";

if($p_items['user_id'] > 0 && $p_items['user_type']){

    begin();

    update_user_type($p_items['user_id'],$p_items['user_type']);

    $list = [];
    $list = get_user_brand_user_list($p_items['user_id']);

    $limit = get_user_brand_limit(['user_type'=>$p_items['user_type']]);

    if(count($list) > $limit){
        
        $r_list = array_reverse($list);

        $cnt = count($list) - $limit;

        for($i=0;$i<$cnt;$i++){
            delete_user_brand($p_items['user_id'],$r_list[$i]['brand_id']);
        }

    }

    
    commit();

    $result['cnt'] = count(get_user_brand_user_list($p_items['user_id']));
    $result['mode'] = "success";

}



$result = json_encode($result);
print $result;
?>