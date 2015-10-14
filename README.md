# Registration
Registration and login application with php, from my course 1DV608 at Linnéuniversity. 

Author: Kristoffer Svensson

#Requirements

* Use-cases : https://github.com/dntoll/1DV608/blob/master/Assignments/Assignment_4/UC4.md

#Tests

##Manual testing with
* https://github.com/dntoll/1DV608/blob/master/Assignments/Assignment_4/TestCases.md

##Automated testing with
* http://csquiz.lnu.se:83/

# Installation and configuration
* Download files from github  
* Upload to you server/run on local server  
* Create the database table with:  
  CREATE TABLE IF NOT EXISTS `Users` (
  `Username` varchar(255) CHARACTER SET latin1 NOT NULL,
  `Password` varchar(255) CHARACTER SET latin1 NOT NULL,
  UNIQUE KEY `Username` (`Username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;  
* Rename Settings.php.default to Settings.php  
* Edit the information in Settings.php  
