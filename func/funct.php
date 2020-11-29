 <?php
 /**
 * Файл с пользовательскими функциями
 * Site: http://bezramok-tlt.ru
 * Регистрация пользователя письмом
 */

 function img_resize($src, $dest, $width, $height, $rgb=0xFFFFFF, $quality=100){
   if (!file_exists($src)) return false;

   $size = getimagesize($src);

   if ($size === false) return false;

   // Определяем исходный формат по MIME-информации, предоставленной
   // функцией getimagesize, и выбираем соответствующую формату
   // imagecreatefrom-функцию.
   $format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
   $icfunc = "imagecreatefrom" . $format;
   if (!function_exists($icfunc)) return false;

   $x_ratio = $width / $size[0];
   $y_ratio = $height / $size[1];

   $ratio       = min($x_ratio, $y_ratio);
   $use_x_ratio = ($x_ratio == $ratio);

   $new_width   = $use_x_ratio  ? $width  : floor($size[0] * $ratio);
   $new_height  = !$use_x_ratio ? $height : floor($size[1] * $ratio);
   $new_left    = $use_x_ratio  ? 0 : floor(($width - $new_width) / 2);
   $new_top     = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);

   $isrc = $icfunc($src);
   $idest = imagecreatetruecolor($width, $height);

   imagefill($idest, 0, 0, $rgb);
   imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0,
     $new_width, $new_height, $size[0], $size[1]);

   imagejpeg($idest, $dest, $quality);

   imagedestroy($isrc);
   imagedestroy($idest);

   return true;

 }

 function resize($image, $w_o = false, $h_o = false) {
    if (($w_o < 0) || ($h_o < 0)) {
      echo "Некорректные входные параметры";
      return false;
    }
    list($w_i, $h_i, $type) = getimagesize($image); // Получаем размеры и тип изображения (число)
    $types = array("", "gif", "jpeg", "png"); // Массив с типами изображений
    $ext = $types[$type]; // Зная "числовой" тип изображения, узнаём название типа
    if ($ext) {
      $func = 'imagecreatefrom'.$ext; // Получаем название функции, соответствующую типу, для создания изображения
      $img_i = $func($image); // Создаём дескриптор для работы с исходным изображением
    } else {
      echo 'Некорректное изображение'; // Выводим ошибку, если формат изображения недопустимый
      return false;
    }
    /* Если указать только 1 параметр, то второй подстроится пропорционально */
    if (!$h_o) $h_o = $w_o / ($w_i / $h_i);
    if (!$w_o) $w_o = $h_o / ($h_i / $w_i);
    $img_o = imagecreatetruecolor($w_o, $h_o); // Создаём дескриптор для выходного изображения
    imagecopyresampled($img_o, $img_i, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i); // Переносим изображение из исходного в выходное, масштабируя его
    $func = 'image'.$ext; // Получаем функция для сохранения результата
    return $func($img_o, $image); // Сохраняем изображение в тот же файл, что и исходное, возвращая результат этой операции
  }

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

   $headers = "Content-type:text/html; charset=\"utf-8\"\r\n";
   $headers .= "From:". $from ."\r\n";
   $headers .= "MIME-Version: 1.0\r\n";
   $headers .= "Date: ". date('D, d M Y h:i:s O') ."\r\n";

   $messagehtml="<html> <head> <meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" /></head><body>".$message."</body></html>";
   //Отправляем данные на ящик админа сайта
   if(!mail($to, $subject, $messagehtml,$headers))
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

 ////для вывода элементов в таблицу
 class smartTable{
   private $rez;//здесь будет собираться код таблицы
   private $pages;
   public function __construct(){
       //формирует заголовок таблицы
       $this->rez = '<table class="table table-striped">
                      <thead>
                        <tr>';
       $this->pages = '';
       $args = func_get_args();
       foreach ($args as $arg) {
         $this->rez .= '<th scope="col">' . $arg . '</th>';
         }
       $this->rez .=	'</tr> </thead><tbody>';
   }
   public function insertrow(){
     //вставляет строку в таблицу
     $args = func_get_args();
     $this->rez .='<tr>';
     foreach ($args as $arg) {
       $this->rez .= '<td>' . $arg . '</td>';
       }
       $this->rez .='</tr>';
   }
   public function showtable(){
     $this->rez .='</tbody>
                   </table>';
    $this->rez .= $this->pages;
                 echo $this->rez;
   }
   public function show_pages($curr_page,$total_page,$action){
     $tp_pages='';
     $tp_pages .= '<nav aria-label="...">
  <ul class="pagination">';
  //пока делаю страницы все без кнопок пердыдущая, следующая
  for ($i=1;$i<=$total_page;$i++){
    if($i==$curr_page){
      $tp_pages .='<li class="page-item disabled">';
      $tp_pages .='<li class="page-item active">';
    } else{
      $tp_pages .= '<li class="page-item">';
    }

      $tp_pages .='<a class="page-link" href="?' . $action . '&page=' . $i . '">' . $i . '</a>';

    if($i==$curr_page){
        $tp_pages .='</li>';
        $tp_pages .='</li>';
      } else{
        $tp_pages .= '</li>';
      }
  }

  $tp_pages .='</ul>
     </nav>';
     $this->pages=$tp_pages;
   }

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
           $row = mysql_fetch_assoc($res); //while ($row = $result->fetch_assoc())
           //заполним переменные из базы
     			 $this->id = $row['id'];
           echo "id=".$this->id." ";
             $sql = 'SELECT * FROM `'. BEZ_DBPREFIX .'profile` WHERE `pr_id_reg` = "'. $this->id .'"';
             $res = $this->mysqlQuery($sql);
                if(mysql_num_rows($res) > 0){
 //профиль заполнен, заполним переменные объекта из базы
                   $res = $this->mysqlQuery($sql);
                   $row = mysql_fetch_assoc($res);
                   $this->name = $row['pr_name'];
                   $this->other = $row['pr_other'];
                   //вывести форму в режиме правки
                   $this->showform("editform");
                }
                else{
                  $this->name = '';
                  $this->other = '';
                  echo"no record profile";
                  //вывести форму ввода данных в профиль
                  //**************
                  $this->showform("addzapis");

                  //**************
                }
         }
         else{
           echo"no record, not register";
         }
   }
