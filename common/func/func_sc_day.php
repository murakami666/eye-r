<?php
/***************************************/
/*休日*/
/***************************************/
//---------------------------
//一覧取得
//---------------------------
function get_sc_day($items){

    if($items['mode'] == "cnt"){
		$sql = "SELECT count(holi_date) as cnt_all ";
	}else{
		$sql .= "SELECT * ";
	}


	$sql .= "FROM tbl_sc_day ";

	//---------------------
	//検索
	//---------------------
	$sub_sql = array();

    if(count($sub_sql) > 0){
		$sql .= " WHERE ".join(" AND ",$sub_sql);
	}

	//---------------------
	//並び順指定
	//---------------------
	if($items['mode'] != "cnt"){
		switch($items['sort']){
			default :
				$sql .= " ORDER BY sc_date DESC";
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
function regist_sc_day($date){
	$sql = sprintf("INSERT INTO tbl_sc_day (sc_date) VALUES ('%s')",$date);
    exec_sql($sql);

}

//---------------------------
//削除
//---------------------------
function delete_sc_day(){
	
    $sql = sprintf("DELETE FROM tbl_sc_day WHERE sc_date <= '%s'",date("Y-m-d",strtotime("-20 day")));
	exec_sql($sql);
	
	$sql = sprintf("DELETE FROM tbl_sc_data WHERE sc_date <= '%s'",date("Y-m-d",strtotime("-20 day")));
    exec_sql($sql);
}

?>