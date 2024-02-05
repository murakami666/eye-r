<?php
ini_set('max_execution_time', 0);
ini_set('display_errors', 0);

require_once(__DIR__."/../common/Config.php");
require_once(__DIR__."/../common/Config_screaming.php");

require_once(BASE_ROOT."/common/func/func_brand_edit_log.php");

define('NOW',time());

//$startTime = microtime(true);

/***********************************
休日
***********************************/
$items = [];
$items['mode'] = 'cnt';
$items['holi_date'] = date('Y-m-d',NOW);
$cnt = 0;
$cnt = get_sc_holiday($items);

if($cnt > 0){
    exit();
}


/***********************************
銘柄取得
***********************************/

define('BRAND_TMP_URL','https://www.traders.co.jp/market_jp/stock_list/price/');

//取得カテゴリ
$barad_cate_page = ['tp','ts','tg','s','fk'];

$get_brand_flg = true;
$get_brand_list = [];

foreach((array)$barad_cate_page as $key => $value){

    $url_tmp = BRAND_TMP_URL.$value.'/all';

    //ページ数取得
    $url = $url_tmp."/1";
    $html = file_get_contents($url);


    $pattern = '/件表示中 \/ (.*)件/S';
    preg_match($pattern, $html, $matches);

    if(strlen($matches[1]) > 0){

        $max_num = (int)$matches[1];
        $max_page = ceil($max_num / 100);

        for($i=1;$i<=$max_page;$i++){

            $url = $url_tmp."/".$i;

            $response = mb_convert_encoding(file_get_contents($url), 'UTF-8', 'ASCII, JIS, UTF-8, EUC-JP, SJIS');
            $html = preg_replace('/<a .*?>|<\/a>|<br>/s', "", mb_convert_encoding($response, 'UTF-8', 'ASCII, JIS, UTF-8, EUC-JP, SJIS'));

            $domDocument = new DOMDocument();
            $domDocument->loadHTML($html);

            $xmlString = $domDocument->saveXML();
            $xmlObject = simplexml_load_string($xmlString);
            $arr = json_decode(json_encode($xmlObject), true);

            $list = $arr['body']['div']['2']['div']['1']['div']['div']['0']['div']['0']['div']['3']['div']['0']['div']['1']['table']['tbody']['tr'];

            $pattern = "/^(.*?)\((\d+)\/.*\)$/u";
            foreach($list as $key => $value){

                if(array_key_exists('td',$value)){

                    $data = $value['td'];

                    $matches = [];
                    preg_match($pattern, preg_replace('/ |\n/s','',$data[0]), $matches);

                    $get_brand_list[$matches[2]] = $matches[1];

                }
            }
        }

    }else{
        $get_brand_flg = false;
        break;
    }
}


if($get_brand_flg == true){
    /***********************************
    登録銘柄と比較
    ***********************************/
    
    //登録銘柄取得
    $now_brand_arr = [];
    $now_brand_arr = get_brand_list([]);

    $now_brand_list = [];
    foreach((array)$now_brand_arr as $key => $value){
        $now_brand_list[$value['brand_code']] = $value['brand_id'];
    }

    //-------------------------
    //追加銘柄取得
    //-------------------------
    
    $result = array_diff_key($get_brand_list, $now_brand_list);

    if(count($result) > 0){

        foreach((array)$result as $key => $value){

            $p_items = [];
            $p_items['brand_code'] = $key;
            $p_items['brand_name'] = $value;

            begin();
        
            $brand_id = regist_brand($p_items);
        
            $items = [];
            $items['log_type'] = 1;
            $items['log_brand_id'] = $brand_id;
            $items['log_b_brand_code'] = $p_items['brand_code'];
            $items['log_b_brand_name'] = $p_items['brand_name'];
            regist_brand_edit_log($items);
        
            commit();

        }
    }
    
    //-------------------------
    //削除銘柄取得
    //-------------------------
    $result = array_diff_key($now_brand_list,$get_brand_list);

    if(count($result) > 0){
    
        foreach((array)$result as $key => $value){

            $items = [];
            $items['brand_id'] = $value;
            $items['detail'] = 1;
            $brand_data = [];
            $brand_data = get_brand_list($items);

            begin();
            
            delete_brand($value);
        
            $items = [];
            $items['log_type'] = 3;
            $items['log_brand_id'] = $brand_data['brand_id'];
            $items['log_b_brand_code'] = $brand_data['brand_code'];
            $items['log_b_brand_name'] = $brand_data['brand_name'];
            regist_brand_edit_log($items);
        
            commit();
            

        }
    
    
    }
    
}


