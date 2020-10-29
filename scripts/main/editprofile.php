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
	echo '<h3>Профиль</h3>'."\n";
  echo '<h4>' . $user_name . '</h4>'."\n";
  $objuser=new user($user_name);
  if(isset($_POST['mode'])){
     if($_POST['mode']=="addzapis"){
        //echo $_POST['name'];
        if(isset($_POST['name'])){
        //записать в базу
        $objuser->recordname($_POST['name'],"---");
        header('Location:'. BEZ_HOST .'?mode=profile');
        }
        else {echo"Имя не может быть пустым"; }
      }
    }
 }

 ?>
