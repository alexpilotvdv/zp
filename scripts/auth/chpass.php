<?php
 /**
 * Скрипт распределения ресурсов
 * Site: http://bezramok-tlt.ru
 * Проверяем права на чтение данных,
 * только для зарегистрированных пользователей
 */

 //Ключ защиты
 if(!defined('BEZ_KEY'))
 {
     header("HTTP/1.1 404 Not Found");
     exit(file_get_contents('../../404.html'));
 }

 //Проверяем зашел ли пользователь
 if($user === false)
 	echo '<h3>Доступ закрыт, Вы не вошли в систему!</h3>'."\n";

 if($user === true)
 {
    if(isset($_POST['submit'])){
//если отправлена форма
if(isset($_POST['tekpass']))
{
 //проверим есть ли такой в базе
 $sql = 'SELECT *
     FROM `'. BEZ_DBPREFIX .'reg`
     WHERE `login` = "'. escape_str($user_name) .'"
     AND `status` = 1';
 $res = mysqlQuery($sql);
 //Если логин совподает, проверяем пароль
 if(mysql_num_rows($res) > 0)
 {
   //Получаем данные из таблицы
   $row = mysql_fetch_assoc($res);
   $salt=$row['salt'];
  // echo md5(md5($_POST['pass']).$row['salt']);
   if(md5(md5($_POST['tekpass']).$row['salt']) != $row['pass']){
   $err[] = 'Текущий пароль не правильный';
   }
 }


}
else{
$err[] = 'Текущий пароль не введен';
}
if(empty($_POST['pass']))
  $err[] = 'Поле Пароль не может быть пустым';

  if(empty($_POST['pass2']))
    $err[] = 'Поле Подтверждения пароля не может быть пустым';
  if($_POST['pass'] != $_POST['pass2'])
    $err[] = 'Пароли не совподают';
    if(count($err) > 0){
    echo showErrorMessage($err);
  }
    else{
      //all ok
      $newpassmd = md5(md5($_POST['pass']).$salt);
       //обновим пароль
       $sql = 'UPDATE `'. BEZ_DBPREFIX .'reg`
          SET `pass` ="'.$newpassmd.'"
          WHERE `login` = "'. $user_name .'"';
          $res = mysqlQuery($sql);
     header('Location:'. BEZ_HOST .'?mode=exit');
    }
    }

}
///////////////////////////////////////////////


	?>
