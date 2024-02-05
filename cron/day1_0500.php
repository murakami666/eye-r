<?php
/***********************************
休日取得
***********************************/

require_once(__DIR__."/../common/Config.php");
require_once(__DIR__."/../common/Config_screaming.php");

$url = 'https://www.jpx.co.jp/corporate/about-jpx/calendar/index.html';

$html = mb_convert_encoding(file_get_contents($url), 'UTF-8', 'ASCII, JIS, UTF-8, EUC-JP, SJIS');


$dom = new DOMDocument;
$dom->loadHTML($html);


$holiday_list = [];

$tables = $dom->getElementsByTagName('table');
foreach ($tables as $table) {
    if ($table->getAttribute('class') === 'overtable') {
        // "overtable"クラス内の日付部分を抜き出す
        $rows = $table->getElementsByTagName('tr');
        foreach ($rows as $row) {
            $cells = $row->getElementsByTagName('td');
            if ($cells->length === 2) {
                $dateCell = $cells->item(0)->nodeValue;
                $cleanedDate = str_replace(['（日）', '（月）', '（火）', '（水）', '（木）', '（金）', '（土）'], '', $dateCell);
                $holiday_list[] = $cleanedDate;
            }
        }
    }
}

if(count($holiday_list) > 0){

    begin();

    //一旦削除
    delete_sc_holiday();
    
    foreach((array)$holiday_list as $key => $value){
        regist_sc_holiday($value);
    }
    
    commit();

}

?>
