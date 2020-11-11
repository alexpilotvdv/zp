<?php

 //Ключ защиты
 if(!defined('BEZ_KEY'))
 {
     header("HTTP/1.1 404 Not Found");
     exit(file_get_contents('../../404.html'));
 }

 //Проверяем зашел ли пользователь
 if($user === false)
 	echo '<h3>Доступ закрыт, Вы не вошли в систему!</h3>'."\n";
 if($user === true){
   if($user_admin===true){
     //если пользователь админ
    $objTable = new smartTable('Фото','Имя','email','Телефон','Статус','Активирован');
     echo "<br>Status admin Ok<br>";
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
         //соберем ссылку для активации
         if($row['status']==0){
           $tpstatus='<a href="?mode=activate&id=' . $row['id'] . '">Активировать</a>';
         } else{
           $tpstatus="Активирован";
         }

$objTable->insertrow($tpimg,  $row['pr_name'], $row['login'], $row['pr_other'], $row['pr_status'], $tpstatus);

       }
       $objTable->showtable();
      echo "</div>";
   }
   }
 }

 ?>
