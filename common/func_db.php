<?
// +-------------------------------+
// + ＤＢ基本        　　　　　　　+
// +-------------------------------+


//--------------------------	
//接続
//--------------------------
function db_connect(){
	$db = new PDO(DSN, DSN_USER, DSN_PASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);	
	return $db;
}
	
//--------------------------
//トランザクション　開始
//--------------------------
function begin(){
	$GLOBALS['db']->query("begin");
}


//--------------------------
//トランザクション　コミット
//--------------------------
function commit(){
	$GLOBALS['db']->query("commit");
}
	
	

//--------------------------
//切断
//--------------------------
function db_close($db)
{
	$db->disconnect();
}
	
//--------------------------
//sqlの実行
//--------------------------
function exec_sql($sql){
	$stmt = $GLOBALS['db']->query($sql);
	$res = $GLOBALS['db']->errorInfo();

	if($res[0] == "00000"){
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $rows;
	}else{

		$GLOBALS['db']->rollBack();

		if($GLOBALS["G_is_debug"] == 1){
			print_r($res);
		}else{
			echo('エラー発生！！<br>');
			echo('「\'」や「"」や「;」の一部の記号をご入力頂いた場合、エラーが発生することがあります。<br>');
			echo('また、サーバ負荷が一時的に上昇している場合もエラーが起こります。<br>');
		}

		exit;
	}
}
	
	
//--------------------------	
//$rs変換
//--------------------------
function convert_rs($rs)
{		
	return $rs;
}

	
	
//--------------------------
//max_id取得
//--------------------------
function get_max_id($table,$field,$cound)
{
	$sql="SELECT max(".$field.") AS max_id FROM ".$table." ";
	if(strlen($cound)>0){
		$sql .= $cound;
	}
	$sql.=";";	
	$rs=exec_sql($sql);
	$RS_max=convert_rs($rs);
	if(!$RS_max){
		$max_id=1;
	}else{
		$max_id=($RS_max[0]['max_id']+1);
	}
		
	return $max_id;
}

		
//--------------------------
//エラー画面表示
//--------------------------
function _raise($location,$msg,$kind="db_class")
{
	echo('<!DOCTYPE html><html lang="ja"><head><meta charset="UTF-8"></head>');
	$format="<b style=\"color:#FF0000;\">ERROR!!</b> : [".$kind." <b>%s</b>]<br><div style=\"font-size:10pt;\">%s</div>";
	echo("<div style=\"font-family:'Courier New' font-size:15pt; border:3px double #FF0000; padding:5px; text-align:left; width:80%;\">");
	if($GLOBALS["G_is_debug"]){
		echo(sprintf($format,$location,$msg));
	}else{
		echo('「\'」や「"」や「;」など一部の記号を使用するとエラーが起こります。<br>');
		echo('また、サーバ負荷が一時的に上昇している場合もエラーが起こります。<br>');
		echo('上記以外の場合は、システム管理者へ連絡してください。');
	}
	echo('</div>');
	echo('</html>');
	exit;
}

?>