<?php
if(!defined('BEZ_KEY'))
{
    header("HTTP/1.1 404 Not Found");
    exit(file_get_contents('../../404.html'));
}

//Проверяем зашел ли пользователь
if($user === false)
 echo '<h3>Доступ закрыт, Вы не вошли в систему!</h3>'."\n";

if($user === true){
  echo '<div class="container">';
//выводим информацию по дню
echo ' <div class="row">
      <div class="col-sm">
     Информация по полетам
     </div>
    </div>';


//выводим информацию по записавшимся
echo ' <div class="row">
      <div class="col-sm">
     Информация по записям
     </div>
    </div>';


//выводим форму
echo ' <div class="row">
       <div class="col-sm">
      Форма
       </div>
       </div>
       </div>';



}
 ?>
