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
        echo $sql;
      $res = mysqlQuery($sql);
      header('Location:'. BEZ_HOST .'admin/?mode=records');
}
} else {
  include 'formaddday.html';
}


//$date = explode('-', $_POST['data']);
//$new_date = $date[2].'-'.$date[1].'-'.$date[0];
//echo $new_date;
 ?>