/***********************************
STOP安＆高取得
***********************************/
//STOP高
$url = 'https://kabutan.jp/warning/?mode=3_1';
$html = mb_convert_encoding(file_get_contents($url), 'HTML-ENTITIES', 'ASCII, JIS, UTF-8, EUC-JP, SJIS');

$domDocument = new DOMDocument();
$domDocument->loadHTML($html);

$xmlString = $domDocument->saveXML();
$xmlObject = simplexml_load_string($xmlString);
$arr = json_decode(json_encode($xmlObject), true);


$list = [];
$list = $arr['body']['div']['div']['2']['div']['0']['div']['4']['table']['tbody']['tr'];

$up_stop_list = [];
foreach((array)$list as $key => $value){
    if($key === 'td'){
        if($value['5']['span'] == "S"){
            $up_stop_list[] = $value['0']['a'];
        }
    }else{
        if($value['td']['5']['span'] == "S"){
            $up_stop_list[] = $value['td']['0']['a'];
        }
    }
}


//STOP安
$url = 'https://kabutan.jp/warning/?mode=3_2';
$html = mb_convert_encoding(file_get_contents($url), 'HTML-ENTITIES', 'ASCII, JIS, UTF-8, EUC-JP, SJIS');

$domDocument = new DOMDocument();
$domDocument->loadHTML($html);

$xmlString = $domDocument->saveXML();
$xmlObject = simplexml_load_string($xmlString);
$arr = json_decode(json_encode($xmlObject), true);


$list = [];
$list = $arr['body']['div']['div']['2']['div']['0']['div']['4']['table']['tbody']['tr'];

$dw_stop_list = [];
foreach((array)$list as $key => $value){
    if($key === 'td'){
        if($value['5']['span'] == "S"){
            $dw_stop_list[] = $value['0']['a'];
        }
    }else{
        if($value['td']['5']['span'] == "S"){
            $dw_stop_list[] = $value['td']['0']['a'];
        }
    }
}


/***********************************
分割・併合取得
***********************************/
$dm_pagetype_arr = [0,5];

$dm_code_arr = [];
foreach($dm_pagetype_arr as $value){
    for($i=1;$i<=100;$i++){
        
        $url = 'https://ca.image.jp/matsui/?type='.$value.'&word2=&word1=&sort=1&seldate=1&serviceDatefrom=&serviceDateto=&page='.$i;

        $html = mb_convert_encoding(file_get_contents($url), 'HTML-ENTITIES', 'ASCII, JIS, UTF-8, EUC-JP, SJIS');
    
        $domDocument = new DOMDocument();
        $domDocument->loadHTML($html);

        $xmlString = $domDocument->saveXML();
        $xmlObject = simplexml_load_string($xmlString);
        $arr = json_decode(json_encode($xmlObject), true);
        
        $list = [];
        $list = $arr['body']['section']['div']['1']['div']['div']['2'];

        if(!is_array($list)){
            break(1);
        }

        $list_2 = [];
        $list_2 = $list['table']['tbody']['tr'];

        foreach((array)$list_2 as $s_key => $s_value){

            if(isset($s_value['td'])){
                $dm_code_arr[] = $s_value['td']['1'];
            }
        }
    }
}

$dm_code_list = [];
if(count($dm_code_arr) > 0){    
    $dm_code_list = array_unique($dm_code_arr);
}


/***********************************
情報取得
***********************************/
$items = [];
//$items['brand_id'] = 893;
//$items['limit'] = 100;
$brand_list = [];
$brand_list = get_brand_list($items);

