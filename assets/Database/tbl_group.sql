-- --------------------------------------------------------
-- Host:                         192.168.40.12
-- Server version:               5.5.60-0ubuntu0.14.04.1 - (Ubuntu)
-- Server OS:                    debian-linux-gnu
-- HeidiSQL Version:             11.0.0.6099
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for wasco_fingerman
CREATE DATABASE IF NOT EXISTS `wasco_fingerman` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `wasco_fingerman`;

-- Dumping structure for table wasco_fingerman.tblfile_group
CREATE TABLE IF NOT EXISTS `tblfile_group` (
  `idgroup` bigint(100) NOT NULL AUTO_INCREMENT,
  `groupdesc` varchar(30) NOT NULL DEFAULT '',
  `stsactive` smallint(6) DEFAULT NULL,
  `iduser_create` varchar(15) DEFAULT NULL,
  `createdate` datetime DEFAULT NULL,
  `iduser_update` varchar(15) DEFAULT NULL,
  `updatedate` datetime DEFAULT NULL,
  PRIMARY KEY (`idgroup`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;

-- Dumping data for table wasco_fingerman.tblfile_group: ~54 rows (approximately)
/*!40000 ALTER TABLE `tblfile_group` DISABLE KEYS */;
INSERT INTO `tblfile_group` (`idgroup`, `groupdesc`, `stsactive`, `iduser_create`, `createdate`, `iduser_update`, `updatedate`) VALUES
	(1, 'WEI', 1, 'RIO', '2014-12-12 17:05:47', NULL, NULL),
	(2, 'SSG', 1, 'BETI', '2015-08-31 13:34:17', NULL, NULL),
	(3, 'STARINDO', 1, 'BETI', '2015-08-31 13:34:17', 'BETI', '2016-04-19 08:06:38'),
	(4, 'KHANTHAS PASIFIK', 1, 'BETI', '2015-08-31 13:34:17', NULL, NULL),
	(5, 'FARA\'S', 1, 'BETI', '2015-09-03 13:59:26', NULL, NULL),
	(6, 'DYNATECH', 1, 'BETI', '2015-09-04 08:35:17', NULL, NULL),
	(7, 'UNITED GLOBAL FORCE', 1, 'BETI', '2015-09-04 08:35:57', NULL, NULL),
	(8, 'MSP', 1, 'BETI', '2015-09-04 08:36:18', NULL, NULL),
	(9, 'SPCO', 1, 'BETI', '2015-09-04 08:36:39', NULL, NULL),
	(10, 'COOL DFINE', 1, 'BETI', '2015-09-04 08:36:52', NULL, NULL),
	(11, 'NEXELITE', 1, 'BETI', '2015-09-04 08:37:14', NULL, NULL),
	(12, 'SRIWIJAYA', 1, 'BETI', '2015-09-05 10:49:28', NULL, NULL),
	(13, 'GPI', 1, 'BETI', '2015-09-08 15:59:27', NULL, NULL),
	(14, 'WSEF/WSET', 1, 'BETI', '2015-09-11 11:35:34', NULL, NULL),
	(15, 'INTERNSHIP', 1, 'BETI', '2015-09-15 10:45:26', NULL, NULL),
	(16, 'HANINDO', 1, 'BETI', '2015-09-15 12:57:28', NULL, NULL),
	(17, 'SECURITY', 1, 'BETI', '2015-09-17 15:53:08', NULL, NULL),
	(18, 'SRIWIJAYA 1', 1, 'BETI', '2015-09-25 09:34:38', NULL, NULL),
	(19, 'TRI SUKSES ENGINEERING', 1, 'MISNA', '2015-12-01 09:08:22', NULL, NULL),
	(20, 'TATA MURDAYA BERSAMA', 1, 'MISNA', '2015-12-01 09:08:38', NULL, NULL),
	(21, 'MBG', 1, 'BETI', '2015-12-28 13:56:00', NULL, NULL),
	(22, 'SAVIRA', 1, 'BETI', '2016-01-05 09:56:28', NULL, NULL),
	(23, 'CONSULTANT', 1, 'BETI', '2016-02-15 16:15:51', NULL, NULL),
	(24, 'SAP CONSULTANT', 1, 'BETI', '2016-02-24 13:21:41', NULL, NULL),
	(25, 'BATAM SUKSES', 1, 'BETI', '2016-04-19 08:06:07', NULL, NULL),
	(26, 'SSG (PO)', 1, 'BETI', '2016-04-21 15:01:25', NULL, NULL),
	(27, 'IWW KINETIK ENERGI', 1, 'BETI', '2016-05-04 14:32:02', NULL, NULL),
	(28, 'SEAWOLF', 1, 'BETI', '2016-06-10 14:00:00', NULL, NULL),
	(29, 'DYNATECH (SUPPLY)', 1, 'BETI', '2016-06-22 14:45:46', NULL, NULL),
	(30, 'FIRST AED', 1, 'BETI', '2016-10-10 13:28:56', NULL, NULL),
	(31, 'WELLSUNLI SUKSES INDONESIA', 1, 'BETI', '2017-01-09 09:58:44', NULL, NULL),
	(32, 'GENERAL MACHINERY', 1, 'BETI', '2017-01-11 08:46:32', NULL, NULL),
	(33, 'CYCLECT BATAM', 1, 'BETI', '2017-05-16 15:32:05', NULL, NULL),
	(34, 'RADIAN', 1, 'BETI', '2017-06-12 13:45:27', NULL, NULL),
	(35, 'TWC', 1, 'BETI', '2017-06-13 10:38:47', NULL, NULL),
	(36, 'PUTRA BATAM JASA MANDIRI UTAMA', 1, 'BETI', '2017-06-22 10:44:05', NULL, NULL),
	(37, 'ANGKASA INDO JAYA', 1, 'BETI', '2017-07-18 10:07:24', NULL, NULL),
	(38, 'BASSAIRE', 1, 'BETI', '2017-08-01 14:26:55', NULL, NULL),
	(39, 'HITEK', 1, 'BETI', '2017-09-26 09:10:33', NULL, NULL),
	(40, 'JOHNSON CONTROL', 1, 'BETI', '2017-11-03 10:25:02', NULL, NULL),
	(41, 'GLOBAL TIMBER INDO', 1, 'BETI', '2017-11-10 15:18:43', NULL, NULL),
	(42, 'VINNEX COATINDO', 1, 'BETI', '2017-12-08 15:46:44', NULL, NULL),
	(43, 'CIPTA KARYA TUNGGAL', 1, 'BETI', '2018-01-31 16:15:00', NULL, NULL),
	(44, 'BEREAU VERITAS', 1, 'BETI', '2018-04-30 15:52:18', NULL, NULL),
	(45, 'GLOBAL AUTOMATION', 1, 'BETI', '2018-08-07 08:32:59', NULL, NULL),
	(46, 'CAST LAB', NULL, NULL, NULL, NULL, NULL),
	(47, 'KURNIA RAYA SURVINDO', 1, 'BETI', '2019-09-30 15:06:02', 'BETI', '2019-09-30 15:06:20'),
	(48, 'WEGL', NULL, NULL, NULL, NULL, NULL),
	(49, 'PRIMA KARYA PONDASI', 1, 'BETI', '2020-02-20 15:15:54', NULL, NULL),
	(50, 'MULTI HARAPAN BERSAMA', 1, 'BETI', '2020-04-22 15:10:41', NULL, NULL),
	(51, 'ARCA BIMASAKTI', 1, 'BETI', '2020-06-18 15:11:03', NULL, NULL),
	(52, 'JL MARINE', 1, 'BETI', '2020-08-07 10:35:01', NULL, NULL),
	(53, 'TARPON', 1, 'BETI', '2020-08-14 15:06:00', NULL, NULL),
	(54, 'SMOE', 1, 'BETI', '2020-09-10 13:29:19', NULL, NULL);
/*!40000 ALTER TABLE `tblfile_group` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
