 <?php
 /**
 * Файл с пользовательскими функциями
 * Site: http://bezramok-tlt.ru
 * Регистрация пользователя письмом
 */

  //Ключ защиты
 if(!defined('BEZ_KEY'))
 {
     header("HTTP/1.1 404 Not Found");
     exit(file_get_contents('../404.html'));
 }

 /**Функция экранирования вносимых данных
 *@param array $data
 */
 function escape_str($data)
 {
    if(is_array($data))
    {
        if(get_magic_quotes_gpc())
           $strip_data = array_map("stripslashes", $data);
           $result = array_map("mysql_real_escape_string", $strip_data);
           return  $result;
    }
    else
    {
        if(get_magic_quotes_gpc())
           $data = stripslashes($data);
           $result = mysql_real_escape_string($data);
           return $result;
    }
 }

 /**Отпровляем сообщение на почту
 * @param string  $to
 * @param string  $from
 * @param string  $title
 * @param string  $message
 */
 function sendMessageMail($to, $from, $title, $message)
 {
   //Адресат с отправителем
   //$to = $to;
   //$from = $from;

   //Формируем заголовок письма
   $subject = $title;
   $subject = '=?utf-8?b?'. base64_encode($subject) .'?=';

   //Формируем заголовки для почтового сервера
   $headers = "Content-type: text/html; charset=\"utf-8\"\r\n";
   $headers .= "From: ". $from ."\r\n";
   $headers .= "MIME-Version: 1.0\r\n";
   $headers .= "Date: ". date('D, d M Y h:i:s O') ."\r\n";

   //Отправляем данные на ящик админа сайта
   if(!mail($to, $subject, $message))
      return 'Ошибка отправки письма!';
   else
      return true;
 }

  /**функция вывода ошибок
  * @param array  $data
  */
 function showErrorMessage($data)
 {
    $err = '<ul>'."\n";

	if(is_array($data))
	{
		foreach($data as $val)
			$err .= '<li style="color:red;">'. $val .'</li>'."\n";
	}
	else
		$err .= '<li style="color:red;">'. $data .'</li>'."\n";

	$err .= '</ul>'."\n";

    return $err;
 }

  /**Простая обертка для запросов к MySQL
  * @param string  $sql
  */
 function mysqlQuery($sql)
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

 /**Простой генератор соли
 * @param string  $sql
 */
 function salt()
 {
	$salt = substr(md5(uniqid()), -8);
	return $salt;
 }


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
                  //**************
                  print"  <FORM ENCTYPE=\"multipart/form-data\" ACTION=\"index.php\" METHOD=\"POST\">";
                  print"  <input type=\"hidden\" name=\"mode\" value=\"addzapis\">";
                  print"  <input type=\"hidden\" name=\"kod\" value=\"$this->id\">";
                  print" <p>Введите имя</p>";
                  print"  <input type=\"text\" name=\"name\" size=\"42\"><br>";
                  print" <p>Введите описание</p>";
                  print"  <input type=\"text\" name=\"other\" size=\"42\"><br>";
                  print"  <INPUT TYPE=\"submit\" VALUE=\"Загрузить\">";
                  print"</FORM>";
                  //**************
                }
         }
         else{
           echo"no record";
         }
   }
public function recordname($name,$other){
  //запись в базу имени $query = "INSERT INTO foto VALUES (\"$kod\",\"$identificatorimg\",\"$opisfoto\")";
  //$query = 'INSERT INTO '. BEZ_DBPREFIX .'profile VALUES ("'.$this->id.'","'.$name.'","'.$other.'")';
  $sql = 'INSERT INTO `'. BEZ_DBPREFIX .'profile`
      VALUES(
          "",
          "'. $this->id .'",
          "'. $name .'",
          "'. $other .'"
          )';


  //echo $query;
  $res = $this->mysqlQuery($sql);
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
