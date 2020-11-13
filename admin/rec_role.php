<?php
$sql = 'UPDATE `'. BEZ_DBPREFIX .'profile`
   SET `pr_status` ='.$_POST['status'].'
   WHERE `pr_id_reg` = '. $_POST['id'] .'';
   $res = mysqlQuery($sql);
   //echo $sql;
header('Location:'. BEZ_HOST .'admin/?mode=users');
