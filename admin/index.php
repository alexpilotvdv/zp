<?php
    /**
    * Главный файл (переключатель)
    * Site: http://bezramok-tlt.ru
    * Регистрация пользователя письмом
    */

    //Запускаем сессию
    session_start();

    //Устанавливаем кодировку и вывод всех ошибок
    header('Content-Type: text/html; charset=UTF8');
    error_reporting(E_ALL);

    //Включаем буферизацию содержимого
    ob_start();

    //Определяем переменную для переключателя
    $mode = isset($_GET['mode'])  ? $_GET['mode'] : false;
    $mode_post = isset($_POST['mode'])  ? $_POST['mode'] : false;
    $user = isset($_SESSION['user']) ? $_SESSION['user'] : false;
    $user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : false;
    $err = array();



    //Устанавливаем ключ защиты
    define('BEZ_KEY', true);

    //Подключаем конфигурационный файл
    include '../config.php';

    //Подключаем скрипт с функциями
    include '../func/funct.php';

    //подключаем MySQL
    include '../bd/bd.php';

    //проверим, является ли пользователь админом
        if ($user === true) {
            $sql = 'SELECT *
	        FROM `'. BEZ_DBPREFIX .'profile`, `'. BEZ_DBPREFIX .'reg`
	        WHERE `login` = "'. $user_name .'" AND `id` = `pr_id_reg`';
            $res = mysqlQuery($sql);
            if (mysql_num_rows($res) > 0) {
                $row = mysql_fetch_assoc($res);
                echo 'Пользователь: ' . $row['pr_name'];
                $id_user=$row['id']; //в дальнейшем понадобится
                //является ли пользователь админом (код 7)
                if ($row['pr_status']=='7') {
                    $user_admin=true;
                } else {
                    $user_admin=false;
                }
            } else {
                $user_admin=false;
            }
        }

    switch ($mode) {

        case 'users':
        //вывести всех  пользователей
            include 'showuser.php';
        break;

        case 'changefact':
            include 'changefact.php';
        break;

        case 'activate':
        //вывести всех  пользователей
            include 'activate.php';
        break;

        case 'formaddday':
        //вывести всех  пользователей
            include 'formaddday.html';
        break;

        case 'records':
            //вывести дни записей на полеты
            include 'records.php';
        break;

				case 'edit_day':
            //вывести форму для редактирования дня полетов
            include 'edit_day.php';
        break;

        case 'ch_status':
            //вывести дни записей на полеты / не используется
            include 'ch_status.php';
        break;

    }
    //отправлено из формы
    if ($user == true) {
        switch ($mode_post) {
        case 'formrez':
                    include 'formaddday.php';
            break;

        case 'rec_role':
            include 'rec_role.php';
            break;
      //  case 'zapis_fact':
      //      include 'changefact.php';
      //  break;
    }
    }

    //Получаем данные с буфера
    $content = ob_get_contents();
    ob_end_clean();

    //Подключаем наш шаблон
    include '../html/indexadmin.html';
