-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.31-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for pdo_cus_b11
CREATE DATABASE IF NOT EXISTS `pdo_cus_b11` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `pdo_cus_b11`;

-- Dumping structure for table pdo_cus_b11.blog
CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `detail` longtext,
  `image` longtext,
  `create_date` timestamp NULL DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- Dumping data for table pdo_cus_b11.blog: ~7 rows (approximately)
/*!40000 ALTER TABLE `blog` DISABLE KEYS */;
INSERT INTO `blog` (`id`, `title`, `description`, `detail`, `image`, `create_date`, `user_id`, `status`) VALUES
	(1, 'Apple ឲ្យប្រើអត់យកលុយ សេវា iCloud ទំហំ​ដល់ ២០០០ GB', '<p>ដើម្បីបង្កើនចំនួនអ្នកប្រើប្រាស់សេវាកម្ម iCloud ក្រុមហ៊ុន Apple បានចេញ Promotion ថ្មី ដោយ​ផ្ដល់ជូននូវការប្រើប្រាស់សេវាកម្មនេះឥតគិតថ្លៃចំនួនមួយខែ ដល់អ្នកប្រើប្រាស់ទាំងឡាយ។</p>', '<p>ដើម្បីបង្កើនចំនួនអ្នកប្រើប្រាស់សេវាកម្ម iCloud ក្រុមហ៊ុន Apple បានចេញ Promotion ថ្មី ដោយ​ផ្ដល់ជូននូវការប្រើប្រាស់សេវាកម្មនេះឥតគិតថ្លៃចំនួនមួយខែ ដល់អ្នកប្រើប្រាស់ទាំងឡាយ។</p>\r\n\r\n<p>នៅពេលអ្នកប្រើផលិតផល iDevice (មាន iPhone, iPad ឬ iPod) មិនមានសល់ទំហំមេមូរី iCloud ដើម្បីធ្វើការ Backup នឹងមានសារថ្មីដែលផ្សាយពីការផ្ដល់ជូនឥតគិតថ្លៃនេះ។ ការប្រើឥតគិតថ្លៃនេះមានចាប់ពី ៥០ GB ដល់ ២ TB សម្រាប់រយៈពេលមួយខែដំបូង។</p>\r\n\r\n<p>ដូចជាសេវាកម្ម Apple ផ្សេងទៀតដែរ សេវាកម្ម iCloud នេះនឹងចាប់ផ្ដើមគិតលុយដោយស្វ័យប្រវត្តិនៅខែទីពីរ ក្រោយពីការប្រើប្រាស់ខែទីមួយបានបញ្ចប់។ ប៉ុន្តែអ្នកប្រើប្រាស់ក៏អាចមានសិទ្ធមិនបន្តប្រើប្រាស់បានផងដែរ។</p>', '5b08f31f7b869_1527313140_large.jpg', '2018-05-21 00:00:00', 1, 1),
	(2, 'ពុលទៀតហើយ ! ពលរដ្ឋខេត្តក្រចេះ ៧៥នាក់ ពុលចំណីមួយប្រភេទ', '<p>តាម​របាយការណ៍​បឋម​របស់​មន្ត្រី​សុខាភិបាល​ខេត្ត​ក្រចេះ ​មាន​​ពលរដ្ឋ​ចំនួន ៧៥​នាក់ នៅ​ភូមិ​ត្រពាំង​ទ្រាំង ឃុំ​ចង្ក្រង់ ស្រុក​ចិត្របុរី&nbsp; សង្ស័យ​ថា​ពុល​ចំណី​ បន្ទាប់​ពី​អ្នក​ទាំង​អស់​នោះ​បាន​ហូប​នំប៉័ង​នៅ​ក្នុង​ពិធី​ប្រជុំ​ផ្សព្វផ្សាយ​ពិគ្រោះ​យោប', '<p>តាម​របាយការណ៍​បឋម​របស់​មន្ត្រី​សុខាភិបាល​ខេត្ត​ក្រចេះ ​មាន​​ពលរដ្ឋ​ចំនួន ៧៥​នាក់ នៅ​ភូមិ​ត្រពាំង​ទ្រាំង ឃុំ​ចង្ក្រង់ ស្រុក​ចិត្របុរី&nbsp; សង្ស័យ​ថា​ពុល​ចំណី​ បន្ទាប់​ពី​អ្នក​ទាំង​អស់​នោះ​បាន​ហូប​នំប៉័ង​នៅ​ក្នុង​ពិធី​ប្រជុំ​ផ្សព្វផ្សាយ​ពិគ្រោះ​យោបល់ ស្ដីពី​ការ​ជំរុញ​ទីផ្សារ​ខ្នាត​តូច​ផលិតផល​ដំឡូងមី កាល​ពីល្ងាច​ថ្ងៃទី ២៥ ឧសភា ឆ្នាំ ២០១៨។</p>\r\n\r\n<p>អ្នក​ជំងឺ​ទាំងអស់​នោះ មាន​អាការៈ​ ឈឺ​ក្អួត​ចង្អោរ និង​រាគ​ និង​​ត្រូវ​បាន​បញ្ជូន​ទៅ​សម្រាក​ព្យាបាល​នៅ​មន្ទីរពេទ្យ​ខេត្ត និង​នៅ​មណ្ឌល​សុខភាព​ខេត្ត​ក្រចេះ។</p>', '5b08e76443282_1527310140_large.jpg', '2018-05-21 00:00:00', 1, 1),
	(3, 'ថតរូបជាមួយម្តាយ តាំងពីចូលរៀនរហូតដល់ចប់ តែរូបចុងក្រោយធ្វើឲ្យគ្រប់គ្នា ទប់ទឹកភ្នែកមិនជាប់', '<p>នារី​វ័យ​ក្មេង​ប្រើប្រាស់​បណ្ដាញ​សង្គម Twitter ម្នាក់​មាន​គណនី​ឈ្មោះ @nanatat07 បាន​ចងក្រង​នូវ​អនុស្សាវរីយ៍​ដែល​មិន​អាច​បំភ្លេច​បាន ដោយ​ថត​រូប​ជាមួយ​ម្ដាយ​នាង​អស់​រយៈពេល ជាង​៤ឆ្នាំ ប៉ុន្តែ​រូបថត​ចុង​ក្រោយ​មួយ​សន្លឹក ធ្វើ​ឲ្យ​គ្រប់​គ្នា​ទប់​ទឹក​ភ្ន', '<p>នារី​វ័យ​ក្មេង​ប្រើប្រាស់​បណ្ដាញ​សង្គម Twitter ម្នាក់​មាន​គណនី​ឈ្មោះ @nanatat07 បាន​ចងក្រង​នូវ​អនុស្សាវរីយ៍​ដែល​មិន​អាច​បំភ្លេច​បាន ដោយ​ថត​រូប​ជាមួយ​ម្ដាយ​នាង​អស់​រយៈពេល ជាង​៤ឆ្នាំ ប៉ុន្តែ​រូបថត​ចុង​ក្រោយ​មួយ​សន្លឹក ធ្វើ​ឲ្យ​គ្រប់​គ្នា​ទប់​ទឹក​ភ្នែក​មិន​ជាប់។</p>\r\n\r\n<p>នាង​បាន​សរសេរ​សារ​មួយ​ថា &laquo;កាល​ពី​ម្សិលមិញ គឺ​ជា​ថ្ងៃ​ចុងក្រោយ​នៃ​ថ្នាក់​វិទ្យាល័យ​របស់​ខ្ញុំ។ អស់​រយៈពេល ៤ឆ្នាំ ខ្ញុំ​បាន​ថត​រូប​ជាមួយ​ម្ដាយ​ខ្ញុំ តាំង​ពី​ថ្ងៃ​ចូល​រៀន​ដំបូង រហូត​ដល់​ថ្ងៃ​បញ្ចប់។ គាត់​មិន​អាច​រង់​ចាំ រហូត​ដល់​រូបថត​ចុង​ក្រោយ​ឡើយ ប៉ុន្តែ​កម្លាំង​របស់​គាត់​បាន​ជំរុញ​ខ្ញុំ ឲ្យ​នៅ​តែ​រឹងមាំ​ក្នុង​អំឡុង​ពេល​នេះ។ ការ​ខិតខំ​ប្រឹងប្រែង​របស់​ខ្ញុំ​ទាំង​ប៉ុន្មាន គឺ​ដើម្បី​គាត់&raquo;។</p>\r\n\r\n<p>គួរ​បញ្ជាក់​ថា ក្នុង​រយៈ​ប៉ុន្មាន​ថ្ងៃ​ប៉ុណ្ណោះ សារ​មួយ​នេះ​ត្រូវ​បាន​គេ​ចុច Like ជាង ៥សែន​ដង និង​ចែកចាយ​បន្ត ជាង ១សែន​ដង​ទៀត​ផង៕</p>', '5b0901c3ed01b_1527316920_large.jpg', '2018-05-21 00:00:00', 1, 1),
	(4, 'សត្វទុង​២​ក្បាល​ ហើរ​ចូល​អុកឡុក​ពេញ​ពិធី​ចែកសញ្ញាបត្រ​ ផ្អើល​អស់​អ្នក​ចូលរួម(វីដេអូ)', '<p>ពិធី​ទទួល​​សញ្ញាបត្រ​គឺ​ជា​ពេលវេលា​ដ៏​រំភើប​បំផុត​ក្នុង​ជីវិត​ជា​និស្សិត ហើយវា​កាន់​តែ​រំភើប​សម្រាប់​និស្សិត​នៅ​សាកលវិទ្យាល័យ​មួយ​នៅ​អាមេរិក ដោយ​បាន​​ទទួល​ភ្ញៀវ​២​នាក់​ឥត​ព្រាងទុក គឺ​ជា​សត្វ​ទុង​២​ក្បាល​​ហើរ​ចុះ​កណ្ដាល​រោងពិធី ផ្អើល​អស់​អ្នក​ចូល​រ', '<p>ពិធី​ទទួល​​សញ្ញាបត្រ​គឺ​ជា​ពេលវេលា​ដ៏​រំភើប​បំផុត​ក្នុង​ជីវិត​ជា​និស្សិត ហើយវា​កាន់​តែ​រំភើប​សម្រាប់​និស្សិត​នៅ​សាកលវិទ្យាល័យ​មួយ​នៅ​អាមេរិក ដោយ​បាន​​ទទួល​ភ្ញៀវ​២​នាក់​ឥត​ព្រាងទុក គឺ​ជា​សត្វ​ទុង​២​ក្បាល​​ហើរ​ចុះ​កណ្ដាល​រោងពិធី ផ្អើល​អស់​អ្នក​ចូល​រួម។​</p>\r\n\r\n<p>ប្រហែល​​ភ្ញៀវ​២​នាក់​នេះ​ទាក់ទាញ ដោយ​សារ​ឈុត​ទទួល​សញ្ញាបត្រ​ពណ៌​ទឹក​សមុទ្រ​របស់​និស្សិត​ប្រមាណ​៨០០​នាក់ នៃ​សាកល​វិទ្យាល័យ Pepperdine រួម​និង​ទិដ្ឋភាព​ដូច​កម្រាលព្រំ​ចម្រុះ​ពណ៌​នៃ​​អ្នក​ចូលរួម​ប្រមាណ ១​ម៉ឺន​នាក់​ទៀត ទើប​ធ្វើ​ឲ្យ​ពួកគេ​ចុះ​ចតកណ្ដាល​ហ្វូង​មនុស្ស។</p>', '5b09075c1f654_1527318360_large.jpg', '2018-05-21 00:00:00', 1, 1),
	(5, 'ស្មាតហ្វូនអនាគតរបស់ Vivo ត្រូវប្រទះឃើញប្រើនៅទីសាធារណៈ', '<p>ស្មាតហ្វូនថ្ងៃអនាគតរបស់​ Vivo ដែល​​មាន​ឈ្មោះ​ថា​ Apex មាន​អេក្រង់​លាត​ពេញ​ផ្នែក​ខាង​មុខ គ្មាន​ក្បាល​ឆក​ ត្រូវ​បាន​គេ​ប្រទះ​ឃើញ​កាន់​ប្រើ​នៅ​ទីសាធារណៈ​នៅ​ប្រទេស​ចិន។</p>', '<p>ស្មាតហ្វូនថ្ងៃអនាគតរបស់​ Vivo ដែល​​មាន​ឈ្មោះ​ថា​ Apex មាន​អេក្រង់​លាត​ពេញ​ផ្នែក​ខាង​មុខ គ្មាន​ក្បាល​ឆក​ ត្រូវ​បាន​គេ​ប្រទះ​ឃើញ​កាន់​ប្រើ​នៅ​ទីសាធារណៈ​នៅ​ប្រទេស​ចិន។</p>\r\n\r\n<p>តាម​រយៈ​រូបភាព​ ២ សន្លឹក​ដែល​គេ​ថត​បាន​ ហាក់​មិន​មាន​បង្ហាញ​អ្វី​ច្រើន​អំពី​ស្មាតហ្វូន Apex នេះ​ទេ ប៉ុន្តែ​មាន​បង្ហាញ​ថា​វា​មាន​រន្ធ​ដោត​កាស​នៅ​ផ្នែក​ខាង​លើ ដែល​ជា​រន្ធ​ប្រភេទ 3.5mm។</p>', '5b09039a54dda_1527317400_large.jpg', '2018-05-21 00:00:00', 1, 1),
	(6, 'តារា​កម្ពុជា​៥​រូប​ព្យាករក្រុម​ដែល​អាច​ឈ្នះ​​ Champions League 2018', '<p>តារា​បាល់ទាត់​កម្ពុជា​​៥​រូប​បាន​ព្យាករ​ក្រុម​ដែល​អាច​ឈ្នះ​ពាន​រង្វាន់​​ធំ​ប្រចាំ​ទ្វីប​អឺរ៉ុប​ UEFA Champions League 2018 ខណៈ​ការ​ប្រកួត​នឹង​ត្រូវ​លេង​វេលា​មួយ​១:៤៥​នាទី​យប់​រំលង​អាធ្រាត្រ​ថ្ងៃ​សៅរ៍​ ទី​២៦​ ឧសភា​ នេះ​នៅ​​ប្រទេស​អ៊ុយក្រែន។</p>\r\n\r\n', '<p>តារា​បាល់ទាត់​កម្ពុជា​​៥​រូប​បាន​ព្យាករ​ក្រុម​ដែល​អាច​ឈ្នះ​ពាន​រង្វាន់​​ធំ​ប្រចាំ​ទ្វីប​អឺរ៉ុប​ UEFA Champions League 2018 ខណៈ​ការ​ប្រកួត​នឹង​ត្រូវ​លេង​វេលា​មួយ​១:៤៥​នាទី​យប់​រំលង​អាធ្រាត្រ​ថ្ងៃ​សៅរ៍​ ទី​២៦​ ឧសភា​ នេះ​នៅ​​ប្រទេស​អ៊ុយក្រែន។</p>\r\n\r\n<p>សម្រាប់​ព្យាករណ៍​នេះ​ កីឡាករ​កម្ពុជា​មាន​រហូត​ដល់​ទៅ​៥​នាក់​បាន​ព្យាករ​ខុស​ៗ​គ្នា​​ដែល​អាច​ឈ្នះ​ពាន​ឆ្នាំ​នេះ។ អតីត​​កីឡាករ​ភ្នំពេញ​ក្រោន កែវ សុខផេង​ និង​សមាជិក​ណាហ្គាវើល គួច សុកុម្ភៈ គិត​ថា​ ក្រុម​អធិរាជ​ស​ Real Madrid ជា​ជើង​ឯក​ឆ្នាំ​នេះ។</p>\r\n\r\n<p>ផ្ទុយ​ពី​អ្នក​ទាំង​ពីរ​ខាង​លើ ​ប្រធាន​ក្រុម​ជម្រើស​ជាតិ​កម្ពុជា​ និង​ជា​សមាជិក​បឹងកេត​ ឃួន ឡាប៊ូរ៉ាវី​ សមាជិក Terangganu FA របស់​លីគ​ម៉ាឡេស៊ី​ ធារី ចន្ថាប៊ីន​ ​និង ប្រាក់​ មុន្នីឧត្តម ​គិត​ថា​ ​ហង្សក្រហម​ Liverpool ជា​អ្នក​ឈ្នះ។</p>', 'Cambodia-National-Team-Vs-Vietnam-AFC-9_large.jpg', '2018-05-21 00:00:00', 1, 1),
	(7, 'វិភាគ​ខ្ទេច​! ​ផ្ដាច់​ព្រ័ត្រ UCL រវាង​ Real Madrid និង Liverpool យប់​នេះ', '<p>​វេលា​ម៉ោង ១:៤៥ នាទី​យប់​នេះ​ នឹង​ដឹង​ថា អ្នក​ណា​ទឹក​ភ្នែក​អ្នក​ណា​សើច​រវាង​អធិរាជ​ស ​Real Madrid និង Liverpool ក្នុង​ការ​ប្រកួត​ផ្ដាច់​ព្រ័ត្រ UEFA Champios League ​នៅ​ទីក្រុង​ Kiev ប្រទេស​អ៊ុយក្រែន។</p>', '<p>​​ម្ចាស់​ជើង​ឯក​ UCL ៥​សម័យ​កាល​ Liverpool និង​ម្ចាស់​ជើង​ឯក​ UCL ១២ សម័យ​កាល​ Real គឺ​សុទ្ធ​តែ​កំពុង​ឡើង​ជើង​ដូច​គ្នា ខណៈ​ដែល​ក្រុម​ស្ដេច​ស​ ត្រូវ​បាន​គេ​ដឹង​ថា គ្រង​ជើង​ឯក​ពាន​នេះ​ពីរ​ឆ្នាំ​ជាប់ៗ​​គ្នា​នៅ​ពីរ​ឆ្នាំ​ចុង​ក្រោយ​។ ​ចំណែក Liverpool ​វិញ​គ្រង​ជើង​ឯក​លើក​ចុង​ក្រោយ​នៅ​ឆ្នាំ​២០០៥។</p>\r\n\r\n<p>ដើម្បី​ជ្រាប​ព័ត៌មាន​ច្បាស់ និង​វិភាគ​លម្អិត​ពី​កម្មវិធី​ប្រកួត​នេះ​ សូម​មើល​វីដេអូ​វិភាគ​ខាង​ក្រោម៖</p>', '5b08d48018020_1527305340_large.jpg', '2018-05-18 00:00:00', 1, 1);
