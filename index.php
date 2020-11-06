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
	$user = isset($_SESSION['user']) ? $_SESSION['user'] : false;
	$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : false;
	$err = array();

	//Устанавливаем ключ защиты
	define('BEZ_KEY', true);

	//Подключаем конфигурационный файл
	include './config.php';

	//Подключаем скрипт с функциями
	include './func/funct.php';

	//подключаем MySQL
	include './bd/bd.php';

	switch($mode)
	{
		//Подключаем обработчик с формой регистрации
		case 'reg':
			include './scripts/reg/reg.php';
			include './scripts/reg/reg_form.html';
		break;

		//Подключаем обработчик с формой авторизации
		case 'auth':
			include './scripts/auth/auth.php';
			include './scripts/auth/auth_form.html';
			include './scripts/auth/show.php';
		break;

		case 'changepass':
			include './scripts/auth/chpass.php';
			include './scripts/auth/chpass_form.html';
		break;

		case 'exit':
			$_SESSION['user'] = false;
			header('Location: ' . BEZ_HOST );
		break;

		case 'profile':
		if($user == true){
			include './scripts/main/editprofile.php';
			break;
		}
		case 'deletefoto':
		if($user == true){
			include './scripts/main/editprofile.php';
			break;
		}
		case 'resetpass':

			include './scripts/auth/auth.php';
			break;


	//	else {
	//		echo "пшел нах";
	//	}
		break;

	}
	//в перспективе можно если установлен, то присвоить переменной $mode
if(isset($_POST['mode'])){
	if($_POST['mode']=="addzapis"){
		if($user == true){
			include './scripts/main/editprofile.php';
		}
		else {
			echo "пшел нах";
		}
	}

	if($_POST['mode']=="editform"){
		if($user == true){
			include './scripts/main/editprofile.php';
		}
		else {
			echo "пшел нах";
		}
	}

	if($_POST['mode']=="upload"){
		if($user == true){
			include './scripts/main/editprofile.php';
		}
		else {
			echo "пшел нах";
		}
	}

	}

	//Получаем данные с буфера
	$content = ob_get_contents();
	ob_end_clean();

	//Подключаем наш шаблон
	include './html/index.html';
?>
