<?php
require_once("Config.php");

require BASE_ROOT.'common/composer/vendor/autoload.php';
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;

$p_items = array();
$p_items = data_trim($_POST);

require_once("func/func_user_all_send_log.php");

//--------------------------------
//送信
//--------------------------------
if(strlen($p_items['send'])>0){

    
    $items = [];
    $items['sort'] = 'user_login_date';
    $user_list = [];
    $user_list = get_user_list($items);

    
    foreach((array)$user_list as $key => $value){

        //print $value['user_name'];
        
        $httpClient  = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(LINE_ME_TOKEN);
        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => LINE_ME_CLIENT_SECRET]);

        $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($p_items['send_text']);

        $response = $bot->pushMessage($value['user_line_id'], $textMessageBuilder);

    }
    

    //---------------------------
    //ログ登録
    //---------------------------
    begin();

    $items = [];
    $items['log_user_num'] = count($user_list);
    $items['log_text'] = $p_items['send_text'];
    regist_user_all_send_log($items);

    commit();

}

//--------------------------------
//確認画面
//--------------------------------
if(strlen($p_items['check'])>0){



}



?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="https://www.eye-r.com">
	<title>会員一斉送信</title>
    <link href="../style.css?t=<?= filemtime("../style.css");?>" rel="stylesheet">
    <link href="./style.css?t=<?= filemtime("./style.css");?>" rel="stylesheet">
    <script type="text/javascript" src="../script.js?t=<?= filemtime("../script.js")?>"></script>
</head>
<body>
    <div class="base">
<? require_once("../tmp_re_link.php")?>
        <h3>会員一斉送信</h3>
<? if(strlen($p_items['send'])>0){ ?>
        <p class="resu_text">
            送信しました<br>
        </p>
<? }elseif(strlen($p_items['check'])>0){ ?>
        <form action="./" method="post" id="edit_form">
            <table border="0" cellspacing="1" cellpadding="0" class="base_table edit_table">
                <tr>
                    <th>送信内容</th>
                    <td><?= nl2br($p_items['send_text'])?></td>
                </tr>
            </table>
            <input name="send" type="submit" id="send" value="送信する"  onClick="return sendCheck(this)"/></td>
            <input type="hidden" name="send_text" value="<?= $p_items['send_text']?>">
        </form>

<? }else{ ?>
        <form action="./" method="post" id="edit_form">
            <table border="0" cellspacing="1" cellpadding="0" class="base_table edit_table">
                <tr>
                    <th>送信内容</th>
                    <td><textarea name="send_text" rows="10"></textarea></td>
                </tr>
            </table>
            <input name="check" type="submit" id="check" value="確認画面へ進む"/></td>
        </form>
<? } ?>
    </div>
</body>
</html>