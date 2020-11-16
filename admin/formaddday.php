<?php
if(isset($_POST['mode'])){
if($_POST['action']=='new'){
  //добавим запись в базу
  $sql='INSERT INTO `'. BEZ_DBPREFIX .'day_for_flight`
        VALUES(
          "",
          "'. $_POST['data'] .'",
          0,
          "'. $_POST['data'] . ' ' . $_POST['timestart'] .':00",
          "'. $_POST['data'] . ' ' . $_POST['timeend'] .':00",
          "'. $_POST['timemax'] .'",
          "'. $_POST['info'] .'",
          '. $id_user .'
          )';
        //echo $sql;
      $res = mysqlQuery($sql);
      header('Location:'. BEZ_HOST .'admin/?mode=records');
} elseif ($_POST['action']=='change_day') {
  $tp_da = (isset($_POST['day_active'])) ? 1 : 0 ;
$sql='UPDATE `'. BEZ_DBPREFIX .'day_for_flight`
        SET `day_status` = ' . $tp_da . ',
            `day_start` = "' . $_POST['data'] . ' ' . $_POST['timestart'] .':00",
            `day_end` = "' . $_POST['data'] . ' ' . $_POST['timeend'] .':00",
            `day_total_time` = "' . $_POST['timemax'] . '",
            `day_info` = "' . $_POST['info'] .'",
            `day_user_create` = ' . $id_user . '
             WHERE `day_id` = ' . $_POST['idrecord'] . '';
             echo $sql;
          $res = mysqlQuery($sql);
          header('Location:'. BEZ_HOST .'admin/?mode=records');
}  else {
  include 'formaddday.html';
}
}
?>
