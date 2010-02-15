#
# Table structure for table `newfeeds`
#
CREATE TABLE newfeeds (
  `fid` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `feed_type` enum('rss','sitemap', 'atom') DEFAULT NULL,
  `feed_name` varchar(64) DEFAULT NULL,
  `mid` int(10) DEFAULT '0',
  `func_feed` varchar(255) DEFAULT NULL,
  `func_file` varchar(255) DEFAULT NULL,
  `xml_buffer_updated` int(12) DEFAULT NULL,
  `xml_buffer` mediumtext,
  PRIMARY KEY (`fid`)
) ENGINE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `domains`
#
# Done in install PHP File
# --------------------------------------------------------


#
# Table structure for table `domainscategory`
#
# Done in install PHP File
# --------------------------------------------------------


#
# Table structure for table `domainsoption`
#
# Done in install PHP File
# --------------------------------------------------------


#
# Table structure for table `policies`
#
CREATE TABLE policies (
  `pcid` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT 'Default',
  `ipstart` varchar(16) DEFAULT '0.0.0.0',
  `ipend` varchar(16) DEFAULT '255.255.255.255',
  `status` enum('open','closed','redirect','hold','sleep') DEFAULT 'open',
  `agents` varchar(255) DEFAULT '*',
  `networknames` mediumtext,
  `groups` varchar(250) DEFAULT '1|2|3',
  `protocol` varchar(128) DEFAULT 'HTTPS|HTTP',
  `modules` varchar(250) DEFAULT '1',
  `redirect_url` varchar(255) DEFAULT NULL,
  `redirect_message` varchar(255) DEFAULT 'Due to policy setting you are being redirected.',
  `redirect_time` int(2) DEFAULT '5',
  `domains` mediumtext,
  `xml_conf` mediumtext,
  PRIMARY KEY (`pcid`)
) ENGINE=MyISAM;
# --------------------------------------------------------

insert into policies VALUES (1,'Default','0.0.0.0','255.255.255.255','open','*','*','1|2|3','HTTPS|HTTP','pm|profile|system','','',5,'all|127.0.0.1|localhost','');