/////функция для вывода формы
public function showform($action){
  print"  <FORM ENCTYPE=\"multipart/form-data\" ACTION=\"index.php\" METHOD=\"POST\">";
  echo '<div class="col-sm-3 my-1">';
  print"  <input type=\"hidden\" name=\"mode\" value=\"".$action."\">";
  print"  <input type=\"hidden\" name=\"kod\" value=\"".$this->id."\">";
  echo'<div class="form-group">';
  echo'<label for="formGroupExampleInput">Введите Ваше имя</label>';
  echo'<input type="text" class="form-control" id="inputCity" name="name" value="'.$this->name.'">';
  echo'</div>';
  echo'<div class="form-group">';
  echo'<label for="formGroupExampleInput">Введите Ваш номер телефона (7-***-**-**)</label>';
  echo'<input type="text" class="form-control" id="inputCity" name="other" value="'.$this->other.'">';
  echo'</div>';
  echo '<button type="submit" class="btn btn-primary" name="submit">Сохранить</button>';
  //print" <p>Введите Ваше имя</p>";
  //print"  <input type=\"text\" name=\"name\" size=\"42\" value=\"".$this->name."\"><br>";
//  print" <p>Введите Ваш номер телефона</p>";
//  print"  <input type=\"text\" name=\"other\" size=\"42\" value=\"".$this->other."\"><br>";
//  print"  <p><INPUT TYPE=\"submit\" VALUE=\"Сохранить\"></p>";
  print"</FORM>";
  echo '</div>';
}
public function showfoto($header){
  //должна показать фото, если загружено и ссылку удалить фотографии
  //если фото не загружено, показать форму загрузки фото
  // выводим форму загрузки файла
  //**************
  $IMGHIx=480;
  $IMGHIy=360;
  //$work_dir = $_SERVER['DOCUMENT_ROOT']
//  $work_dir = $_SERVER['HTTP_HOST'] .'/zp';
//  $work_dir=$work_dir."/imgobj/";
//проверим, есть ли фото. Если есть, то вывести фото, иначе форму.
$sql = 'SELECT * FROM `'. BEZ_DBPREFIX .'foto` WHERE `ft_id_reg` = "'. $this->id .'"';
$res = $this->mysqlQuery($sql);
if(mysql_num_rows($res) > 0){
//выведем фото
   $row = mysql_fetch_assoc($res);
   echo '<img src="imgobj/'.$row['ft_img'].'"><p>';
   echo '<a href="index.php?mode=deletefoto">Удалить фото</a>';
}
 else {
//если фото нет в базе, выведем форму
echo '<div class="col-sm-3 my-1">';
print"  <FORM ENCTYPE=\"multipart/form-data\" ACTION=\"index.php\" METHOD=\"POST\">";
echo '<div class="form-group">';
echo'<label for="exampleFormControlFile1">Загрузить фото (формат jpeg!)</label>';
echo'<input type="file" class="form-control-file" id="exampleFormControlFile1" name="img">';
echo'</div>';
echo'<button type="submit" class="btn btn-primary" name="submit">Загрузить</button>';

print"  <input type=\"hidden\" name=\"mode\" value=\"upload\">";
print"  <input type=\"hidden\" name=\"kod\" value=\"$this->id\">";
print"  <INPUT TYPE=\"hidden\" NAME=\"MAX_FILE_SIZE\" VALUE=\"6000000\">";

//print"  Загрузить фото <INPUT NAME=\"img\" TYPE=\"file\"><BR>";
//print"  <INPUT TYPE=\"submit\" VALUE=\"Загрузить\">";
print"</FORM>";
echo '</div>';
 }

  $work_dir=BEZ_HOST."/imgobj/";

  //**************
  if(isset($_POST['mode'])){
  if($_POST['mode']=="upload"){
  // определяем уникальное имя файла
  $identificatorimg = uniqid("i",FALSE);// Генерируется строка
  str_replace('.','',$identificatorimg);//уберем точки, вдруг в конце будет
  $newimgfile="./imgobj/".$identificatorimg.".jpg";
//  echo $_FILES['img']['tmp_name'];
  if(move_uploaded_file($_FILES['img']['tmp_name'],$newimgfile)){
    echo "Файл корректен и был успешно загружен.\n";
    //изменим размеры
    resize($newimgfile, 150);
    // Подключиться к серверу и выбрать базу данных
    $sql='INSERT INTO `'. BEZ_DBPREFIX .'foto`
          VALUES(
            "'. $this->id .'",
            "'. $identificatorimg .'.jpg"
            )';
        $res = mysqlQuery($sql);
        header($header);
 } else {
     echo "Возможная атака с помощью файловой загрузки либо не выбран файл!\n";
 }


    }
  }
}

