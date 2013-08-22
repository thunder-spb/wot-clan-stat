-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Авг 23 2013 г., 00:25
-- Версия сервера: 5.1.70-log
-- Версия PHP: 5.3.27-pl0-gentoo

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `stat`
--

-- --------------------------------------------------------

--
-- Структура таблицы `tech`
--

DROP TABLE IF EXISTS `tech`;
CREATE TABLE `tech` (
  `current` int(11) NOT NULL DEFAULT '0',
  `lasthourwm` int(11) NOT NULL DEFAULT '0',
  `lastiv` int(11) NOT NULL DEFAULT '0',
  `cntmaxpl` int(11) NOT NULL DEFAULT '10',
  `maxplreq` int(11) NOT NULL DEFAULT '20',
  `reqtarget` int(11) DEFAULT '20',
  PRIMARY KEY (`current`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `tech`
--

INSERT INTO `tech` (`current`, `lasthourwm`, `lastiv`, `cntmaxpl`, `maxplreq`, `reqtarget`) VALUES
(0, 31003, 1377192064, 24, 63, 20);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