foreach($brand_list as $key => $value){
    
    $sc_data = [];
    $sc_data['sc_date'] = date('Y-m-d',NOW);
    $sc_data['sc_brand'] = $value['brand_id'];

    //------------------
    //4本値＆出来高
    //------------------
    $url = 'https://kabutan.jp/stock/?code='.$value['brand_code'];
    $html = mb_convert_encoding(file_get_contents($url), 'HTML-ENTITIES', 'ASCII, JIS, UTF-8, EUC-JP, SJIS');

    $domDocument = new DOMDocument();
    $domDocument->loadHTML($html);

    $xmlString = $domDocument->saveXML();
    $xmlObject = simplexml_load_string($xmlString);
    $arr = json_decode(json_encode($xmlObject), true);

    $list = [];
    $list = $arr['body']['div']['div']['2']['div']['0']['div']['2']['table'];

    $sc_data['sc_op'] = str_replace(',','',$list['0']['tbody']['tr']['0']['td']['0']);//始値
    $sc_data['sc_ep'] = str_replace(',','',$list['0']['tbody']['tr']['3']['td']['0']);//終値
    $sc_data['sc_hp'] = str_replace(',','',$list['0']['tbody']['tr']['1']['td']['0']);//高値
    $sc_data['sc_lp'] = str_replace(',','',$list['0']['tbody']['tr']['2']['td']['0']);//安値
    $sc_data['sc_vo'] = preg_replace("/\xC2\xA0/","",html_entity_decode(str_replace(['株',','],"",$list['1']['tbody']['tr']['0']['td'])));//出来高
    $sc_data['sc_ne'] = preg_replace("/\xC2\xA0/","",html_entity_decode(str_replace(['回',','],"",$list['1']['tbody']['tr']['3']['td'])));//約定回数
    $sc_data['sc_b_rate'] = str_replace(',','',$arr['body']['div']['div']['2']['div']['0']['section']['div']['0']['div']['1']['div']['0']['div']['1']['dl']['dd']['0']['span']);//前日比


    //------------------
    //乖離率(25,75,200日)
    //------------------
    $list = [];
    $list = $arr['body']['div']['div']['2']['div']['0']['div']['3']['div']['0']['table']['tbody']['tr']['2']['td'];

    $sc_data['sc_dr_25'] = str_replace(['+'],"",$list['1']['span']);//乖離率(25日)
    $sc_data['sc_dr_75'] = str_replace(['+'],"",$list['2']['span']);//乖離率(75日)
    $sc_data['sc_dr_200'] = str_replace(['+'],"",$list['3']['span']);//乖離率(200日)
    
    //print_r($sc_data);

    $stop_data = [];
    $stop_data['sc_up_stop'] = 0;
    $stop_data['sc_dw_stop'] = 0;

    if(in_array($value['brand_code'],$up_stop_list)){
        $stop_data['sc_up_stop'] = 1;
    }
    if(in_array($value['brand_code'],$dw_stop_list)){
        $stop_data['sc_dw_stop'] = 1;
    }

    begin();

    regist_sc_data($sc_data);
    update_sc_stop($value['brand_id'],date('Y-m-d',NOW),$stop_data);

    commit();
    
}

/***********************************
連続日数計算＆分割・併合
***********************************/

