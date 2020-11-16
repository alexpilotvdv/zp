<?php
if(!defined('BEZ_KEY'))
{
    header("HTTP/1.1 404 Not Found");
    exit(file_get_contents('../../404.html'));
}

if($user === true){
  $objTable = new smartTable('Доступные для записи дни');
  $sql = 'SELECT *
      FROM `'. BEZ_DBPREFIX .'day_for_flight`
      WHERE `day_status` = 1';
  $res = mysqlQuery($sql);
  if(mysql_num_rows($res) > 0){
    echo'<div class="container">';
    while ($row = mysql_fetch_assoc($res)) {
      $tp_ref = '<a href="?mode=zapis&idday=' . $row['day_id'] .'">' . $row['day_data'] . '</a>';
      $objTable->insertrow($tp_ref);
    }
    $objTable->showtable();
      echo "</div>";
}
}
 ?>
