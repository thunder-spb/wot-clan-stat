-- phpMyAdmin SQL Dump
-- version 3.5.8
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июн 11 2013 г., 18:26
-- Версия сервера: 5.1.67-log
-- Версия PHP: 5.3.23-pl0-gentoo

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
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
(1, 'medalCarius', 'Кариус', 'MedalCarius', 1),
(2, 'medalHalonen', 'Халонен', 'MedalHalonen', 2),
(3, 'invader', 'Захватчик', 'Invader', 4),
(4, 'medalFadin', 'Фадин', 'MedalFadin', 2),
(5, 'armorPiercer', 'Бронебойщик', 'ArmorPiercer', 3),
(6, 'medalEkins', 'Экинс', 'MedalEkins', 1),
(7, 'mousebane', 'Гроза мышей', 'Mousebane', 3),
(8, 'medalKay', 'Кей', 'MedalKay', 1),
(9, 'defender', 'Защитник', 'Defender', 4),
(10, 'medalLeClerc', 'Леклерк', 'MedalLeClerc', 1),
(11, 'supporter', 'Поддержка', 'Supporter', 4),
(12, 'steelwall', 'Стальная стена', 'Steelwall', 4),
(13, 'medalAbrams', 'Абрамс', 'MedalAbrams', 1),
(14, 'medalPoppel', 'Попель', 'MedalPoppel', 1),
(15, 'medalOrlik', 'Орлик', 'MedalOrlik', 2),
(16, 'handOfDeath', 'Коса смерти', 'HandOfDeath', 3),
(17, 'sniper', 'Снайпер', 'Sniper', 4),
(18, 'warrior', 'Воин', 'Warrior', 4),
(19, 'titleSniper', 'Стрелок', 'TitleSniper', 3),
(20, 'medalWittmann', 'Бёльтер', 'MedalBoelter', 2),
(21, 'medalBurda', 'Бурда', 'MedalBurda', 2),
(22, 'scout', 'Разведчик', 'Scout', 4),
(23, 'beasthunter', 'Зверобой', 'Beasthunter', 3),
(24, 'kamikaze', 'Камикадзе', 'Kamikaze', 3),
(25, 'raider', 'Рейдер', 'Raider', 3),
(26, 'medalOskin', 'Оськин', 'MedalOskin', 2),
(27, 'medalBillotte', 'Бийот', 'MedalBillotte', 2),
(28, 'medalLavrinenko', 'Лавриненко', 'MedalLavrinenko', 1),
(29, 'medalKolobanov', 'Колобанов', 'MedalKolobanov', 2),
(30, 'invincible', 'Неуязвимый', 'Invincible', 3),
(31, 'lumberjack', 'lumberjack', 'lumberjack', 0),
(32, 'tankExpert', 'Эксперт', 'TankExpert', 5),
(33, 'diehard', 'Живучий', 'Diehard', 3),
(34, 'medalKnispel', 'Книспель', 'MedalKnispel', 1),
(35, 'mechanicEngineer', 'Инженер-механик', 'mechanicEngineer', 0),
(37, 'heroesOfRassenay', 'Герой Рассеняя', 'heroesOfRassenay', 2),
(38, 'sinai', 'Лев Синая', 'sinai', 3),
(40, 'maxKillingSeries', 'Серия "Коса Смерти"', '', 10),
(41, 'maxSniperSeries', 'Серия "Стрелок"', '', 10),
(42, 'maxPiercingSeries', 'Серия "Бронебойщик"', '', 10),
(43, 'maxDiehardSeries', 'Серия "Живучий"', '', 10),
(44, 'maxInvincibleSeries', 'Серия "Неуязвимый"', '', 10),
(45, 'tankExpertsUsa', 'Эксперт<br>США', 'tankExpert2', 5),
(46, 'tankExpertsFrance', 'Эксперт<br>Франция', 'tankExpert4', 5),
(47, 'tankExpertsGermany', 'Эксперт<br>Германия', 'tankExpert1', 5),
(48, 'tankExpertsUssr', 'Эксперт<br>СССР', 'tankExpert0', 5),
(49, 'tankExpertsChina', 'Эксперт<br>Китай', 'tankExpert3', 5),
(50, 'tankExpertsUK', 'Эксперт<br>Великобритания', 'tankExpert5', 5),
(51, 'mechanicEngineersUSA', 'Инженер-механик<br>США', 'mechanicEngineer2', 5),
(52, 'mechanicEngineersUssr', 'Инженер-механик<br>СССР', 'mechanicEngineer0', 5),
(53, 'mechanicEngineersFrance', 'Инженер-механик<br>Франция', 'mechanicEngineer4', 5),
(54, 'mechanicEngineersGermany', 'Инженер-механик <br> Германия', 'mechanicEngineer1', 5),
(55, 'mechanicEngineersChina', 'Инженер-механик<br>Китай', 'mechanicEngineer3', 5),
(56, 'mechanicEngineersUK', 'Инженер-механик<br>Великобритания', 'mechanicEngineer5', 5),
(57, 'mechanicEngineer', 'Инженер-механик', 'mechanicEngineer', 5),
(59, 'medalPascucci', 'Паскуччи', 'medalPascucci', 2),
(60, 'medalBrunoPietro', 'Бруно-Пьетро', 'medalBrunoPietro', 2),
(61, 'evileye', 'Дозорный', 'evileye', 4),
(62, 'medalTamadaYoshio', 'Тамада Йошио', 'medalTamadaYoshio', 2),
(63, 'medalBrothersInArms', 'Братья по оружию', 'medalBrothersInArms', 4),
(64, 'medalTarczay', 'Тарцай', 'medalTarczay', 4),
(65, 'medalCrucialContribution', 'Решающий вклад', 'medalCrucialContribution', 4),
(66, 'medalDeLanglade', 'де Лагланд', 'medalDeLanglade', 2),
(67, 'medalNikolas', 'Николс', 'medalNikolas', 2),
(68, 'medalLafayettePool', 'Пул', 'medalLafayettePool', 2),
(69, 'medalLehvaslaiho', 'Лехвеслайхо ', 'medalLehvaslaiho', 2),
(70, 'medalDumitru', 'Думитру', 'medalDumitru', 2),
(71, 'medalRadleyWalters', 'Рэдли-Уолтерс', 'medalRadleyWalters', 2),
(72, 'bombardier', 'Бомбардир ', 'bombardier', 3),
(75, 'luckyDevil', 'Счастливчик', 'Lucky', 6),
(74, 'huntsman', 'Егерь', 'Jager', 6),
(76, 'ironMan', 'Невозмутимый', 'Imperturbable', 6),
(77, 'pattonValley', 'Долина Паттонов', 'Pattonvalley', 3),
(78, 'sturdy', 'Спартанец', 'Sparta', 6);

-- --------------------------------------------------------

--
-- Структура таблицы `tech`
--

CREATE TABLE IF NOT EXISTS `tech` (
  `current` int(11) NOT NULL DEFAULT '0',
  `lasthourwm` int(2) NOT NULL,
  PRIMARY KEY (`current`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `tech`
--

INSERT INTO `tech` (`current`,`lasthourwm`) VALUES
(760,1);

-- --------------------------------------------------------

--
-- Структура таблицы `wm_regions`
--

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
