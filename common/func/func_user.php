<?php
/***************************************/
/*ユーザー*/
/***************************************/
//---------------------------
//一覧取得
//---------------------------
function get_user_list($items){

    if($items['mode'] == "cnt"){
		$sql = "SELECT count(user_id) as cnt_all ";
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

	$sql .= " tbl_user ";

	//---------------------
	//検索
	//---------------------
	$sub_sql = array();
	if($items['user_id'] > 0){$sub_sql[] = sprintf(" user_id=%u",$items['user_id']);}
	if(strlen($items['user_line_id']) > 0){$sub_sql[] = sprintf(" user_line_id='%s'",$items['user_line_id']);}
	if(strlen($items['user_line_login_text']) > 0){$sub_sql[] = sprintf(" user_line_login_text='%s'",$items['user_line_login_text']);}

	if(count($items['user_type_arr']) > 0){
        $sub_sql[] = "user_type IN (".join(",",$items['user_type_arr']).")";
    }

    if(count($sub_sql) > 0){
		$sql .= " WHERE ".join(" AND ",$sub_sql);
	}

	//---------------------
	//並び順指定
	//---------------------
	if($items['mode'] != "cnt"){
		switch($items['sort']){
			case "user_regist_date" :
				$sql .= " ORDER BY user_regist_date DESC";
				break;
			case "user_regist_date_desc" :
				$sql .= " ORDER BY user_regist_date";
				break;
			case "user_login_date" :
				$sql .= " ORDER BY user_login_date DESC";
				break;
			case "user_login_date_desc" :
				$sql .= " ORDER BY user_login_date";
				break;
			default :
				$sql .= " ORDER BY user_id DESC";
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
function regist_user($items){

    $max_id = get_max_id("tbl_user","user_id","");
	$login_text = makeRandStr(10);
	
    $sql = sprintf(
        "INSERT tbl_user (user_id,user_line_id,user_line_login_text,user_regist_date) values (%u,'%s','%s','%s')"
        ,$max_id,$items['user_line_id'],$login_text,date("Y-m-d H:i:s")
    );
    exec_sql($sql);

	$data = ['user_id'=>$max_id,'user_line_login_text'=>$login_text];
	
    return $data;

}

//---------------------------
//名前上書き
//---------------------------
function update_user_name($user_id,$user_name){
	$sql = sprintf(
		"UPDATE tbl_user SET user_name='%s' WHERE user_id=%u"
		,$user_name,$user_id
	);
	exec_sql($sql);
}


//---------------------------
//画像上書き
//---------------------------
function update_user_line_img($user_id,$img){
	$sql = sprintf(
		"UPDATE tbl_user SET user_line_img='%s' WHERE user_id=%u"
		,$img,$user_id
	);
	exec_sql($sql);
}


//---------------------------
//アクセストークン上書き
//---------------------------
function update_user_line_accesstoken($user_id,$token){
	$sql = sprintf(
		"UPDATE tbl_user SET user_line_accesstoken='%s' WHERE user_id=%u"
		,$token,$user_id
	);
	exec_sql($sql);
}



//---------------------------
//ログイン日時更新
//---------------------------
function update_user_login_date($user_id){

	$sql = sprintf(
		"UPDATE tbl_user SET user_login_date='%s' WHERE user_id=%u"
		,date("Y-m-d H:i:s"),$user_id
	);
	exec_sql($sql);

}

//---------------------------
//種別更新
//---------------------------
function update_user_type($user_id,$type){

	$sql = sprintf(
		"UPDATE tbl_user SET user_type=%u WHERE user_id=%u"
		,$type,$user_id
	);
	exec_sql($sql);

}


//---------------------------
//削除
//---------------------------
function delete_user($user_id){

	$sql = sprintf("DELETE FROM tbl_user WHERE user_id=%u",$user_id);
	exec_sql($sql);

	$sql = sprintf("DELETE FROM tbl_user_brand WHERE ub_user=%u",$user_id);
	exec_sql($sql);

}


/***************************************/
/*銘柄登録*/
/***************************************/
//---------------------------
//登録済み一覧取得(会員)
//---------------------------
function get_user_brand_user_list($user_id){

	$sql = sprintf(
		"SELECT * FROM (tbl_brand INNER JOIN tbl_user_brand ON brand_id=ub_brand) WHERE ub_user=%u ORDER BY ub_regist_date"
		,$user_id
	);
	$rs = exec_sql($sql);
	$rs_list = convert_rs($rs);
    
    return $rs_list;

}

//---------------------------
//登録済み一覧取得(複数コード)
//---------------------------
function get_user_brand_brand_group_list($brand_list){

    $sql = "SELECT * FROM (SELECT ub_user,ub_brand FROM tbl_user_brand WHERE ub_brand IN (".join(",",$brand_list).")) as ub INNER JOIN tbl_user ON ub.ub_user=user_id";
    $rs = exec_sql($sql);
	$rs_list = convert_rs($rs);
    

    return $rs_list;

}


//---------------------------
//登録
//---------------------------
function regist_user_brand($user,$brand){
    $sql = sprintf(
        "INSERT INTO tbl_user_brand "
        ."(ub_user,ub_brand,ub_regist_date)"
        ."VALUES"
        ."(%u,%u,'%s')"
        ,$user,$brand,date("Y-m-d H:i:s")
    );
    exec_sql($sql);
}


//---------------------------
//削除
//---------------------------
function delete_user_brand($user,$brand){
    $sql = sprintf(
		"DELETE FROM tbl_user_brand WHERE ub_user=%u AND ub_brand=%u"
        ,$user,$brand
    );
    exec_sql($sql);
}
?>