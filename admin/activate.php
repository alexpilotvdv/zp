<?php
//Ключ защиты
if (!defined('BEZ_KEY')) {
		header("HTTP/1.1 404 Not Found");
		exit(file_get_contents('404.html'));
}

	if($user === true){
$sql = 'UPDATE `'. BEZ_DBPREFIX .'reg`
   SET `status` = 1
   WHERE `id` = "'. $_GET['id'] .'"';
   $res = mysqlQuery($sql);
header('Location:'. BEZ_HOST .'admin/?mode=users');
//получим адрес почты
$sql = 'SELECT *
    FROM `'. BEZ_DBPREFIX .'reg`
    WHERE `id` = "'. $_GET['id'] .'"';
$res = mysqlQuery($sql);
if(mysql_num_rows($res) > 0){
  $row = mysql_fetch_assoc($res);


//Отправляем письмо для активации
$title = 'Ваш аккаунт успешно активирован';
$message = 'Поздравляю Вас, Ваш аккаунт  успешно активирован';

sendMessageMail($row['login'], BEZ_MAIL_AUTOR, $title, $message);
}
}

 ?>