/*!40000 ALTER TABLE `blog` ENABLE KEYS */;

-- Dumping structure for table pdo_cus_b11.courses
CREATE TABLE IF NOT EXISTS `courses` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_title` varchar(250) DEFAULT NULL,
  `course_description` varchar(250) DEFAULT NULL,
  `course_detail` longtext,
  `image` varchar(250) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`course_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- Dumping data for table pdo_cus_b11.courses: ~15 rows (approximately)
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` (`course_id`, `course_title`, `course_description`, `course_detail`, `image`, `status`) VALUES
	(1, 'ថ្នាក់ទី​១', 'មេរៀន​ថ្នាក់ទី​១', NULL, 'grade1.jpg', 1),
	(2, 'ថ្នាក់ទី​២', 'មេរៀន​ថ្នាក់ទី​១', NULL, 'grade1.jpg', 1),
	(3, 'ថ្នាក់ទី​៣', 'មេរៀន​ថ្នាក់ទី​១', NULL, 'grade1.jpg', 1),
	(4, 'ថ្នាក់ទី​៤', 'មេរៀន​ថ្នាក់ទី​១', NULL, 'grade1.jpg', 1),
	(5, 'ថ្នាក់ទី​៥', 'មេរៀន​ថ្នាក់ទី​១', NULL, 'grade1.jpg', 1),
	(6, 'ថ្នាក់ទី​៦', 'មេរៀន​ថ្នាក់ទី​១', NULL, 'grade1.jpg', 1),
	(7, 'ថ្នាក់ទី​៧', 'មេរៀន​ថ្នាក់ទី​១', NULL, 'grade1.jpg', 1),
	(8, 'ថ្នាក់ទី​៨', 'មេរៀន​ថ្នាក់ទី​១', NULL, 'grade1.jpg', 1),
	(9, 'ថ្នាក់ទី​៩', 'មេរៀន​ថ្នាក់ទី​១', NULL, 'grade1.jpg', 1),
	(10, 'ថ្នាក់ទី​១០', 'មេរៀន​ថ្នាក់ទី​១', NULL, 'grade1.jpg', 1),
	(11, 'ថ្នាក់ទី​១១', 'មេរៀន​ថ្នាក់ទី​១', NULL, 'grade1.jpg', 1),
	(12, 'ថ្នាក់ទី​១២', 'មេរៀន​ថ្នាក់ទី​១', NULL, 'grade1.jpg', 1),
	(13, 'វិទ្យាសាស្រ្តកុំព្យូទ័រ', 'ពត៌មានវិទ្យា', 'សិក្សាពីវិទ្យាសាស្រ្តកុំព្យូទ័រ', 'grade1.jpg', 1),
	(14, 'មហាវិត្យាល័យគណិតវិទ្យា', 'គណិតវិទ្យា', 'សិក្សាពីវិទ្យាសាស្រ្តកុំព្យូទ័រ', 'grade1.jpg', 1),
	(15, 'អក្សរសាស្រ្ត​ខ្មែរ', 'អក្សរសាស្រ្តខ្មែរ', 'សិក្សាពីវិទ្យាសាស្រ្តកុំព្យូទ័រ', 'grade1.jpg', 1);
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;

-- Dumping structure for table pdo_cus_b11.instructor
CREATE TABLE IF NOT EXISTS `instructor` (
  `instructor_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(250) DEFAULT NULL,
  `last_name` varchar(250) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `gender` varchar(250) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `province_id` varchar(250) NOT NULL COMMENT '1',
  `skill_id` varchar(250) NOT NULL COMMENT '1,2,4,6',
  `facebook` varchar(250) DEFAULT NULL,
  `twitter` varchar(250) DEFAULT NULL,
  `gplus` varchar(250) DEFAULT NULL,
  `detail` longtext COMMENT 'detail information',
  `picture` varchar(250) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`instructor_id`,`province_id`,`skill_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table pdo_cus_b11.instructor: ~1 rows (approximately)
/*!40000 ALTER TABLE `instructor` DISABLE KEYS */;
INSERT INTO `instructor` (`instructor_id`, `first_name`, `last_name`, `name`, `gender`, `address`, `province_id`, `skill_id`, `facebook`, `twitter`, `gplus`, `detail`, `picture`, `status`) VALUES
	(1, 'Seng', 'Sourng', 'Seng Sourng', 'M', 'Siem Reap', '1', '1,2,4,6', 'sensourng', '#', '#', 'Keep Accurate and Up-to-Date Records, Log, Record, Itemize, Collate, Tabulate Data.', '1.jpg', 1);
/*!40000 ALTER TABLE `instructor` ENABLE KEYS */;

-- Dumping structure for table pdo_cus_b11.lesson
CREATE TABLE IF NOT EXISTS `lesson` (
  `lesson_id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) NOT NULL,
  `title` varchar(250) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `detail` varchar(250) DEFAULT NULL,
  `video` varchar(250) DEFAULT NULL,
  `reference` varchar(250) DEFAULT NULL,
  `image` varchar(250) DEFAULT NULL,
  `status` varchar(250) DEFAULT NULL,
  `create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`lesson_id`,`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table pdo_cus_b11.lesson: ~1 rows (approximately)
