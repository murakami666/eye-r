<?php

require_once(__DIR__."/../common/Config.php");

begin();

delete_old_data();

commit();

?>