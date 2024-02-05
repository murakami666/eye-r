<?php
//---------------------------
//一覧取得
//---------------------------
function get_brand_list($items){

    if($items['mode'] == "cnt"){
		$sql = "SELECT count(brand_id) as cnt_all ";
	}else{
		$sql = "SELECT ";
		if(count($items['colimn_arr']) > 0){
			$sql .= join(",",$items['colimn_arr']);
		}else{
			$sql .= " * ";
		}
	}


	$sql .= " FROM tbl_brand ";

	//---------------------
	//検索
	//---------------------
	$sub_sql = array();
	if($items['brand_id'] > 0){$sub_sql[] = sprintf(" brand_id=%u",$items['brand_id']);}
	if(strlen($items['code_or_name_like']) > 0){$sub_sql[] = "(brand_code='".$items['code_or_name_like']."' OR brand_name LIKE '%".$items['code_or_name_like']."%')";}

	if(strlen($items['brand_sc_pd_3']) > 0){$sub_sql[] = sprintf(" brand_sc_pd_3=%u",$items['brand_sc_pd_3']);}
	if(strlen($items['brand_sc_pd_4']) > 0){$sub_sql[] = sprintf(" brand_sc_pd_4=%u",$items['brand_sc_pd_4']);}
	if(strlen($items['brand_sc_pd_5']) > 0){$sub_sql[] = sprintf(" brand_sc_pd_5=%u",$items['brand_sc_pd_5']);}
	if(strlen($items['brand_sc_pu_3']) > 0){$sub_sql[] = sprintf(" brand_sc_pu_3=%u",$items['brand_sc_pu_3']);}
	if(strlen($items['brand_sc_pu_4']) > 0){$sub_sql[] = sprintf(" brand_sc_pu_4=%u",$items['brand_sc_pu_4']);}
	if(strlen($items['brand_sc_pu_5']) > 0){$sub_sql[] = sprintf(" brand_sc_pu_5=%u",$items['brand_sc_pu_5']);}

	if(strlen($items['brand_sc_up_stop_2']) > 0){$sub_sql[] = sprintf(" brand_sc_up_stop_2=%u",$items['brand_sc_up_stop_2']);}
	if(strlen($items['brand_sc_up_stop_3']) > 0){$sub_sql[] = sprintf(" brand_sc_up_stop_3=%u",$items['brand_sc_up_stop_3']);}
	if(strlen($items['brand_sc_dw_stop_2']) > 0){$sub_sql[] = sprintf(" brand_sc_dw_stop_2=%u",$items['brand_sc_dw_stop_2']);}
	if(strlen($items['brand_sc_dw_stop_3']) > 0){$sub_sql[] = sprintf(" brand_sc_dw_stop_3=%u",$items['brand_sc_dw_stop_3']);}

    if(count($sub_sql) > 0){
		$sql .= " WHERE ".join(" AND ",$sub_sql);
	}

	//---------------------
	//並び順指定
	//---------------------
	if($items['mode'] != "cnt"){
		switch($items['sort']){
			case "brand_id" :
				$sql .= " ORDER BY brand_id";
				break;
			case "brand_code" :
				$sql .= " ORDER BY brand_code";
				break;
			default :
				$sql .= " ORDER BY brand_id DESC";
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
function regist_brand($items){

    $max_id = get_max_id("tbl_brand","brand_id","");

    $sql = sprintf(
        "INSERT INTO tbl_brand "
        ."(brand_id,brand_code,brand_name,brand_regist_date)"
        ."VALUES"
        ."(%u,'%s','%s','%s')"
        ,$max_id,$items['brand_code'],$items['brand_name'],date("Y-m-d H:i:s")
    );
    exec_sql($sql);
    return $max_id;
}

//---------------------------
//更新
//---------------------------
function update_brand($id,$items){

	$sql = sprintf(
		"UPDATE tbl_brand SET brand_code='%s',brand_name='%s' WHERE brand_id=%u"
		,$items['brand_code'],$items['brand_name'],$id
	);
	exec_sql($sql);

}


//---------------------------
//更新(乖離率)
//---------------------------
function update_brand_sc($id,$items){

	$sql = sprintf(
		"UPDATE tbl_brand SET "
		." brand_sc_pd_3=%u,brand_sc_pd_4=%u,brand_sc_pd_5=%u"
		.",brand_sc_pu_3=%u,brand_sc_pu_4=%u,brand_sc_pu_5=%u"
		.",brand_sc_up_stop_2=%u,brand_sc_up_stop_3=%u,brand_sc_dw_stop_2=%u,brand_sc_dw_stop_3=%u"
		." WHERE brand_id=%u"
		,$items['brand_sc_pd_3'],$items['brand_sc_pd_4'],$items['brand_sc_pd_5']
		,$items['brand_sc_pu_3'],$items['brand_sc_pu_4'],$items['brand_sc_pu_5']
		,$items['brand_sc_up_stop_2'],$items['brand_sc_up_stop_3'],$items['brand_sc_dw_stop_2'],$items['brand_sc_dw_stop_3']
		,$id
	);
	exec_sql($sql);
}

//---------------------------
//削除
//---------------------------
function delete_brand($id){

	$sql = sprintf("DELETE FROM tbl_brand WHERE brand_id=%u",$id);
	exec_sql($sql);

	$sql = sprintf("DELETE FROM tbl_user_brand WHERE ub_brand=%u",$id);
	exec_sql($sql);

	$sql = sprintf("DELETE FROM tbl_sc_data WHERE sc_brand=%u",$id);
    exec_sql($sql);
	
}

?>