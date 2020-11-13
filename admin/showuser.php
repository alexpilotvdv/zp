<?php

 //Ключ защиты
 if (!defined('BEZ_KEY')) {
     header("HTTP/1.1 404 Not Found");
     exit(file_get_contents('../../404.html'));
 }

 //Проверяем зашел ли пользователь
 if ($user === false) {
     echo '<h3>Доступ закрыт, Вы не вошли в систему!</h3>'."\n";
 }
 if ($user === true) {
     if ($user_admin===true) {
         //если пользователь админ
         $objTable = new smartTable('Фото', 'Имя', 'email', 'Телефон', 'Статус', 'Активирован');
         echo "<br>Status admin Ok<br>";
         $sql = 'SELECT *
         FROM `'. BEZ_DBPREFIX .'reg` LEFT JOIN `'. BEZ_DBPREFIX .'profile`
         ON `id` = `pr_id_reg` LEFT JOIN `'. BEZ_DBPREFIX .'foto`
         ON `id` = `ft_id_reg`';
         $res = mysqlQuery($sql);
         if (mysql_num_rows($res) > 0) {
             echo'<div class="container">';
             //определим количество
             $tp_col=mysql_num_rows($res);
             //определена ли страница
             if (isset($_GET['page'])) {
                 $tp_first_page = PAGE_MAX * ($_GET['page'] - 1);
                 $sql .=' LIMIT ' . $tp_first_page . ', ' . PAGE_MAX;
             } else {
                 //страница не определена, вывести первые записи
                 $sql .=' LIMIT 0, ' . PAGE_MAX;
             }
             $res = mysqlQuery($sql);
             while ($row = mysql_fetch_assoc($res)) {
                 if ($row['ft_img']==null) {
                     $tpimg="нет фото";
                 } else {
                     $tpimg='<img src="' . BEZ_HOST . 'imgobj/' . $row['ft_img'] . '">';
                 }
                 //соберем ссылку для активации
                 if ($row['status']==0) {
                     $tpstatus='<a href="?mode=activate&id=' . $row['id'] . '">Активировать</a>';
                 } else {
                     $tpstatus="Активирован";
                 }
                 //$tp_href='<a href="?mode=ch_status&id=' . $row['id'] .'">' . $row['pr_status'] . '</a>';
                 //кнопка запуска модального окна
                 $tp_href='<button type="button"
class="btn btn-primary"
data-toggle="modal" data-target="#exampleModal"
data-whatever="' . $row['id'] . '" data-role="' . $row['pr_status'] . '">' . $row['pr_status'] . '</button>';
                 $objTable->insertrow($tpimg, $row['pr_name'], $row['login'], $row['pr_other'], $tp_href, $tpstatus);
             }
             if ($tp_col - (PAGE_MAX * (int)($tp_col / PAGE_MAX)) > 0) {
                 $tp_vsego_pages = (int)($tp_col / PAGE_MAX) + 1;
             } else {
                 $tp_vsego_pages = (int)($tp_col / PAGE_MAX);
             }
             $objTable->show_pages((isset($_GET['page']) ? $_GET['page'] : 1), $tp_vsego_pages, 'mode=users');
             $objTable->showtable();
             echo "</div>";
             ////
         }
     }
 }