/*!40000 ALTER TABLE `lesson` DISABLE KEYS */;
INSERT INTO `lesson` (`lesson_id`, `subject_id`, `title`, `description`, `detail`, `video`, `reference`, `image`, `status`, `create_date`) VALUES
	(1, 13, 'មេរៀនទី១ របៀបភ្ជាប់ Menu', 'Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place of English to emphasise design elements over content. It\'s also called placeholder (or filler) text. It\'s a convenient tool for mock-ups. It helps to out', 'Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place of English to emphasise design elements over content. It\'s also called placeholder (or filler) text. It\'s a convenient tool for mock-ups. It helps to out', '<iframe width="560" height="315" src="https://www.youtube.com/embed/QlwnY-3URK8" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>', 'W3School', '1.jpg', '1', '2018-05-26 15:07:00');
/*!40000 ALTER TABLE `lesson` ENABLE KEYS */;

-- Dumping structure for table pdo_cus_b11.marketing
CREATE TABLE IF NOT EXISTS `marketing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `detail` longtext,
  `image` varchar(250) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table pdo_cus_b11.marketing: ~3 rows (approximately)
/*!40000 ALTER TABLE `marketing` DISABLE KEYS */;
INSERT INTO `marketing` (`id`, `title`, `description`, `detail`, `image`, `status`) VALUES
	(1, 'ដាក់ពាក្យ​ចូល​រៀន', 'ដាក់ពាក្យ​ចូល​រៀនសម្រាប់ឆ្នាំ​ថ្មី', 'ដាក់ពាក្យ​ចូល​រៀនសម្រាប់ឆ្នាំ​ថ្មី', 'marketing1.jpg', 1),
	(2, 'បណ្តុះ​បណ្តាល​', 'បណ្តុះ​បណ្តាល​បណ្តុះ​បណ្តាល​', 'បណ្តុះ​បណ្តាល​បណ្តុះ​បណ្តាល​បណ្តុះ​បណ្តាល​បណ្តុះ​បណ្តាល​បណ្តុះ​បណ្តាល​បណ្តុះ​បណ្តាល​', 'marketing2.jpg', 1),
	(3, 'ទទួល​ស្កាល់​', 'ទទួល​ស្កាល់​ ទទួល​ស្កាល់​', 'ទទួល​ស្កាល់​ ទទួល​ស្កាល់​ទទួល​ស្កាល់​ទទួល​ស្កាល់​ទទួល​ស្កាល់​', 'marketing3.jpg', 1);
/*!40000 ALTER TABLE `marketing` ENABLE KEYS */;

-- Dumping structure for table pdo_cus_b11.skill
CREATE TABLE IF NOT EXISTS `skill` (
  `skill_id` int(11) NOT NULL AUTO_INCREMENT,
  `skill_title` varchar(250) DEFAULT NULL,
  `skill_detail` longtext,
  `skill_image` varchar(250) DEFAULT NULL,
  `status` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`skill_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- Dumping data for table pdo_cus_b11.skill: ~8 rows (approximately)
