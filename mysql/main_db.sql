-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Авг 23 2013 г., 00:44
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
-- Структура таблицы `arenas`
--

CREATE TABLE IF NOT EXISTS `arenas` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `btl`
--

CREATE TABLE IF NOT EXISTS `btl` (
  `id_b` int(11) NOT NULL AUTO_INCREMENT,
  `idb` int(11) NOT NULL,
  `idc` int(7) NOT NULL,
  `date` date NOT NULL,
  `time` int(11) NOT NULL,
  `type` char(25) NOT NULL,
  `id_prov` varchar(7) NOT NULL,
  `prov` varchar(30) NOT NULL,
  `started` tinyint(1) NOT NULL,
  `arena` varchar(20) NOT NULL,
  PRIMARY KEY (`id_b`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='текущие битвы клана' AUTO_INCREMENT=192084 ;

-- --------------------------------------------------------

--
-- Структура таблицы `cat_tanks`
--

CREATE TABLE IF NOT EXISTS `cat_tanks` (
  `id_t` int(11) NOT NULL AUTO_INCREMENT,
  `localized_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `image_url` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `level` int(11) NOT NULL,
  `nation` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `class` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_t`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=335 ;

-- --------------------------------------------------------

--
-- Структура таблицы `clan`
--

CREATE TABLE IF NOT EXISTS `clan` (
  `id_c` int(11) NOT NULL AUTO_INCREMENT,
  `idc` int(11) NOT NULL,
  `idp` int(11) NOT NULL,
  `role_localised` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'новобранец',
  `date` date NOT NULL,
  `freq` int(11) NOT NULL DEFAULT '1',
  `target` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_c`),
  UNIQUE KEY `idp` (`idp`),
  KEY `idc` (`idc`),
  KEY `role_localised` (`role_localised`),
  KEY `date` (`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=24530 ;

-- --------------------------------------------------------

--
-- Структура таблицы `clan_info`
--

CREATE TABLE IF NOT EXISTS `clan_info` (
  `id _cl` int(11) NOT NULL AUTO_INCREMENT,
  `idc` int(11) NOT NULL,
  `color` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `tag` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `actdate` int(11) NOT NULL,
  `allians` int(11) NOT NULL,
  `rate` int(6) NOT NULL,
  `firepower` int(2) NOT NULL,
  `skill` int(2) NOT NULL,
  `position` int(11) NOT NULL,
  `igrokovkv` int(3) NOT NULL,
  `fishek` int(3) NOT NULL,
  `smallimg` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id _cl`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=382 ;

-- --------------------------------------------------------

--
-- Структура таблицы `event_clan`
--

CREATE TABLE IF NOT EXISTS `event_clan` (
  `id_ec` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL DEFAULT '0',
  `idp` int(11) NOT NULL,
  `idc` int(11) NOT NULL,
  `message` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `reason` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  PRIMARY KEY (`id_ec`),
  KEY `idc` (`idc`),
  KEY `date` (`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=94147 ;

-- --------------------------------------------------------

--
-- Структура таблицы `event_tank`
--

CREATE TABLE IF NOT EXISTS `event_tank` (
  `id_et` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL DEFAULT '0',
  `idp` int(11) NOT NULL,
  `idc` int(11) NOT NULL,
  `idt` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  PRIMARY KEY (`id_et`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=27486 ;

-- --------------------------------------------------------

--
-- Структура таблицы `player`
--

CREATE TABLE IF NOT EXISTS `player` (
  `id_p` int(11) NOT NULL AUTO_INCREMENT,
  `idp` int(11) NOT NULL,
  `idc` int(11) NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` date NOT NULL,
  `spotted` int(11) NOT NULL,
  `hits_percents` int(11) NOT NULL,
  `capture_points` int(11) NOT NULL,
  `damage_dealt` int(11) NOT NULL,
  `frags` int(11) NOT NULL,
  `dropped_capture_points` int(11) NOT NULL,
  `wins` int(11) NOT NULL,
  `losses` int(11) NOT NULL,
  `battles_count` int(11) NOT NULL,
  `survived_battles` int(11) NOT NULL,
  `xp` int(11) NOT NULL,
  `battle_avg_xp` int(11) NOT NULL,
  `max_xp` int(11) NOT NULL,
  `in_clan` tinyint(4) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `rating` int(4) NOT NULL,
  `wn6` int(4) NOT NULL,
  `rating30` int(4) NOT NULL,
  `wn630` int(4) NOT NULL,
  `win30` decimal(5,2) NOT NULL,
  PRIMARY KEY (`id_p`),
  KEY `date` (`date`),
  KEY `idc` (`idc`),
  KEY `idp` (`idp`),
  KEY `name_2` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=103565 ;

-- --------------------------------------------------------

--
-- Структура таблицы `player_ach`
--

CREATE TABLE IF NOT EXISTS `player_ach` (
  `id_pa` int(11) NOT NULL AUTO_INCREMENT,
  `idp` int(11) NOT NULL,
  `ida` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id_pa`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=152293 ;

-- --------------------------------------------------------

--
-- Структура таблицы `player_btl`
--

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
  PRIMARY KEY (`id_pb`),
  KEY `idp` (`idp`),
  KEY `date` (`date`),
  KEY `battle_count` (`battle_count`),
  KEY `idt` (`idt`),
  KEY `idtidp` (`idt`,`idp`),
  KEY `idpidt` (`idp`,`idt`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2540427 ;

-- --------------------------------------------------------

--
-- Структура таблицы `possession`
--

CREATE TABLE IF NOT EXISTS `possession` (
  `id_pos` int(11) NOT NULL AUTO_INCREMENT,
  `idpr` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `idc` int(11) NOT NULL,
  `attacked` tinyint(1) NOT NULL,
  `occupancy_time` int(11) NOT NULL,
  `capital` tinyint(1) NOT NULL,
  `mutiny` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_pos`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1043 ;

-- --------------------------------------------------------

--
-- Структура таблицы `province`
--

CREATE TABLE IF NOT EXISTS `province` (
  `id_pr` int(11) NOT NULL AUTO_INCREMENT,
  `prime_time` double NOT NULL,
  `id` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `arena_id` int(10) NOT NULL DEFAULT '0',
  `revenue` int(10) NOT NULL,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `periphery` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `landing_url` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `landing_final_battle_time` int(16) NOT NULL,
  `region` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `neighbours` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_pr`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=294354 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tech_log`
--

CREATE TABLE IF NOT EXISTS `tech_log` (
  `date` int(11) NOT NULL,
  `players` int(11) NOT NULL,
  `all` int(1) NOT NULL,
  `cntplayers` int(11) NOT NULL,
  `timer` int(11) NOT NULL,
  PRIMARY KEY (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `wm_event`
--

CREATE TABLE IF NOT EXISTS `wm_event` (
  `id_e` int(11) NOT NULL AUTO_INCREMENT,
  `idpr` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `idc` int(11) NOT NULL,
  PRIMARY KEY (`id_e`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2605 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
