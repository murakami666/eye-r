<?php
require_once(__DIR__."/../common/Config.php");

require BASE_ROOT.'common/composer/vendor/autoload.php';
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;

sleep(1);

define("NOW",strtotime(date("Y-m-d H:i:00",time())));

define("TMP_GET_URL",'https://www.release.tdnet.info/inbs/I_list_');

$page_flg = true;

$row_list = [];
for($i=1;$i<=100;$i++){

    $get_url = TMP_GET_URL.sprintf("%03d_%s.html",$i,date("Ymd",NOW));
    
    if(url_exists($get_url)){

        $list = get_url_data($get_url);

        if(count($list) > 0){
            foreach($list as $key => $value){
                if($value['time'] == date("H:i",NOW)){
                    $row_list[] = $value;
                }else{
                    break(2);
                }
            }
        }
    }else{
        break;
    }
}


if(count($row_list) > 0){
    
    begin();

    foreach($row_list as $key => $value){

        $items = [];
        $items['data_date'] = date("Y-m-d",NOW);
        $items['data_time'] = $value['time'];
        $items['data_code'] = $value['code'];
        $items['data_name'] = $value['name'];
        $items['data_text'] = $value['text'];
        $items['data_link'] = $value['link'];

        regist_data($items);

    }

    commit();
    
    
    $items = [];
    $items['data_date'] = date("Y-m-d",NOW);
    $items['data_time'] = date("H:i:s",NOW);
    $items['join'] = ['brand'];
    $data_list = [];
    $data_list = get_data_list($items);

    if(count($data_list) > 0){


        $data_brand_list = [];
        $data_brand_id_list = [];

        foreach((array)$data_list as $key => $value){
        
            $data_brand_list[$value['brand_id']] = $value;
            $data_brand_id_list[] = $value['brand_id'];
        
        }

        $user_all_list = [];
        $user_all_list = get_user_brand_brand_group_list($data_brand_id_list);

        if(count($user_all_list) > 0){
            
            $user_list = [];
            $user_brand_id_list = [];
            foreach((array)$user_all_list as $key => $value){
                $user_list[$value['user_id']] = $value;
                $user_brand_id_list[$value['user_id']][] = $value['ub_brand'];
            }

            if(count($user_list) > 0){
                foreach((array)$user_list as $key => $value){

                    $text = "";
                    $cnt = 0;
                    foreach((array)$user_brand_id_list[$value['user_id']] as $s_key => $s_value){
                        if($cnt >= 5){break;}
                        $text .= trim($data_brand_list[$s_value]['data_name'])."(".$data_brand_list[$s_value]['data_code'].")\n";
                        $cnt++;
                    }

                    $text = trim($text);

                    if(strlen($text) > 0 && strlen($value['user_line_id']) > 0){

                        $text = "▼".date("n月j日 H:i",NOW)."\n".trim($text)."\n\n▼eyeRで確認\n".BASE_URL.date("Ymd",NOW)."/";

                        $httpClient  = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(LINE_ME_TOKEN);
                        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => LINE_ME_CLIENT_SECRET]);

                        $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($text);

                        $response = $bot->pushMessage($value['user_line_id'], $textMessageBuilder);

                        
                    }

                }
            }
        }

    }
    
}

?>