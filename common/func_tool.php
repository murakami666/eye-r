<?php
function url_exists( $url ){
    $headers = get_headers($url);
    if( is_array($headers) && count($headers) > 0 ) {
        if( strpos($headers[0],'OK') ) {
            return true;
        }
    }
    return false;
}

function get_url_data( $url ){

    $html = mb_convert_encoding(file_get_contents($url), 'HTML-ENTITIES', 'ASCII, JIS, UTF-8, EUC-JP, SJIS');


    $domDocument = new DOMDocument();
    $domDocument->loadHTML($html);
    
    
    //リンク先配列作成
    $anchors = $domDocument->getElementsByTagName('a');
    $link_list = [];
    if(count($anchors) > 0){
        foreach ($anchors as $anchor) {

            $value = $anchor->getAttribute('href');

            if (strpos($value, ".pdf") !== false) {
                $link_list[] = $value."\n";   
            }
    
        }
    }

    //データ配列作成
    $xmlString = $domDocument->saveXML();
    $xmlObject = simplexml_load_string($xmlString);
    $arr = json_decode(json_encode($xmlObject), true);
    $list = $arr['body']['form']['table']['tr']['0']['td']['div']['1']['table']['0']['tr'];

    $data_list = [];
    if(count($list) > 0){

        foreach($list as $key => $value){

            $t = [];
            $t = $value['td'];

            if( preg_match( '/0$/', $t[1]) ) {

                $data = [];
                $data['time'] = $t[0];
                $data['code'] = substr($t[1], 0, -1);
                $data['name'] = $t[2];
                $data['text'] = $t[3]['a'];
                $data['link'] = str_replace(array("\r\n", "\r", "\n"),"", 'https://www.release.tdnet.info/inbs/'.$link_list[$key]);

                $data_list[] = $data;

            }
        }

    }

    return $data_list;

}

function data_trim($items){
	if($items){
		if(is_array($items)){
			foreach($items as $key => $value){
				if(is_array($value)){
					foreach($value as $s_key => $s_value){
						$items[$key][$s_key] = trim($s_value);
					}
				}else{
					$items[$key] = trim($value);
				}
			}
			return $items;
		}else{
			return trim($items);
		}
	}
}

function data_trim_in($items){
	if($items){
		if(is_array($items)){
			foreach($items as $key => $value){
				if(is_array($value)){
					foreach($value as $s_key => $s_value){
						$items[$key][$s_key] = htmlspecialchars(trim($s_value),ENT_QUOTES,"UTF-8");
					}
				}else{
					$items[$key] = htmlspecialchars(trim($value),ENT_QUOTES,"UTF-8");
				}
			}
			return $items;
		}else{
			return htmlspecialchars(trim($items),ENT_QUOTES,"UTF-8");
		}
	}
}

//------------------------------------------
//セレクトリスト作成
//------------------------------------------
function get_select_list($itmes,$data){
	$select_list = "";
	if(count($itmes) > 0){
		foreach($itmes as $key => $val){
			$select_list .= "<option value=\"".$key."\"";
			if($key == $data){$select_list .= " selected=\"selected\"";}
			$select_list .= ">".$val."</option>\n";
		}
	}
	return $select_list;
}

//------------------------------------------
//セレクトリスト作成
//------------------------------------------
function get_select_list_2($min,$max,$data){
	$select_list = "";
	for($i=$min;$i<=$max;$i++){
		$select_list .= "<option value=\"".$i."\"";
		if($i == $data){$select_list .= " selected=\"selected\"";}
		$select_list .= ">".$i."</option>\n";
	}
	return $select_list;
}


//------------------------------------------
//パスワード作成
//------------------------------------------
function makeRandStr($length) {
	$str = array_merge(range('a','z'),range('0','9'));
	$r_str = null;
	for ($i=0;$i<$length;$i++) {
		$r_str .= $str[rand(0, count($str) - 1)];
	}
	return $r_str;
}

//------------------------------------------
//ページリスト作成
//------------------------------------------
function get_page_list($page,$all_cnt,$limit,$page_limit_back,$page_limit_next){
	if(($page - $page_limit_back) <= 0){
		$page_min = 1;
		if(ceil($all_cnt/$limit) > ($page_limit_back + $page_limit_next + 1)){
			$page_max = $page_limit_back + $page_limit_next + 1;
		}else{
			$page_max = ceil($all_cnt/$limit);
		}
	}else{
		if(($page + $page_limit_next) < ceil($all_cnt/$limit)){
			$page_min = $page - $page_limit_back;
			$page_max = $page + $page_limit_next;
		}else{
			if((ceil($all_cnt/$limit) - $page_limit_back - $page_limit_next ) < 1){
				$page_min = 1;
			}else{
				$page_min = ceil($all_cnt/$limit) - $page_limit_back - $page_limit_next;
			}
			$page_max = (ceil($all_cnt/$limit) - $page) + $page;
		}
	}
	
	$page_list = "";
	if($page > 1){
		$page_list .= "<a href=\"javascript:pg_ch(".($page-1).");\">≪前へ</a>\n";
	}
	for($i=$page_min;$i<=$page_max;$i++){
		if($i==$page){
			$page_list .= "<span>".$i."</span>\n";
		}else{
			$page_list .= "<a href=\"javascript:pg_ch(".$i.")\">".$i."</a>\n";
		}
	}
	if($page < ceil($all_cnt/$limit)){
		$page_list .= "<a href=\"javascript:pg_ch(".($page+1).");\">次へ≫</a>\n";
	}
	
	return $page_list;
}
?>