-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Янв 06 2020 г., 15:23
-- Версия сервера: 5.5.64-MariaDB
-- Версия PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- База данных: `admin_test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `element`
--

DROP TABLE IF EXISTS `element`;
CREATE TABLE IF NOT EXISTS `element` (
  `ID` int(18) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255) NOT NULL,
  `AVAILABLE` tinyint(1) NOT NULL,
  `PRICE` float NOT NULL DEFAULT '0',
  `MANUFACTURE` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `element`
--

INSERT INTO `element` (`ID`, `NAME`, `AVAILABLE`, `PRICE`, `MANUFACTURE`) VALUES
(1, 'Микроволновая печь встраиваемая Tesler MEB-2380Х', 1, 8980, 1),
(2, 'Микроволновая печь встраиваемая Weissgauff HMT-205', 1, 10790, 2),
(3, 'Микроволновая печь встраиваемая Bosch BEL524MB0', 1, 19990, 6);

-- --------------------------------------------------------

--
-- Структура таблицы `elem_sect`
--

DROP TABLE IF EXISTS `elem_sect`;
CREATE TABLE IF NOT EXISTS `elem_sect` (
  `ELEMENT` int(11) NOT NULL,
  `SECTION` int(11) NOT NULL,
  UNIQUE KEY `VERIFY` (`ELEMENT`,`SECTION`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `elem_sect`
--

INSERT INTO `elem_sect` (`ELEMENT`, `SECTION`) VALUES
(1, 30),
(2, 30),
(3, 30);

-- --------------------------------------------------------

--
-- Структура таблицы `manufacture`
--

DROP TABLE IF EXISTS `manufacture`;
CREATE TABLE IF NOT EXISTS `manufacture` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `manufacture`
--

INSERT INTO `manufacture` (`ID`, `NAME`) VALUES
(1, 'Tesler'),
(2, 'Weissgauff'),
(6, 'Bosch');

-- --------------------------------------------------------

--
-- Структура таблицы `section`
--

DROP TABLE IF EXISTS `section`;
CREATE TABLE IF NOT EXISTS `section` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255) NOT NULL,
  `PARENT_SECTION` int(18) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `section`
--

INSERT INTO `section` (`ID`, `NAME`, `PARENT_SECTION`) VALUES
(30, 'Микроволновки', 5),
(2, 'Стиральные машины', 5),
(3, 'Посудомоечные машины', 5),
(4, 'Холодильники', 5),
(5, 'Бытовая техника', 0),
(6, 'Садовая техника', 0),
(7, 'Газонокосилки', 6),
(8, 'Системы полива', 6),
(9, 'Мото-культиваторы', 6);
COMMIT;