/*!40000 ALTER TABLE `skill` DISABLE KEYS */;
INSERT INTO `skill` (`skill_id`, `skill_title`, `skill_detail`, `skill_image`, `status`) VALUES
	(1, 'COMPUTER LITERATE', 'Develop, Organize and Complete Tasks and Projects Using Software Programs Such as Word, Excel and PowerPoint.', '1.jpg', '1'),
	(2, 'PLAN, ORGANIZE', 'Define Goals and Objectives, Schedule and Develop Projects or Programs.', '2.jpg', '1'),
	(3, 'OBSERVE', 'Study, Scrutinize, Examine Data, People, or Things Scientifically.', '3.jpg', '1'),
	(4, 'MAINTAIN RECORDS', 'Keep Accurate and Up-to-Date Records, Log, Record, Itemize, Collate, Tabulate Data.', '4.jpg', '1'),
	(5, 'Adverising', 'Keep Accurate and Up-to-Date Records, Log, Record, Itemize, Collate, Tabulate Data.', '4.jpg', '1'),
	(6, 'Coaching', 'Keep Accurate and Up-to-Date Records, Log, Record, Itemize, Collate, Tabulate Data.', '4.jpg', '1'),
	(7, 'Web Designer', 'Keep Accurate and Up-to-Date Records, Log, Record, Itemize, Collate, Tabulate Data.', '4.jpg', '1'),
	(8, 'Web Developer', 'Keep Accurate and Up-to-Date Records, Log, Record, Itemize, Collate, Tabulate Data.', '4.jpg', '1');
