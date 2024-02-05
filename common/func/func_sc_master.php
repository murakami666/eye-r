<?php
//---------------------------
//取得
//---------------------------
function get_sc_master(){

    $sql = "SELECT * FROM tbl_sc_master WHERE ms_id=1";
    $rs = exec_sql($sql);
	$rs_list = convert_rs($rs);

    return $rs_list[0];
}

//---------------------------
//更新
//---------------------------
function update_ms_now_flg($flg){
    $sql = sprintf("UPDATE tbl_sc_master SET ms_now_flg=%u WHERE ms_id=%u",$flg,1);
    exec_sql($sql);
}

?>