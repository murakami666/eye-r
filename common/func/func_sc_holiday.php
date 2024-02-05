<?php
/***************************************/
/*休日*/
/***************************************/
//---------------------------
//一覧取得
//---------------------------
function get_sc_holiday($items){

    if($items['mode'] == "cnt"){
		$sql = "SELECT count(holi_date) as cnt_all ";
	}else{
		$sql .= " * ";
	}


	$sql .= "FROM tbl_sc_holiday ";

	//---------------------
	//検索
	//---------------------
	$sub_sql = array();
	if(strlen($items['holi_date']) > 0){$sub_sql[] = sprintf(" holi_date='%s'",$items['holi_date']);}
	if(strlen($items['code_or_name_like']) > 0){$sub_sql[] = "(brand_code='".$items['code_or_name_like']."' OR brand_name LIKE '%".$items['code_or_name_like']."%')";}


    if(count($sub_sql) > 0){
		$sql .= " WHERE ".join(" AND ",$sub_sql);
	}

	//---------------------
	//並び順指定
	//---------------------
	if($items['mode'] != "cnt"){
		switch($items['sort']){
			default :
				$sql .= " ORDER BY holi_date DESC";
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
function regist_sc_holiday($date){
    $sql = sprintf("INSERT INTO tbl_sc_holiday (holi_date) VALUES ('%s')",$date);
    exec_sql($sql);

}

//---------------------------
//削除
//---------------------------
function delete_sc_holiday(){
    $sql = sprintf("DELETE FROM tbl_sc_holiday");
    exec_sql($sql);
}

?>