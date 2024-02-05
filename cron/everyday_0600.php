<?php

require_once(__DIR__."/../common/Config.php");

require_once(BASE_ROOT."common/func/func_brand_edit_log.php");
require_once(BASE_ROOT."common/func/func_sc_disp_log.php");

begin();

delete_brand_edit_log();
delete_sc_disp_log();

commit();

?>