public function deletefoto($header){
  //найдем имя файла в базе
  $sql = 'SELECT * FROM `'. BEZ_DBPREFIX .'foto` WHERE `ft_id_reg` = "'. $this->id .'"';
  $res = $this->mysqlQuery($sql);
  if(mysql_num_rows($res) > 0){
  //выведем фото
     $row = mysql_fetch_assoc($res);
     $FILEtoDEL="./imgobj/".$row['ft_img'];
     unlink($FILEtoDEL);
     $sql = "DELETE FROM ".BEZ_DBPREFIX."foto WHERE ft_id_reg=\"$this->id\"";
    $res = $this->mysqlQuery($sql);
    header($header);
  }


}

public function editrecord($name,$other){
  $sql = 'UPDATE `'. BEZ_DBPREFIX .'profile`
      SET `pr_name` = "'.$name.'",
          `pr_other` = "'.$other.'"
      WHERE `pr_id_reg` = "'. $this->id .'"';
  $res = mysqlQuery($sql);
}
public function recordname($name,$other){
  //запись в базу имени $query = "INSERT INTO foto VALUES (\"$kod\",\"$identificatorimg\",\"$opisfoto\")";
  //$query = 'INSERT INTO '. BEZ_DBPREFIX .'profile VALUES ("'.$this->id.'","'.$name.'","'.$other.'")';
  $sql = 'INSERT INTO `'. BEZ_DBPREFIX .'profile`
      VALUES(
          "",
          "'. $this->id .'",
          "'. $name .'",
          "'. $other .'",
          "0"
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

//генератор пароля из строки
 function gen_password($length = 6)
 {
 	$chars = 'qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP';
 	$size = strlen($chars) - 1;
 	$password = '';
 	while($length--) {
 		$password .= $chars[rand(0, $size)];
 	}
 	return $password;
 }
