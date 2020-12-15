# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.26)
# Database: invoice_generator
# Generation Time: 2020-12-15 12:28:05 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table tblcompany
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblcompany`;

CREATE TABLE `tblcompany` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `address1` varchar(200) DEFAULT NULL,
  `address2` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `phone` varchar(200) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `adddate` datetime DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table tblcompanyyear
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblcompanyyear`;

CREATE TABLE `tblcompanyyear` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `years` varchar(20) DEFAULT NULL,
  `is_completed` tinyint(1) DEFAULT '0',
  `company_id` int(11) DEFAULT NULL,
  `year_start_from_month` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table tblcurrencies
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblcurrencies`;

CREATE TABLE `tblcurrencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(100) DEFAULT NULL,
  `currency` varchar(100) DEFAULT NULL,
  `code` varchar(25) DEFAULT NULL,
  `symbol` varchar(25) DEFAULT NULL,
  `thousand_separator` varchar(10) DEFAULT NULL,
  `decimal_separator` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `tblcurrencies` WRITE;
/*!40000 ALTER TABLE `tblcurrencies` DISABLE KEYS */;

INSERT INTO `tblcurrencies` (`id`, `country`, `currency`, `code`, `symbol`, `thousand_separator`, `decimal_separator`)
VALUES
	(1,'Albania','Leke','ALL','Lek',',','.'),
	(2,'America','Dollars','USD','$',',','.'),
	(3,'Afghanistan','Afghanis','AF','؋',',','.'),
	(4,'Argentina','Pesos','ARS','$',',','.'),
	(5,'Aruba','Guilders','AWG','ƒ',',','.'),
	(6,'Australia','Dollars','AUD','$',',','.'),
	(7,'Azerbaijan','New Manats','AZ','ман',',','.'),
	(8,'Bahamas','Dollars','BSD','$',',','.'),
	(9,'Barbados','Dollars','BBD','$',',','.'),
	(10,'Belarus','Rubles','BYR','p.',',','.'),
	(11,'Belgium','Euro','EUR','€',',','.'),
	(12,'Beliz','Dollars','BZD','BZ$',',','.'),
	(13,'Bermuda','Dollars','BMD','$',',','.'),
	(14,'Bolivia','Bolivianos','BOB','$b',',','.'),
	(15,'Bosnia and Herzegovina','Convertible Marka','BAM','KM',',','.'),
	(16,'Botswana','Pula\'s','BWP','P',',','.'),
	(17,'Bulgaria','Leva','BG','лв',',','.'),
	(18,'Brazil','Reais','BRL','R$',',','.'),
	(19,'Britain (United Kingdom)','Pounds','GBP','£',',','.'),
	(20,'Brunei Darussalam','Dollars','BND','$',',','.'),
	(21,'Cambodia','Riels','KHR','៛',',','.'),
	(22,'Canada','Dollars','CAD','$',',','.'),
	(23,'Cayman Islands','Dollars','KYD','$',',','.'),
	(24,'Chile','Pesos','CLP','$',',','.'),
	(25,'China','Yuan Renminbi','CNY','¥',',','.'),
	(26,'Colombia','Pesos','COP','$',',','.'),
	(27,'Costa Rica','Colón','CRC','₡',',','.'),
	(28,'Croatia','Kuna','HRK','kn',',','.'),
	(29,'Cuba','Pesos','CUP','₱',',','.'),
	(30,'Cyprus','Euro','EUR','€',',','.'),
	(31,'Czech Republic','Koruny','CZK','Kč',',','.'),
	(32,'Denmark','Kroner','DKK','kr',',','.'),
	(33,'Dominican Republic','Pesos','DOP ','RD$',',','.'),
	(34,'East Caribbean','Dollars','XCD','$',',','.'),
	(35,'Egypt','Pounds','EGP','£',',','.'),
	(36,'El Salvador','Colones','SVC','$',',','.'),
	(37,'England (United Kingdom)','Pounds','GBP','£',',','.'),
	(38,'Euro','Euro','EUR','€',',','.'),
	(39,'Falkland Islands','Pounds','FKP','£',',','.'),
	(40,'Fiji','Dollars','FJD','$',',','.'),
	(41,'France','Euro','EUR','€',',','.'),
	(42,'Ghana','Cedis','GHC','¢',',','.'),
	(43,'Gibraltar','Pounds','GIP','£',',','.'),
	(44,'Greece','Euro','EUR','€',',','.'),
	(45,'Guatemala','Quetzales','GTQ','Q',',','.'),
	(46,'Guernsey','Pounds','GGP','£',',','.'),
	(47,'Guyana','Dollars','GYD','$',',','.'),
	(48,'Holland (Netherlands)','Euro','EUR','€',',','.'),
	(49,'Honduras','Lempiras','HNL','L',',','.'),
	(50,'Hong Kong','Dollars','HKD','$',',','.'),
	(51,'Hungary','Forint','HUF','Ft',',','.'),
	(52,'Iceland','Kronur','ISK','kr',',','.'),
	(53,'India','Rupees','INR','Rp',',','.'),
	(54,'Indonesia','Rupiahs','IDR','Rp',',','.'),
	(55,'Iran','Rials','IRR','﷼',',','.'),
	(56,'Ireland','Euro','EUR','€',',','.'),
	(57,'Isle of Man','Pounds','IMP','£',',','.'),
	(58,'Israel','New Shekels','ILS','₪',',','.'),
	(59,'Italy','Euro','EUR','€',',','.'),
	(60,'Jamaica','Dollars','JMD','J$',',','.'),
	(61,'Japan','Yen','JPY','¥',',','.'),
	(62,'Jersey','Pounds','JEP','£',',','.'),
	(63,'Kazakhstan','Tenge','KZT','лв',',','.'),
	(64,'Korea (North)','Won','KPW','₩',',','.'),
	(65,'Korea (South)','Won','KRW','₩',',','.'),
	(66,'Kyrgyzstan','Soms','KGS','лв',',','.'),
	(67,'Laos','Kips','LAK','₭',',','.'),
	(68,'Latvia','Lati','LVL','Ls',',','.'),
	(69,'Lebanon','Pounds','LBP','£',',','.'),
	(70,'Liberia','Dollars','LRD','$',',','.'),
	(71,'Liechtenstein','Switzerland Francs','CHF','CHF',',','.'),
	(72,'Lithuania','Litai','LTL','Lt',',','.'),
	(73,'Luxembourg','Euro','EUR','€',',','.'),
	(74,'Macedonia','Denars','MKD','ден',',','.'),
	(75,'Malaysia','Ringgits','MYR','RM',',','.'),
	(76,'Malta','Euro','EUR','€',',','.'),
	(77,'Mauritius','Rupees','MUR','₨',',','.'),
	(78,'Mexico','Pesos','MX','$',',','.'),
	(79,'Mongolia','Tugriks','MNT','₮',',','.'),
	(80,'Mozambique','Meticais','MZ','MT',',','.'),
	(81,'Namibia','Dollars','NAD','$',',','.'),
	(82,'Nepal','Rupees','NPR','₨',',','.'),
	(83,'Netherlands Antilles','Guilders','ANG','ƒ',',','.'),
	(84,'Netherlands','Euro','EUR','€',',','.'),
	(85,'New Zealand','Dollars','NZD','$',',','.'),
	(86,'Nicaragua','Cordobas','NIO','C$',',','.'),
	(87,'Nigeria','Nairas','NG','₦',',','.'),
	(88,'North Korea','Won','KPW','₩',',','.'),
	(89,'Norway','Krone','NOK','kr',',','.'),
	(90,'Oman','Rials','OMR','﷼',',','.'),
	(91,'Pakistan','Rupees','PKR','₨',',','.'),
	(92,'Panama','Balboa','PAB','B/.',',','.'),
	(93,'Paraguay','Guarani','PYG','Gs',',','.'),
	(94,'Peru','Nuevos Soles','PE','S/.',',','.'),
	(95,'Philippines','Pesos','PHP','Php',',','.'),
	(96,'Poland','Zlotych','PL','zł',',','.'),
	(97,'Qatar','Rials','QAR','﷼',',','.'),
	(98,'Romania','New Lei','RO','lei',',','.'),
	(99,'Russia','Rubles','RUB','руб',',','.'),
	(100,'Saint Helena','Pounds','SHP','£',',','.'),
	(101,'Saudi Arabia','Riyals','SAR','﷼',',','.'),
	(102,'Serbia','Dinars','RSD','Дин.',',','.'),
	(103,'Seychelles','Rupees','SCR','₨',',','.'),
	(104,'Singapore','Dollars','SGD','$',',','.'),
	(105,'Slovenia','Euro','EUR','€',',','.'),
	(106,'Solomon Islands','Dollars','SBD','$',',','.'),
	(107,'Somalia','Shillings','SOS','S',',','.'),
	(108,'South Africa','Rand','ZAR','R',',','.'),
	(109,'South Korea','Won','KRW','₩',',','.'),
	(110,'Spain','Euro','EUR','€',',','.'),
	(111,'Sri Lanka','Rupees','LKR','₨',',','.'),
	(112,'Sweden','Kronor','SEK','kr',',','.'),
	(113,'Switzerland','Francs','CHF','CHF',',','.'),
	(114,'Suriname','Dollars','SRD','$',',','.'),
	(115,'Syria','Pounds','SYP','£',',','.'),
	(116,'Taiwan','New Dollars','TWD','NT$',',','.'),
	(117,'Thailand','Baht','THB','฿',',','.'),
	(118,'Trinidad and Tobago','Dollars','TTD','TT$',',','.'),
	(119,'Turkey','Lira','TRY','TL',',','.'),
	(120,'Turkey','Liras','TRL','£',',','.'),
	(121,'Tuvalu','Dollars','TVD','$',',','.'),
	(122,'Ukraine','Hryvnia','UAH','₴',',','.'),
	(123,'United Kingdom','Pounds','GBP','£',',','.'),
	(124,'United States of America','Dollars','USD','$',',','.'),
	(125,'Uruguay','Pesos','UYU','$U',',','.'),
	(126,'Uzbekistan','Sums','UZS','лв',',','.'),
	(127,'Vatican City','Euro','EUR','€',',','.'),
	(128,'Venezuela','Bolivares Fuertes','VEF','Bs',',','.'),
	(129,'Vietnam','Dong','VND','₫',',','.'),
	(130,'Yemen','Rials','YER','﷼',',','.'),
	(131,'Zimbabwe','Zimbabwe Dollars','ZWD','Z$',',','.');

