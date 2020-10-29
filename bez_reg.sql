-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: e9sotok.mysql
-- Время создания: Окт 29 2020 г., 16:04
-- Версия сервера: 5.6.41-84.1
-- Версия PHP: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `e9sotok_00`
--

-- --------------------------------------------------------

--
-- Структура таблицы `bez_reg`
--
-- Создание: Июн 05 2020 г., 16:04
-- Последнее обновление: Июн 06 2020 г., 17:00
--

DROP TABLE IF EXISTS `bez_reg`;
CREATE TABLE `bez_reg` (
  `id` int(11) NOT NULL,
  `login` varchar(200) NOT NULL,
  `pass` varchar(32) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `active_hex` varchar(32) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `bez_reg`
--

INSERT INTO `bez_reg` (`id`, `login`, `pass`, `salt`, `active_hex`, `status`) VALUES
(11, 'a41751@gmail.com', 'eab1f3c304d61399244c4ecb225f9dd5', 'bf8a46a1', '998a50bc35cf7e1e4b256137258a533a', 1),
(12, 'gremlin-@mail.ru', '40fc4f3dfabf1df5d961b8eed5005591', '52b46f43', '7cf412b03c35c2a3ed17fef24be83219', 1),
(13, 'a41751@googlemail.com', 'e71eb6c566519d1b9a7af2fe7ceb33ca', 'ba3d935f', '833fafab1f736d75639134b512dc4bb0', 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `bez_reg`
--
ALTER TABLE `bez_reg`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `bez_reg`
--
ALTER TABLE `bez_reg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
