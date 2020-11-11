<?php

 if(!defined('BEZ_KEY'))
 {
     header("HTTP/1.1 404 Not Found");
     exit(file_get_contents('../../404.html'));
 }
 if($user === true){

     //если пользователь админ
    $objTable = new \smartTable('Фото','Имя','email','Телефон','Статус','Активирован');
     
     $sql = 'SELECT *
         FROM `'. BEZ_DBPREFIX .'reg` LEFT JOIN `'. BEZ_DBPREFIX .'profile`
         ON `id` = `pr_id_reg` LEFT JOIN `'. BEZ_DBPREFIX .'foto`
         ON `id` = `ft_id_reg`';
     $res = mysqlQuery($sql);
     if(mysql_num_rows($res) > 0){
       echo'<div class="container">';
       while ($row = mysql_fetch_assoc($res)) {
         if($row['ft_img']==null){
           $tpimg="нет фото";
         } else{
           $tpimg='<img src="' . BEZ_HOST . 'imgobj/' . $row['ft_img'] . '">';
         }
$objTable->insertrow($tpimg,  $row['pr_name'], $row['login'], $row['pr_other'], $row['pr_status'], $row['status']);

       }
       $objTable->showtable();
      echo "</div>";
   }

 }

 ?>
