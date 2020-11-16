<?php
//выводит форму для редактирования дня полетов
$sql = 'SELECT *
FROM `'. BEZ_DBPREFIX .'day_for_flight` LEFT JOIN `'. BEZ_DBPREFIX .'profile`
ON `day_user_create` = `pr_id_reg`
WHERE `day_id` = ' . $_GET['id'] . '';
$res = mysqlQuery($sql);
if (mysql_num_rows($res) > 0) {
$row = mysql_fetch_assoc($res);

//определим переменную чекбокса
$tp_ch=($row['day_status']=='1') ? 'checked="checked"' : '';
//echo substr($row['day_start'],-8,-3);
//выведем форму
echo '<div class="container-md col-sm-4">
<form action="" method="POST">
  <div class="form-group">
    <label for="inputDate">Введите дату:</label>
    <input type="date" name="data" class="form-control" value="' . $row['day_data'] . '">
    <input type="hidden" name="mode" value="formrez" >
    <input type="hidden" name="idrecord" value="' . $row['day_id'] . '" >
    <input type="hidden" name="action" value="change_day" >
  </div>
  <div class="form-group">
    <label for="inputDate">Начало полетов:</label>
    <input type="time" name="timestart" class="form-control"
    value="' . substr($row['day_start'],-8,-3) . '">
  </div>
  <div class="form-group">
    <label for="inputDate">Окончание полетов:</label>
    <input type="time" name="timeend" class="form-control" value="' . substr($row['day_end'],-8,-3) . '">
  </div>
  <div class="form-group">
    <label for="inputDate">Максимальный налет за смену:</label>
    <input type="time" name="timemax" class="form-control" value="' . $row['day_total_time'] . '">
  </div>

  <div class="form-group">
  <label for="exampleFormControlTextarea1">Информация</label>
  <textarea class="form-control" name="info" id="exampleFormControlTextarea1" rows="3">' . $row['day_info'] . '</textarea>
 </div>

 <div class="form-group form-check">
   <input type="checkbox" class="form-check-input" name="day_active" value="1" ' . $tp_ch . '>
   <label class="form-check-label" for="exampleCheck1">Доступен для записи</label>
 </div>

 <div class="form-group form-check">
   <label>' . $row['pr_name'] . '</label>
 </div>

  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>
';

}
 ?>