foreach($brand_list as $key => $value){
    //------------------
    //連続日数計算
    //------------------
    $items = [];
    $items['colimn_arr'] = ['sc_b_rate','sc_up_stop','sc_dw_stop'];
    $items['sc_brand'] = $value['brand_id'];
    $items['limit'] = 7;
    $arr = [];
    $arr = get_sc_data_list($items);

    $data = [];
    $data['brand_sc_pd_3'] = 0;
    $data['brand_sc_pd_4'] = 0;
    $data['brand_sc_pd_5'] = 0;
    $data['brand_sc_pu_3'] = 0;
    $data['brand_sc_pu_4'] = 0;
    $data['brand_sc_pu_5'] = 0;
    $data['brand_sc_up_stop_2'] = 0;
    $data['brand_sc_up_stop_3'] = 0;
    $data['brand_sc_dw_stop_2'] = 0;
    $data['brand_sc_dw_stop_3'] = 0;

    if(count($arr) >= 3){

        //連続
        $list = [];
        foreach((array)$arr as $s_key => $s_value){
            $list[] = $s_value['sc_b_rate'];
        }

        
        if($list[0] > 0){
            //up
            $count = 0;
            for ($i = 0; $i < count($list); $i++) {
                if ($list[$i] > 0) {
                    $count++;
                } else {
                    break;
                }
            }

            if($count >= 3){
                $data['brand_sc_pu_3'] = 1;
            }
            if($count >= 4){
                $data['brand_sc_pu_4'] = 1;
            }
            if($count >= 5){
                $data['brand_sc_pu_5'] = 1;
            }

        }if($list[0] < 0){

            //down
            $count = 0;
            for ($i = 0; $i < count($list); $i++) {
                if ($list[$i] < 0) {
                    $count++;
                } else {
                    break;
                }
            }

            if($count >= 3){
                $data['brand_sc_pd_3'] = 1;
            }
            if($count >= 4){
                $data['brand_sc_pd_4'] = 1;
            }
            if($count >= 5){
                $data['brand_sc_pd_5'] = 1;
            }
        }
        

        //STOP高
        $list = [];
        foreach((array)$arr as $s_key => $s_value){
            $list[] = $s_value['sc_up_stop'];
        }

        $count = 0;
        for ($i = 0; $i < count($list); $i++) {
            if ($list[$i] > 0) {
                $count++;
            } else {
                break;
            }
        }

        if($count >= 2){
            $data['brand_sc_up_stop_2'] = 1;
        }
        if($count >= 3){
            $data['brand_sc_up_stop_3'] = 1;
        }

        //STOP安
        $list = [];
        foreach((array)$arr as $s_key => $s_value){
            $list[] = $s_value['sc_dw_stop'];
        }

        $count = 0;
        for ($i = 0; $i < count($list); $i++) {
            if ($list[$i] > 0) {
                $count++;
            } else {
                break;
            }
        }

        if($count >= 2){
            $data['brand_sc_dw_stop_2'] = 1;
        }
        if($count >= 3){
            $data['brand_sc_dw_stop_3'] = 1;
        }
    }

    begin();

    update_brand_sc($value['brand_id'],$data);

    commit();


    //------------------
    //分割・併合
    //------------------
    if(count($dm_code_list) > 0){

        if(in_array($value['brand_code'],$dm_code_list)){

            $items = [];
            $items['sc_brand'] = $value['brand_id'];
            $sc_list = [];
            $sc_list = get_sc_data_list($items);
            
            $day_list = [];
            foreach($sc_list as $s_key => $s_value){
                $day_list[] = $s_value['sc_date'];
            }

            $day_list_last = $day_list[(count($day_list)-1)];

            for($i=1;$i<=10;$i++){

                //-----------------------
                //時系列取得
                //-----------------------
                $url = 'https://kabutan.jp/stock/kabuka?code='.$value['brand_code'].'&ashi=day&page='.$i;


                $html = mb_convert_encoding(file_get_contents($url), 'HTML-ENTITIES', 'ASCII, JIS, UTF-8, EUC-JP, SJIS');
        
                $domDocument = new DOMDocument();
                $domDocument->loadHTML($html);
        
                $xmlString = $domDocument->saveXML();
                $xmlObject = simplexml_load_string($xmlString);
                $arr = json_decode(json_encode($xmlObject), true);

                $list = [];
                $list = $arr['body']['div']['div']['2']['div']['0']['div']['5']['table']['1']['tbody']['tr'];

                if(count($list) > 0){
                    
                    foreach($list as $s_key => $s_value){
                        
                        $date = strtotime("20".$s_value['th']['time']);

                        if(strtotime($day_list_last) > $date){
                            break(2);
                        }

                        $sc_data = [];
                        $sc_data['sc_op'] = str_replace(',','',$s_value['td']['0']);//始値
                        $sc_data['sc_ep'] = str_replace(',','',$s_value['td']['3']);//終値
                        $sc_data['sc_hp'] = str_replace(',','',$s_value['td']['1']);//高値
                        $sc_data['sc_lp'] = str_replace(',','',$s_value['td']['2']);//安値
                        $sc_data['sc_vo'] = str_replace([','],"",$s_value['td']['6']);//出来高
                        $sc_data['sc_b_rate'] = str_replace(',','',$s_value['td']['4']['span']);//前日比

                        begin();

                        update_sc_data($value['brand_id'],date('Y-m-d',$date),$sc_data);

                        commit();

                    }
                }
            }
        }
    }
    
}



/***********************************
情報日付登録
***********************************/

begin();

regist_sc_day(date('Y-m-d',NOW));
delete_sc_day();

commit();

/***********************************
取集終了
***********************************/
begin();

update_ms_now_flg(0);

commit();




/*
$endTime = microtime(true);
$elapsedTime = $endTime - $startTime;
echo "<br>\n----------------------<br>\n処理にかかった時間: {$elapsedTime} 秒";
*/
?>
