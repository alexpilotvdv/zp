<?php
//Ключ защиты
if (!defined('BEZ_KEY')) {
    header("HTTP/1.1 404 Not Found");
    exit(file_get_contents('404.html'));
}
if (isset($_POST['mode'])) {
    //необходимо обработать данные
    $sql='UPDATE
`'. BEZ_DBPREFIX .'records` SET
`rec_fact_nalet` = ' . $_POST['minutes'] . '
WHERE `rec_id` = ' . $_POST['idday'] . '';
    $res=mysqlQuery($sql);
    header('Location:'. BEZ_HOST .'admin/?mode=edit_day&id=' . $_POST['day'] . '');
} else {
    echo ' <div class="row">
       <div class="col-sm col-md-6">
       <form action="" method="POST">
       <input type = "hidden" name = "mode" value = "zapis_fact">
       <input type = "hidden" name = "idday" value = "' . $_GET['id_rec'] . '">
       <input type = "hidden" name = "day" value = "' . $_GET['day'] . '">
       <div class="form-group">
       <label>Введите фактический налет в минутах:</label>
       </div>
       <div class="form-group">
       <select class="custom-select" name="minutes">';
    $sql = 'SELECT *
    FROM `'. BEZ_DBPREFIX .'records`
    WHERE `rec_id` = ' . $_GET['id_rec'] . '';
    $res=mysqlQuery($sql);
    if (mysql_num_rows($res) > 0) {
        $row = mysql_fetch_assoc($res);
        for ($i=0;$i<190;$i=$i+5) {
            if ($i==$row['rec_fact_nalet']) {
                echo'<option selected value="' . $i . '">' . $i . '</option>';
            } else {
                echo'<option value="' . $i . '">' . $i . '</option>';
            }
        }
    }

    echo '</select></div>';
    echo'<button type="submit" class="btn btn-primary">Сохранить</button>';
    echo ' </form>
        </div></div>';
}
