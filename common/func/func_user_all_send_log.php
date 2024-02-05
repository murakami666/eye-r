<?php
//---------------------------
//一覧取得
//---------------------------
function get_user_all_send_log_list($items){

	$sql = "SELECT * FROM tbl_user_all_send_log ORDER BY log_id DESC";

	$rs = exec_sql($sql);
	$rs_list = convert_rs($rs);

	return $rs_list;
}


//---------------------------
//登録
//---------------------------
function regist_user_all_send_log($items){

    $max_id = get_max_id("tbl_user_all_send_log","log_id","");

    $sql = sprintf(
        "INSERT INTO tbl_user_all_send_log "
        ."(log_id,log_user_num,log_text,log_regist_date,log_ip)"
        ."VALUES"
        ."(%u,%u,'%s','%s','%s')"
        ,$max_id,$items['log_user_num'],$items['log_text'],date("Y-m-d H:i:s"),$_SERVER["REMOTE_ADDR"]
    );

    exec_sql($sql);

    //過去分削除
    $limit = 10;

    $list = [];
    $list = get_user_all_send_log_list([]);

    if(count($list) > $limit){

        $del_list = [];
        $del_list = array_slice(array_reverse($list),0,(count($list) - $limit));

        foreach($del_list as $key=> $value){
            $sql = sprintf("DELETE FROM tbl_user_all_send_log WHERE log_id=%u",$value['log_id']);
            exec_sql($sql);
        }
    }

}
?>