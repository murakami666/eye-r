<?php
/***************************************/
/*お知らせ*/
/***************************************/
//---------------------------
//一覧取得
//---------------------------
function get_info_list($items){

    if($items['mode'] == "cnt"){
		$sql = "SELECT count(info_id) as cnt_all ";
	}else{
		$sql = "SELECT ";
		if(count($items['colimn_arr']) > 0){
			$sql .= join(",",$items['colimn_arr']);
		}else{
			$sql .= " * ";
		}
	}


	$sql .= " FROM tbl_info";

	//---------------------
	//検索
	//---------------------
	$sub_sql = array();
	if($items['info_id'] > 0){$sub_sql[] = sprintf(" info_id=%u",$items['info_id']);}

    if(count($sub_sql) > 0){
		$sql .= " WHERE ".join(" AND ",$sub_sql);
	}

	//---------------------
	//並び順指定
	//---------------------
	if($items['mode'] != "cnt"){
		switch($items['sort']){
			default :
				$sql .= " ORDER BY info_date DESC,info_id DESC";
				break;
		}
	}
	
	//---------------------
	//件数指定
	//---------------------
	if($items['limit'] > 0){
		if($items['page'] > 0){
			$sql .= sprintf(" LIMIT %u OFFSET %u",$items['limit'],(($items['page'] * $items['limit']) - $items['limit']));
		}else{
			$sql .= sprintf(" LIMIT %u",$items['limit']);
		}
	}
	
	//print $sql."<br>";
	
	$rs = exec_sql($sql);
	$rs_list = convert_rs($rs);
	
	if($items['mode'] == "cnt"){
		return $rs_list[0]['cnt_all'];
	}else{
		if($items['detail'] == 1){
			return $rs_list[0];
		}else{
			return $rs_list;
		}
	}

}


//---------------------------
//登録
//---------------------------
function regist_info($items){

    $max_id = get_max_id("tbl_info","info_id","");
	
    $sql = sprintf(
        "INSERT tbl_info (info_id,info_date,info_text,info_regist_date) values (%u,'%s','%s','%s')"
        ,$max_id,$items['info_date'],$items['info_text'],date("Y-m-d H:i:s")
    );
    exec_sql($sql);
}

//---------------------------
//名前上書き
//---------------------------
function update_info($info_id,$items){
	$sql = sprintf(
		"UPDATE tbl_info SET info_date='%s',info_text='%s' WHERE info_id=%u"
		,$items['info_date'],$items['info_text'],$info_id
	);
	exec_sql($sql);
}

//---------------------------
//削除
//---------------------------
function delete_info($info_id){
	$sql = sprintf("DELETE FROM tbl_info WHERE info_id=%u",$info_id);
	exec_sql($sql);
}
