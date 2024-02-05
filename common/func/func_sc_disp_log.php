<?php
//---------------------------
//一覧取得
//---------------------------
function get_sc_disp_log_day_list($items){

    $sql = sprintf(
        "SELECT log_user,log_brand,count(*) as count FROM tbl_sc_disp_log WHERE DATE(log_regist_date)='%s' GROUP BY log_user,log_brand ORDER BY count DESC,log_brand DESC"
        ,$items['log_date']
    );
	$rs = exec_sql($sql);
	$rs_list = convert_rs($rs);

	return $rs_list;
}


//---------------------------
//登録
//---------------------------
function regist_sc_disp_log($items){

    $max_id = get_max_id("tbl_sc_disp_log","log_id","");

    $sql = sprintf(
        "INSERT INTO tbl_sc_disp_log "
        ."(log_id,log_user,log_brand,log_regist_date,log_ip)"
        ."VALUES"
        ."(%u,%u,%u,'%s','%s')"
        ,$max_id,$items['log_user'],$items['log_brand']
        ,date("Y-m-d H:i:s"),$_SERVER["REMOTE_ADDR"]
    );

    exec_sql($sql);
}

//---------------------------------
//過去分削除
//---------------------------------
function delete_sc_disp_log(){
    $sql = sprintf(
        "DELETE FROM tbl_sc_disp_log WHERE log_regist_date<='%s'"
        ,date("Y-m-d H:i:s",strtotime("-100 day"))
    );
    exec_sql($sql);
}
?>