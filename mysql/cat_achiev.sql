-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Сен 10 2013 г., 16:12
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
-- Структура таблицы `cat_achiev`
--

DROP TABLE IF EXISTS `cat_achiev`;
CREATE TABLE IF NOT EXISTS `cat_achiev` (
  `id_ac` int(11) NOT NULL AUTO_INCREMENT,
  `medal` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `medal_ru` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `img` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id_ac`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=79 ;

--
-- Дамп данных таблицы `cat_achiev`
--

INSERT INTO `cat_achiev` (`id_ac`, `medal`, `medal_ru`, `img`, `type`) VALUES
(1, 'medal_carius', 'Кариус', 'MedalCarius', 1),
(2, 'medal_halonen', 'Халонен', 'MedalHalonen', 2),
(3, 'invader', 'Захватчик', 'Invader', 4),
(4, 'medal_fadin', 'Фадин', 'MedalFadin', 2),
(5, 'armor_piercer', 'Бронебойщик', 'ArmorPiercer', 3),
(6, 'medal_ekins', 'Экинс', 'MedalEkins', 1),
(7, 'mousebane', 'Гроза мышей', 'Mousebane', 3),
(8, 'medal_kay', 'Кей', 'MedalKay', 1),
(9, 'defender', 'Защитник', 'Defender', 4),
(10, 'medal_le_clerc', 'Леклерк', 'MedalLeClerc', 1),
(11, 'supporter', 'Поддержка', 'Supporter', 4),
(12, 'steelwall', 'Стальная стена', 'Steelwall', 4),
(13, 'medal_abrams', 'Абрамс', 'MedalAbrams', 1),
(14, 'medal_poppel', 'Попель', 'MedalPoppel', 1),
(15, 'medal_orlik', 'Орлик', 'MedalOrlik', 2),
(16, 'hand_of_death', 'Коса смерти', 'HandOfDeath', 3),
(17, 'sniper', 'Снайпер', 'Sniper', 4),
(18, 'warrior', 'Воин', 'Warrior', 4),
(19, 'title_sniper', 'Стрелок', 'TitleSniper', 3),
(20, 'medal_boelter', 'Бёльтер', 'MedalBoelter', 2),
(21, 'medal_burda', 'Бурда', 'MedalBurda', 2),
(22, 'scout', 'Разведчик', 'Scout', 4),
(23, 'beasthunter', 'Зверобой', 'Beasthunter', 3),
(24, 'kamikaze', 'Камикадзе', 'Kamikaze', 3),
(25, 'raider', 'Рейдер', 'Raider', 3),
(26, 'medal_oskin', 'Оськин', 'MedalOskin', 2),
(27, 'medal_billotte', 'Бийот', 'MedalBillotte', 2),
(28, 'medal_lavrinenko', 'Лавриненко', 'MedalLavrinenko', 1),
(29, 'medal_kolobanov', 'Колобанов', 'MedalKolobanov', 2),
(30, 'invincible', 'Неуязвимый', 'Invincible', 3),
(31, 'lumberjack', 'lumberjack', 'lumberjack', 0),
(32, 'tank_expert', 'Эксперт', 'TankExpert', 5),
(33, 'diehard', 'Живучий', 'Diehard', 3),
(34, 'medal_knispel', 'Книспель', 'MedalKnispel', 1),
(37, 'medal_heroes_of_rassenay', 'Герой Рассеняя', 'heroesOfRassenay', 2),
(38, 'sinai', 'Лев Синая', 'sinai', 3),
(40, 'max_killing_series', 'Серия "Коса Смерти"', '', 10),
(41, 'max_sniper_series', 'Серия "Стрелок"', '', 10),
(42, 'max_piercing_series', 'Серия "Бронебойщик"', '', 10),
(43, 'max_diehard_series', 'Серия "Живучий"', '', 10),
(44, 'max_invincible_series', 'Серия "Неуязвимый"', '', 10),
(45, 'tank_expert_usa', 'Эксперт<br>США', 'tankExpert2', 5),
(46, 'tank_expert_france', 'Эксперт<br>Франция', 'tankExpert4', 5),
(47, 'tank_expert_germany', 'Эксперт<br>Германия', 'tankExpert1', 5),
(48, 'tank_expert_ussr', 'Эксперт<br>СССР', 'tankExpert0', 5),
(49, 'tank_expert_china', 'Эксперт<br>Китай', 'tankExpert3', 5),
(50, 'tank_expert_uk', 'Эксперт<br>Великобритания', 'tankExpert5', 5),
(51, 'mechanic_engineer_usa', 'Инженер-механик<br>США', 'mechanicEngineer2', 5),
(52, 'mechanic_engineer_ussr', 'Инженер-механик<br>СССР', 'mechanicEngineer0', 5),
(53, 'mechanic_engineer_france', 'Инженер-механик<br>Франция', 'mechanicEngineer4', 5),
(54, 'mechanic_engineer_germany', 'Инженер-механик <br> Германия', 'mechanicEngineer1', 5),
(55, 'mechanic_engineer_china', 'Инженер-механик<br>Китай', 'mechanicEngineer3', 5),
(56, 'mechanic_engineer_uk', 'Инженер-механик<br>Великобритания', 'mechanicEngineer5', 5),
(57, 'mechanic_engineer', 'Инженер-механик', 'mechanicEngineer', 5),
(59, 'medal_pascucci', 'Паскуччи', 'medalPascucci', 2),
(60, 'medal_bruno_pietro', 'Бруно-Пьетро', 'medalBrunoPietro', 2),
(61, 'evileye', 'Дозорный', 'evileye', 4),
(62, 'medal_tamada_yoshio', 'Тамада Йошио', 'medalTamadaYoshio', 2),
(63, 'medal_brothers_in_arms', 'Братья по оружию', 'medalBrothersInArms', 4),
(64, 'medal_tarczay', 'Тарцай', 'medalTarczay', 4),
(65, 'medal_crucial_contribution', 'Решающий вклад', 'medalCrucialContribution', 4),
(66, 'medal_delanglade', 'де Лагланд', 'medalDeLanglade', 2),
(67, 'medal_nikolas', 'Николс', 'medalNikolas', 2),
(68, 'medal_lafayette_pool', 'Пул', 'medalLafayettePool', 2),
(69, 'medal_lehvaslaiho', 'Лехвеслайхо ', 'medalLehvaslaiho', 2),
(70, 'medal_dumitru', 'Думитру', 'medalDumitru', 2),
(71, 'medal_radley_walters', 'Рэдли-Уолтерс', 'medalRadleyWalters', 2),
(72, 'bombardier', 'Бомбардир ', 'bombardier', 3),
(75, 'lucky_devil', 'Счастливчик', 'Lucky', 6),
(74, 'huntsman', 'Егерь', 'Jager', 6),
(76, 'iron_man', 'Невозмутимый', 'Imperturbable', 6),
(77, 'patton_valley', 'Долина Паттонов', 'Pattonvalley', 3),
(78, 'sturdy', 'Спартанец', 'Sparta', 6);

-- --------------------------------------------------------

--
-- Структура таблицы `tech`
--

DROP TABLE IF EXISTS `tech`;
CREATE TABLE IF NOT EXISTS `tech` (
  `current` int(11) NOT NULL DEFAULT '0',
  `lasthourwm` int(11) NOT NULL DEFAULT '0',
  `lastiv` int(11) NOT NULL DEFAULT '0',
  `cntmaxpl` int(11) NOT NULL DEFAULT '1',
  `maxplreq` int(11) NOT NULL DEFAULT '10',
  `reqtarget` int(11) DEFAULT '20',
  PRIMARY KEY (`current`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `tech`
--

INSERT INTO `tech` (`current`, `lasthourwm`, `lastiv`, `cntmaxpl`, `maxplreq`, `reqtarget`) VALUES
(150, 31779, 1378650075, 13, 30, 16);

-- --------------------------------------------------------

--
-- Структура таблицы `wm_regions`
--

DROP TABLE IF EXISTS `wm_regions`;
CREATE TABLE IF NOT EXISTS `wm_regions` (
  `id_r` varchar(6) NOT NULL,
  `name` varchar(30) NOT NULL,
  `url` varchar(50) NOT NULL,
  `excl` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `wm_regions`
--

INSERT INTO `wm_regions` (`id_r`, `name`, `url`, `excl`) VALUES
('reg_01', 'Северная Европа', '/clanwars/maps/provinces/regions/1/', 0),
('reg_02', 'Средиземноморье', '/clanwars/maps/provinces/regions/2/', 0),
('reg_03', 'Западная Африка', '/clanwars/maps/provinces/regions/3/', 0),
('reg_04', 'Восточная Африка', '/clanwars/maps/provinces/regions/4/', 0),
('reg_05', 'Урал и Зауралье', '/clanwars/maps/provinces/regions/5/', 0),
('reg_06', 'Сибирь и Дальний восток', '/clanwars/maps/provinces/regions/6/', 0),
('reg_11', 'Южная Африка', '/clanwars/maps/provinces/regions/11/', 0),
('reg_07', 'Азия', '/clanwars/maps/provinces/regions/7/', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
