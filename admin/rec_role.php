<?php
if (!defined('BEZ_KEY')) {
    header("HTTP/1.1 404 Not Found");
    exit(file_get_contents('404.html'));
}
$sql = 'UPDATE `'. BEZ_DBPREFIX .'profile`
   SET `pr_status` ='.$_POST['status'].'
   WHERE `pr_id_reg` = '. $_POST['id'] .'';
   $res = mysqlQuery($sql);
   //echo $sql;
header('Location:'. BEZ_HOST .'admin/?mode=users');
?>
