<?php
//Ключ защиты

if(!defined('BEZ_KEY'))
{
    header("HTTP/1.1 404 Not Found");
    exit(file_get_contents('../../404.html'));
}
echo'<div class="container">';
//Проверяем зашел ли пользователь
if($user === false)
 echo '<h3>Доступ закрыт, Вы не вошли в систему!</h3>'."\n";
if($user === true){
  if($user_admin===true){
    //если пользователь админ
   $objTable = new smartTable('Дата','Статус');
    echo "<br>Status admin Ok<br>";
    $sql = 'SELECT *
        FROM `'. BEZ_DBPREFIX .'day_for_flight` ORDER BY `day_data`';
    $res = mysqlQuery($sql);
    if(mysql_num_rows($res) > 0){

      //определим количество
      $tp_col=mysql_num_rows($res);

      //определена ли страница
      if(isset($_GET['page'])){
        $tp_first_page = PAGE_MAX * ($_GET['page'] - 1);
        $sql .=' LIMIT ' . $tp_first_page . ', ' . PAGE_MAX;
      } else{
        //страница не определена, вывести первые записи
        $sql .=' LIMIT 0, ' . PAGE_MAX;
      }
      //echo (int)($tp_col / PAGE_MAX);
      $res = mysqlQuery($sql);
      while ($row = mysql_fetch_assoc($res)) {


     $objTable->insertrow($row['day_data'], $row['day_status']);

      }
      $objTable->show_pages((isset($_GET['page']) ? $_GET['page'] : 1),(int)($tp_col / PAGE_MAX),'mode=records');
      $objTable->showtable();


  }
    echo '<a href="?mode=formaddday"><button type="button" class="btn btn-success">Добавить день полетов</button></a>';
  }
}
  echo "</div>";

 ?>
