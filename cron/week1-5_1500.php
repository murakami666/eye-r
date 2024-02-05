<?php
require_once(__DIR__."/../common/Config.php");
require_once(__DIR__."/../common/Config_screaming.php");

define('NOW',time());

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
取集開始
***********************************/
begin();

update_ms_now_flg(1);

commit();

?>