/*!40000 ALTER TABLE `tblcurrencies` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tblcustomers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblcustomers`;

CREATE TABLE `tblcustomers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(200) DEFAULT NULL,
  `company_name` varchar(200) DEFAULT NULL,
  `address1` varchar(200) DEFAULT NULL,
  `address2` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `phone` varchar(200) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `adddate` datetime DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table tblinvoice
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblinvoice`;

CREATE TABLE `tblinvoice` (
  `id` bigint(50) NOT NULL AUTO_INCREMENT,
  `invoiceId` bigint(50) NOT NULL,
  `invoicedate` datetime DEFAULT NULL,
  `invoiceduedate` datetime DEFAULT NULL,
  `currencysign` int(11) DEFAULT NULL,
  `types` enum('Hourly','Quantity') DEFAULT NULL,
  `fromcompanyid` int(11) DEFAULT NULL,
  `tocompanyid` int(11) DEFAULT NULL,
  `adddate` datetime DEFAULT NULL,
  `subtotal` decimal(11,2) DEFAULT NULL,
  `taxtotal` decimal(11,2) DEFAULT NULL,
  `discounttotal` decimal(11,2) DEFAULT NULL,
  `discounttype` enum('Percent','Amount') DEFAULT NULL,
  `discountvalue` decimal(11,2) DEFAULT NULL,
  `grandtotal` decimal(11,2) DEFAULT NULL,
  `ispaid` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoiceId` (`invoiceId`,`fromcompanyid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table tblinvoiceitem
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblinvoiceitem`;

CREATE TABLE `tblinvoiceitem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoiceid` int(11) DEFAULT NULL,
  `itemname` varchar(255) NOT NULL,
  `qnty1` decimal(11,2) DEFAULT NULL,
  `qnty2` decimal(11,2) DEFAULT NULL,
  `priceperunit` decimal(11,2) DEFAULT NULL,
  `discountpercent` decimal(11,2) DEFAULT NULL,
  `dicountamount` decimal(11,2) DEFAULT NULL,
  `taxpercent` decimal(11,2) DEFAULT NULL,
  `taxtotal` decimal(11,2) DEFAULT NULL,
  `subtotal` decimal(11,2) DEFAULT NULL,
  `grandtotal` decimal(11,2) DEFAULT NULL,
  `adddate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table tbluser
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tbluser`;

CREATE TABLE `tbluser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `isactive` tinyint(1) DEFAULT '1',
  `is_super_admin` tinyint(1) DEFAULT '0',
  `email` varchar(50) DEFAULT NULL,
  `forgot_token` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `is_email_verified` tinyint(1) DEFAULT '0',
  `email_verify_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
