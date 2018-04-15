-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.5.53 - MySQL Community Server (GPL)
-- Операционная система:         Win64
-- HeidiSQL Версия:              9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры для таблица testDB.things_types
CREATE TABLE IF NOT EXISTS `things_types` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `thing_name` char(50) NOT NULL DEFAULT 'нет имени',
  `count` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы testDB.things_types: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `things_types` DISABLE KEYS */;
INSERT INTO `things_types` (`id`, `thing_name`, `count`) VALUES
	(0, 'пылесос', 130),
	(1, 'шахматы "кони-кони"', 2356),
	(2, 'пылесос моющий', 90),
	(3, 'пылесос беспроводной', 12),
	(4, 'очки солнцезащитные', 200),
	(5, 'очки прозрачные', 250),
	(6, 'очки-тренажеры', 20),
	(7, 'кепка "СЧАСТЛИВЧИК"', 1000),
	(8, 'кепка "ЧЕРНЫЙ ДЖЕК"', 1500),
	(9, 'бейсболка "VICTORIA"', 100),
	(10, 'бесболка "VEGAS POVER"', 1050),
	(11, 'бесболка "DESERT GOLD"', 540),
	(12, 'набор игральных карт "сингапур"', 440),
	(13, 'набор игральных карт "манчестерское ограбление"', 577),
	(14, 'набор игральных карт "doctor MONKEY"', 1800),
	(15, 'набор игральных карт "vegas\'s wings"', 144),
	(16, 'набор игральных карт "eldorado\'s gold"', 13),
	(17, 'набор игральных карт "Якутские Алмазы"', 777),
	(18, 'набор игральных карт "бинго!"', 8432),
	(19, 'набор для покера "капитан Победа"', 1324),
	(20, 'набор для покера "texas holdEm"', 836),
	(21, 'набор для покера "vegas blunsh"', 1244),
	(22, 'набор для покера "mad MAXXX"', 145),
	(23, 'набор для покера "future lottery"', 1345),
	(24, 'набор для покера "fortune\'s fury"', 532),
	(25, 'набор для покера "echo GIFT"', 5232),
	(26, 'шахматы любительские', 5423),
	(27, 'шахматы "king\'s battle"', 324),
	(28, 'шахматы "Artur the king"', 2566),
	(29, 'шахматы сувенирные "рюмочки"', 345),
	(30, 'автомобиль Lamborghini Murcielago', 3);
/*!40000 ALTER TABLE `things_types` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
