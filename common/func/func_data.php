<?php
//---------------------------
//一覧取得
//---------------------------
function get_data_list($items){

    if($items['mode'] == "cnt"){
		$sql = "SELECT count(data_id) as cnt_all ";
	}else{
		$sql = "SELECT ";
		if(count($items['colimn_arr']) > 0){
			$sql .= join(",",$items['colimn_arr']);
		}else{
			$sql .= " * ";
		}
	}


	$sql .= " FROM ";

	//---------------------
	//JOIN用括弧
    //---------------------
    if(array_key_exists('join', $items)){
        for($i=0;$i<count($items['join']);$i++){$sql .= "(";}
    }

	$sql .= " tbl_data ";


	if(array_key_exists('join', $items)){
        if(count($items['join'])>0){
			if(in_array("brand",$items['join'])){$sql .= "  INNER JOIN tbl_brand ON data_code=brand_code)";}//銘柄
		}
	}

	//---------------------
	//検索
	//---------------------
	$sub_sql = array();
	if(strlen($items['data_date']) > 0){$sub_sql[] = sprintf(" data_date='%s'",$items['data_date']);}
	if(strlen($items['data_date_min']) > 0){$sub_sql[] = sprintf(" data_date>='%s'",$items['data_date_min']);}
	if(strlen($items['data_time']) > 0){$sub_sql[] = sprintf(" data_time='%s'",$items['data_time']);}

	if($items['brand_id'] > 0){$sub_sql[] = sprintf(" brand_id=%u",$items['brand_id']);}
	if(count($items['brand_id_arr']) > 0){
        $list = [];
        foreach((array)$items['brand_id_arr'] as $key => $value){
            $list[] = "'".$value."'";
        }
        $sub_sql[] = "brand_id IN (".join(",",$list).")";
    }

    if(count($sub_sql) > 0){
		$sql .= " WHERE ".join(" AND ",$sub_sql);
	}

	//---------------------
	//並び順指定
	//---------------------
	if($items['mode'] != "cnt"){
		switch($items['sort']){
			default :
				$sql .= " ORDER BY data_id DESC";
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
function regist_data($items){

    $max_id = get_max_id("tbl_data","data_id","");

    $sql = sprintf(
        "INSERT INTO tbl_data "
        ."(data_id,data_date,data_time,data_code,data_name,data_text,data_link,data_regist_date)"
        ."VALUES"
        ."(%u,'%s','%s','%s','%s','%s','%s','%s')"
        ,$max_id,$items['data_date'],$items['data_time'],$items['data_code'],$items['data_name'],$items['data_text'],$items['data_link'],date("Y-m-d H:i:s")
    );

    exec_sql($sql);
}

//---------------------------
//過去分削除
//---------------------------
function delete_old_data(){

	$sql = sprintf("DELETE FROM tbl_data WHERE data_date < '%s'",date("Y-m-d",strtotime("-30 day")));
	exec_sql($sql);

}

?>