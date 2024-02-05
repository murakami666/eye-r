<?php
//---------------------------
//一覧取得
//---------------------------
function get_brand_edit_log_list($items){

	$sql = "SELECT * FROM tbl_brand_edit_log ORDER BY log_id DESC";

	$rs = exec_sql($sql);
	$rs_list = convert_rs($rs);

	return $rs_list;
}


//---------------------------
//登録
//---------------------------
function regist_brand_edit_log($items){

    $max_id = get_max_id("tbl_brand_edit_log","log_id","");

    $sql = sprintf(
        "INSERT INTO tbl_brand_edit_log "
        ."(log_id,log_type,log_brand_id,log_b_brand_code,log_b_brand_name,log_a_brand_code,log_a_brand_name,log_regist_date,log_ip)"
        ."VALUES"
        ."(%u,%u,%u,'%s','%s','%s','%s','%s','%s')"
        ,$max_id,$items['log_type'],$items['log_brand_id']
        ,$items['log_b_brand_code'],$items['log_b_brand_name'],$items['log_a_brand_code'],$items['log_a_brand_name']
        ,date("Y-m-d H:i:s"),$_SERVER["REMOTE_ADDR"]
    );

    exec_sql($sql);
}

//---------------------------------
//過去分削除
//---------------------------------
function delete_brand_edit_log(){
    $sql = sprintf(
        "DELETE FROM tbl_brand_edit_log WHERE log_regist_date<='%s'"
        ,date("Y-m-d H:i:s",strtotime("-90 day"))
    );
    exec_sql($sql);
}
?>