<?php
if (!defined('BEZ_KEY')) {
    header("HTTP/1.1 404 Not Found");
    exit(file_get_contents('../../404.html'));
}

//Проверяем зашел ли пользователь
if ($user === false) {
    echo '<h3>Доступ закрыт, Вы не вошли в систему!</h3>'."\n";
}

if ($user === true) {
    echo '<div class="container">';
    //выводим информацию по дню
    if(isset($_GET['idday'])){$day_id=$_GET['idday'];}
    if(isset($_POST['idday'])){$day_id=$_POST['idday'];}
    $sql = 'SELECT *
    FROM `'. BEZ_DBPREFIX .'day_for_flight`
    WHERE `day_id` = ' . $day_id . '';
    $res=mysqlQuery($sql);
    if(mysql_num_rows($res) > 0){
      $row = mysql_fetch_assoc($res);
    echo'  <div class="row">
      <div class="col-sm">
    <div class="card">
    <h5 class="card-header">' . $row['day_data'] . '</h5>
    <div class="card-body">
      <h5 class="card-title">Начало полетов: ' . substr($row['day_start'],-8) . '</h5>
      <h5 class="card-title">Окончание полетов: ' . substr($row['day_end'],-8) . '</h5>
      <h5 class="card-title">Планируемый максимальный налет: ' . $row['day_total_time'] . '</h5>
      <p class="card-text">Дополнительная информация: ' . $row['day_info'] . '</p>
      </div>
     </div>
    </div>
  </div>';

    }

    //выводим информацию по записавшимся
    $sql = 'SELECT *
    FROM `'. BEZ_DBPREFIX .'records`
    LEFT JOIN `'. BEZ_DBPREFIX .'profile`
    ON `rec_iduser` = `pr_id_reg`
    WHERE `rec_day` = ' . $day_id . '';
    $res=mysqlQuery($sql);
    if(mysql_num_rows($res) > 0){
      echo ' <div class="row">
        <div class="col-sm"><p>';
       while($row = mysql_fetch_assoc($res)){
         echo'<div class="alert alert-success" role="alert">
       ' . $row['pr_name'] . ' - ' . $row['rec_plan_nalet'] . ' минут.<br/>
       ' . $row['rec_info'] . '
         </div>';
       }
       echo'</div>
           </div>';
    }

    //выводим форму
    //необходимо проверить, есть ли уже запись этого пользователя
    //если есть, то вывести форму редактирования
    echo ' <div class="row">
           <div class="col-sm">
           <form action="" method="POST">
           <input type = "hidden" name = "mode" value = "zapis">
           <input type = "hidden" name = "idday" value = "' . $day_id . '">
           <div class="form-group">
           <label>Выберите желаемый налет в минутах:</label>
           </div>
           <div class="form-group">
           <select class="custom-select" name="minutes">';
    $sql = 'SELECT *
        FROM `'. BEZ_DBPREFIX .'records`
        WHERE `rec_iduser` = ' . $user_id . '';

    $res=mysqlQuery($sql);
    if(mysql_num_rows($res) > 0){
      $tp_flag=1;
    $row = mysql_fetch_assoc($res);
    for($i=10;$i<190;$i=$i+10){
      if($i==$row['rec_plan_nalet']){
        echo'<option selected value="' . $i . '">' . $i . '</option>';
      } else {
        echo'<option value="' . $i . '">' . $i . '</option>';
      }
    }
  } else {
    $tp_flag=0;
    //выведем просто форму
    for($i=10;$i<190;$i=$i+10){
      if($i==40){
        echo'<option selected value="' . $i . '">' . $i . '</option>';
      } else {
        echo'<option value="' . $i . '">' . $i . '</option>';
      }
    }
  }
  echo '</select></div>';
  if($tp_flag==1){
    echo'<input type = "hidden" name = "action" value = "edit_new">';
    echo'<input type = "hidden" name = "id" value = "' . $row['rec_id'] . '">';
    echo'  <div class="form-group">
      <label>Информация</label>
      <textarea class="form-control" name="info"
        rows="3">' . $row['rec_info'] . '</textarea>
      </div>';
  }
  if($tp_flag==0){
    echo'<input type = "hidden" name = "action" value = "add_new">';
    echo'  <div class="form-group">
      <label>Информация</label>
      <textarea class="form-control" name="info"  rows="3"></textarea>
      </div>';
  }

  echo'<button type="submit" class="btn btn-primary">Сохранить</button>';

    echo ' </form>
            </div>
            </div>
            </div>';
//обработаем данные формы и перезагрузим страницу
if(isset($_POST['action'])){
if($_POST['action']=='add_new' AND $tp_flag==0){
  $sql='INSERT INTO
  `'. BEZ_DBPREFIX .'records`
  VALUES(
    "",
    ' . $user_id . ' ,
    ' . $_GET['idday'] . ' ,
    ' . $_POST['minutes'] . ' ,
    0,
    "' . $_POST['info'] . '")';
    $res=mysqlQuery($sql);
    header('Location:'. BEZ_HOST .'?mode=zapis&idday=' . $day_id . '');
} else if ($_POST['action']=='edit_new'){
  $sql='UPDATE
  `'. BEZ_DBPREFIX .'records` SET
  `rec_plan_nalet` = ' . $_POST['minutes'] . ' ,
  `rec_info` = "' . $_POST['info'] . '"
  WHERE `rec_id` = ' . $_POST['id'] . '';
$res=mysqlQuery($sql);
header('Location:'. BEZ_HOST .'?mode=zapis&idday=' . $day_id . '');
}
}

}
