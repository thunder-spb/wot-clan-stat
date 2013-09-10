-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Сен 10 2013 г., 16:16
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
-- Структура таблицы `player_btl`
--

DROP TABLE IF EXISTS `player_btl`;
CREATE TABLE IF NOT EXISTS `player_btl` (
  `id_pb` int(11) NOT NULL AUTO_INCREMENT,
  `idp` int(11) NOT NULL,
  `idt` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `battle_count` int(11) NOT NULL,
  `win_count` int(11) NOT NULL,
  `frags` int(11) NOT NULL,
  `spotted` int(11) NOT NULL,
  `survivedBattles` int(11) NOT NULL,
  `damageDealt` int(11) NOT NULL,
  `frozen` datetime NOT NULL,
  `master` int(1) NOT NULL,
  `garage` int(1) NOT NULL,
  PRIMARY KEY (`id_pb`),
  KEY `idp` (`idp`),
  KEY `date` (`date`),
  KEY `battle_count` (`battle_count`),
  KEY `idt` (`idt`),
  KEY `idtidp` (`idt`,`idp`),
  KEY `idpidt` (`idp`,`idt`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `player_clan`
--

DROP TABLE IF EXISTS `player_clan`;
CREATE TABLE IF NOT EXISTS `player_clan` (
  `id_p` int(11) NOT NULL AUTO_INCREMENT,
  `idp` int(11) NOT NULL,
  `spotted_clan` int(11) NOT NULL,
  `hits_percents_clan` int(3) NOT NULL,
  `capture_points_clan` int(11) NOT NULL,
  `damage_dealt_clan` int(11) NOT NULL,
  `frags_clan` int(11) NOT NULL,
  `dropped_capture_points_clan` int(11) NOT NULL,
  `wins_clan` int(11) NOT NULL,
  `losses_clan` int(11) NOT NULL,
  `battles_count_clan` int(11) NOT NULL,
  `survived_battles_clan` int(11) NOT NULL,
  `xp_clan` int(11) NOT NULL,
  `battle_avg_xp_clan` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `rating` int(4) NOT NULL,
  `wn6` int(4) NOT NULL,
  `rating30` int(4) NOT NULL,
  `wn630` int(4) NOT NULL,
  `win30` decimal(5,2) NOT NULL,
  PRIMARY KEY (`id_p`),
  KEY `date` (`date`),
  KEY `idp` (`idp`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `player_company`
--

DROP TABLE IF EXISTS `player_company`;
CREATE TABLE IF NOT EXISTS `player_company` (
  `id_p` int(11) NOT NULL AUTO_INCREMENT,
  `idp` int(11) NOT NULL,
  `spotted_company` int(11) NOT NULL,
  `hits_percents_company` int(3) NOT NULL,
  `capture_points_company` int(11) NOT NULL,
  `damage_dealt_company` int(11) NOT NULL,
  `frags_company` int(11) NOT NULL,
  `dropped_capture_points_company` int(11) NOT NULL,
  `wins_company` int(11) NOT NULL,
  `losses_company` int(11) NOT NULL,
  `battles_count_company` int(11) NOT NULL,
  `survived_battles_company` int(11) NOT NULL,
  `xp_company` int(11) NOT NULL,
  `battle_avg_xp_company` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `rating` int(4) NOT NULL,
  `wn6` int(4) NOT NULL,
  `rating30` int(4) NOT NULL,
  `wn630` int(4) NOT NULL,
  `win30` decimal(5,2) NOT NULL,
  PRIMARY KEY (`id_p`),
  KEY `date` (`date`),
  KEY `idp` (`idp`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
