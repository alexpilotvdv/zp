<?php
 /**
 * Обработчик формы авторизации
 * Site: http://bezramok-tlt.ru
 * Авторизация пользователя
 */

 //Ключ защиты
 if(!defined('BEZ_KEY'))
 {
     header("HTTP/1.1 404 Not Found");
     exit(file_get_contents('../../404.html'));
 }
 if($mode=="resetpass"){
  // echo"reset";
   $newpass=gen_password();
   echo $newpass;
   //Проверяем ключ
   $sql = 'SELECT *
       FROM `'. BEZ_DBPREFIX .'reg`
       WHERE `active_hex` = "'. escape_str($_GET['key']) .'"';
   $res = mysqlQuery($sql);
   if(mysql_num_rows($res) > 0){
     $row = mysql_fetch_assoc($res);
     $mailto=$row['login'];
     //Солим пароль
    $newpassmd = md5(md5($newpass).$row['salt']);
     //обновим пароль
     $sql = 'UPDATE `'. BEZ_DBPREFIX .'reg`
 				SET `pass` ="'.$newpassmd.'"
 				WHERE `login` = "'. $row['login'] .'"';
        $res = mysqlQuery($sql);
   }

   $title = 'Регистрация на сервис записи на полеты';
   $message = '<p>Ваш новый пароль: '.$newpass;
   sendMessageMail($mailto, BEZ_MAIL_AUTOR, $title, $message);
   //добавить код редиректа
  // header('Location:'. BEZ_HOST .'?mode=auth');
 }

//если нажата кнопка сброса пароля
if(isset($_POST['submitpass'])){
  if(empty($_POST['email']))
		$err[] = 'Не введен Логин';
    if(count($err) > 0)
  		echo showErrorMessage($err);
  	else{
  $sql = 'SELECT *
      FROM `'. BEZ_DBPREFIX .'reg`
      WHERE `login` = "'. escape_str($_POST['email']) .'"
      AND `status` = 1';
  $res = mysqlQuery($sql);
  if(mysql_num_rows($res) > 0)
  {
    //Получаем данные из таблицы
    $row = mysql_fetch_assoc($res);
$title = 'Регистрация на сервис записи на полеты';
$url = BEZ_HOST .'?mode=resetpass&key='. md5($row['salt']);
$message = '<p>Для сброса пароля пройдите по ссылке <a href="' . $url . '">' . $url . '</a>';
sendMessageMail($_POST['email'], BEZ_MAIL_AUTOR, $title, $message);
  }
}
}
 //Если нажата кнопка то обрабатываем данные
 if(isset($_POST['submit']))
 {
	if(empty($_POST['email']))
		$err[] = 'Не введен Логин';

	if(empty($_POST['pass']))
		$err[] = 'Не введен Пароль';

	//Проверяем наличие ошибок и выводим пользователю
	if(count($err) > 0)
		echo showErrorMessage($err);
	else
	{
		/*Создаем запрос на выборку из базы
		данных для проверки подлиности пользователя*/
		$sql = 'SELECT *
				FROM `'. BEZ_DBPREFIX .'reg`
				WHERE `login` = "'. escape_str($_POST['email']) .'"
				AND `status` = 1';
		$res = mysqlQuery($sql);


		//Если логин совподает, проверяем пароль
		if(mysql_num_rows($res) > 0)
		{
			//Получаем данные из таблицы
			$row = mysql_fetch_assoc($res);

			if(md5(md5($_POST['pass']).$row['salt']) == $row['pass'])
			{
				$_SESSION['user'] = true;
        $_SESSION['user_name'] = escape_str($_POST['email']);

				//Сбрасываем параметры
				header('Location:'. BEZ_HOST .'?mode=auth');
				exit;
			}
			else
				echo showErrorMessage('Неверный пароль!');
		}
		else
			echo showErrorMessage('Логин <b>'. $_POST['email'] .'</b> не найден!');
	}

 }

?>
