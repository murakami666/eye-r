<?php
//---------------------------
//一覧取得
//---------------------------
function get_sc_data_list($items){

    if($items['mode'] == "cnt"){
		$sql = "SELECT count(*) as cnt_all ";
	}else{
		$sql = "SELECT ";
		if(count($items['colimn_arr']) > 0){
			$sql .= join(",",$items['colimn_arr']);
		}else{
			$sql .= " * ";
		}
	}

	$sql .= " FROM tbl_sc_data";

	//---------------------
	//検索
	//---------------------
	$sub_sql = array();
	if($items['sc_brand'] > 0){$sub_sql[] = sprintf(" sc_brand=%u",$items['sc_brand']);}
    if(strlen($items['sc_date']) > 0){$sub_sql[] = sprintf(" sc_date='%s'",$items['sc_date']);}
    if(strlen($items['sc_date_s']) > 0){$sub_sql[] = sprintf(" sc_date>='%s'",$items['sc_date_s']);}
    if(strlen($items['sc_date_e']) > 0){$sub_sql[] = sprintf(" sc_date<='%s'",$items['sc_date_e']);}
    if($items['sc_brand'] > 0){$sub_sql[] = sprintf(" sc_brand=%u",$items['sc_brand']);}

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
function regist_sc_data($items){

    $sql = sprintf(
        "INSERT INTO tbl_sc_data (sc_date,sc_brand) VALUES ('%s',%u)"
        ,$items['sc_date'],$items['sc_brand']
    );
    exec_sql($sql);

    $sql = "UPDATE tbl_sc_data SET ";

    $list = [];
    $arr = ['sc_op','sc_ep','sc_hp','sc_lp','sc_b_rate'];
    foreach($arr as $key => $value){
        if(is_numeric($items[$value])){
            $list[] = sprintf('%s=%10.1f',$value,$items[$value]);
        }else{
            $list[] = sprintf('%s=NULL',$value);
        }
    }

    $arr = ['sc_dr_25','sc_dr_75','sc_dr_200'];
    foreach($arr as $key => $value){
        if(is_numeric($items[$value])){
            $list[] = sprintf('%s=%10.2f',$value,$items[$value]);
        }else{
            $list[] = sprintf('%s=NULL',$value);
        }
    }

    $arr = ['sc_vo','sc_ne'];
    foreach($arr as $key => $value){
        if(is_numeric($items[$value])){
            $list[] = sprintf('%s=%u',$value,$items[$value]);
        }else{
            $list[] = sprintf('%s=NULL',$value);
        }
    }

    $sql .= join(",",$list).sprintf(" WHERE sc_date='%s' AND sc_brand=%u",$items['sc_date'],$items['sc_brand']);

    exec_sql($sql);
}


//---------------------------
//更新
//---------------------------
function update_sc_data($id,$date,$items){

	$sql = "UPDATE tbl_sc_data SET ";

    $list = [];
    $arr = ['sc_op','sc_ep','sc_hp','sc_lp','sc_b_rate'];
    foreach($arr as $key => $value){
        if(is_numeric($items[$value])){
            $list[] = sprintf('%s=%10.1f',$value,$items[$value]);
        }else{
            $list[] = sprintf('%s=NULL',$value);
        }
    }

    $arr = ['sc_vo'];
    foreach($arr as $key => $value){
        if(is_numeric($items[$value])){
            $list[] = sprintf('%s=%u',$value,$items[$value]);
        }else{
            $list[] = sprintf('%s=NULL',$value);
        }
    }

    $sql .= join(",",$list).sprintf(" WHERE sc_date='%s' AND sc_brand=%u",$date,$id);

    exec_sql($sql);

}

//---------------------------
//STOP高・安更新
//---------------------------
function update_sc_stop($id,$date,$items){
	$sql = sprintf(
		"UPDATE tbl_sc_data SET sc_up_stop=%u,sc_dw_stop=%u WHERE sc_date='%s' AND sc_brand=%u"
		,$items['sc_up_stop'],$items['sc_dw_stop'],$date,$id
	);
	exec_sql($sql);
}
?>