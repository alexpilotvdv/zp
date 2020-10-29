<?php
 /**
 * Скрипт распределения ресурсов
 * Site: http://bezramok-tlt.ru
 * Проверяем права на чтение данных,
 * только для зарегистрированных пользователей
 */
class user{
  private $id;
  private $name;
  private $other;

  public function __construct($user_name){
        $sql = 'SELECT * FROM `'. BEZ_DBPREFIX .'reg` WHERE `login` = "'. $user_name .'"';
      //  echo $sql;
        $res = $this->mysqlQuery($sql);
    		if(mysql_num_rows($res) > 0){
          $row = mysql_fetch_assoc($res);
    			$this->id = $row['id'];
          echo "id=".$this->id." ";
            $sql = 'SELECT * FROM `'. BEZ_DBPREFIX .'profile` WHERE `pr_id_reg` = "'. $this->id .'"';
            $res = $this->mysqlQuery($sql);
               if(mysql_num_rows($res) > 0){
//профиль заполнен
               }
               else{
                 echo"no record profile";
                 //вывести форму ввода данных в профиль
               }
        }
        else{
          echo"no record";
        }
  }

  public function mysqlQuery($sql)
  {
 	$res = mysql_query($sql);
 	/* Проверяем результат
 	Это показывает реальный запрос, посланный к MySQL, а также ошибку. Удобно при отладке.*/
 	if(!$res)
 	{
 		$message  = 'Неверный запрос: ' . mysql_error() . "\n";
 		$message .= 'Запрос целиком: ' . $sql;
 		die($message);
 	}

 	return $res;
  }
}
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
 }

 ?>