/*!40000 ALTER TABLE `skill` ENABLE KEYS */;

-- Dumping structure for table pdo_cus_b11.slide
CREATE TABLE IF NOT EXISTS `slide` (
  `slide_id` int(50) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `description` varchar(250) NOT NULL,
  `img` varchar(250) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `url` varchar(250) NOT NULL,
  PRIMARY KEY (`slide_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table pdo_cus_b11.slide: ~2 rows (approximately)
/*!40000 ALTER TABLE `slide` DISABLE KEYS */;
INSERT INTO `slide` (`slide_id`, `title`, `description`, `img`, `status`, `url`) VALUES
	(1, 'កម្មវិធីសិកស្សាឆ្នាំថ្មី', 'កម្មវិធីសិកស្សាឆ្នាំថ្មី ត្រូវ​បាម​ជ្រើសរើសឲ្យ​ចូលរៀន​', 'img001.jpg', 1, ''),
	(2, 'កម្មវិធីសិកស្សា2019', 'កម្មវិធីសិកស្សាឆ្នាំថ្មី ត្រូវ​បាម​ជ្រើសរើសឲ្យ​ចូលរៀន​', 'img002.jpg', 1, '');
/*!40000 ALTER TABLE `slide` ENABLE KEYS */;

-- Dumping structure for table pdo_cus_b11.students
CREATE TABLE IF NOT EXISTS `students` (
  `stu_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(150) DEFAULT NULL,
  `last_name` varchar(150) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `gender` varchar(150) DEFAULT NULL,
  `bod` varchar(150) DEFAULT NULL,
  `phone` varchar(150) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `pass` varchar(250) DEFAULT NULL,
  `fb` varchar(250) DEFAULT NULL,
  `tw` varchar(250) DEFAULT NULL,
  `gplus` varchar(250) DEFAULT NULL,
  `about` longtext,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`stu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table pdo_cus_b11.students: ~2 rows (approximately)
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` (`stu_id`, `first_name`, `last_name`, `name`, `gender`, `bod`, `phone`, `email`, `pass`, `fb`, `tw`, `gplus`, `about`, `status`) VALUES
	(1, 'Som', 'Dara', 'Som Dara', 'M', '12-05-1983', '092771244', 'somdara@gmail.com', '123456', 'sengsourng', '#', '#', 'I like to learn Technology', 1),
	(2, 'Kean', 'Koeun', 'Kean Koeun', 'M', '11-02-1993', '092771244', 'keankoeun@gmail.com', '123456', 'keankeon', '#', '#', 'I like to learn Technology', 1);
/*!40000 ALTER TABLE `students` ENABLE KEYS */;

-- Dumping structure for table pdo_cus_b11.student_subject
CREATE TABLE IF NOT EXISTS `student_subject` (
  `stu_sub_id` int(11) NOT NULL AUTO_INCREMENT,
  `stu_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `start_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `note` text,
  PRIMARY KEY (`stu_sub_id`,`stu_id`,`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table pdo_cus_b11.student_subject: ~0 rows (approximately)
/*!40000 ALTER TABLE `student_subject` DISABLE KEYS */;
INSERT INTO `student_subject` (`stu_sub_id`, `stu_id`, `subject_id`, `start_date`, `note`) VALUES
	(1, 1, 1, '2018-05-26 15:08:59', 'Do you like Cheese Whiz? Spray tan? Fake eyelashes? That\'s what is Lorem Ipsum to many—it rubs them the wrong way, all the way. It\'s unreal, uncanny, makes you wonder if something is wrong, it seems to seek your attention for all the wrong reasons. Usually, we prefer the real thing, wine without sulfur based preservatives, real butter, not margarine, and so we\'d like our layouts and designs to be filled with real words, with thoughts that count, information that has value.');
/*!40000 ALTER TABLE `student_subject` ENABLE KEYS */;

-- Dumping structure for table pdo_cus_b11.subject
CREATE TABLE IF NOT EXISTS `subject` (
  `subject_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `subject_title` varchar(250) NOT NULL,
  `subject_description` varchar(250) NOT NULL,
  `subject_detail` longtext NOT NULL,
  `image` varchar(250) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `dist` decimal(10,0) NOT NULL,
  `unit` char(50) NOT NULL,
  `stutus` tinyint(4) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`subject_id`,`course_id`,`instructor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table pdo_cus_b11.subject: ~2 rows (approximately)
/*!40000 ALTER TABLE `subject` DISABLE KEYS */;
INSERT INTO `subject` (`subject_id`, `course_id`, `instructor_id`, `subject_title`, `subject_description`, `subject_detail`, `image`, `price`, `dist`, `unit`, `stutus`, `create_date`) VALUES
	(1, 13, 1, 'Advance Web Development', 'Do you like Cheese Whiz? Spray tan? Fake eyelashes? That\'s what is Lorem Ipsum to many—it rubs them the wrong way, all the way. It\'s unreal, uncanny, makes you wonder if something is wrong, it seems to seek your attention for all the wrong reasons. U', 'Do you like Cheese Whiz? Spray tan? Fake eyelashes? That\'s what is Lorem Ipsum to many—it rubs them the wrong way, all the way. It\'s unreal, uncanny, makes you wonder if something is wrong, it seems to seek your attention for all the wrong reasons. Usually, we prefer the real thing, wine without sulfur based preservatives, real butter, not margarine, and so we\'d like our layouts and designs to be filled with real words, with thoughts that count, information that has value.', '1.jpg', 20, 0, 'usd', 1, '2018-05-26 15:13:03'),
	(2, 14, 2, 'Java Programming', 'Do you like Cheese Whiz? Spray tan? Fake eyelashes? That\'s what is Lorem Ipsum to many—it rubs them the wrong way, all the way. It\'s unreal, uncanny, makes you wonder if something is wrong, it seems to seek your attention for all the wrong reasons. U', 'Do you like Cheese Whiz? Spray tan? Fake eyelashes? That\'s what is Lorem Ipsum to many—it rubs them the wrong way, all the way. It\'s unreal, uncanny, makes you wonder if something is wrong, it seems to seek your attention for all the wrong reasons. Usually, we prefer the real thing, wine without sulfur based preservatives, real butter, not margarine, and so we\'d like our layouts and designs to be filled with real words, with thoughts that count, information that has value.', '2.jpg', 40, 0, 'euo', 1, '2018-05-26 15:13:05');
/*!40000 ALTER TABLE `subject` ENABLE KEYS */;

-- Dumping structure for table pdo_cus_b11.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oauth_provider` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `oauth_uid` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `cover` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table pdo_cus_b11.users: ~0 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `oauth_provider`, `oauth_uid`, `first_name`, `last_name`, `email`, `gender`, `locale`, `cover`, `picture`, `link`, `created`, `modified`) VALUES
	(1, 'facebook', '11111111', 'Seng', 'Sourng', 'sengsourng@gmail.com', 'male', 'kh-KM', '1.jpg', '5b0901c1cd894_1527316920_large.jpg', '#', '2018-05-04 00:00:00', '2018-05-19 00:00:00');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
