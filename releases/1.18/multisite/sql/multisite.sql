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
CREATE TABLE domain (
  `dom_id` INT(10) unsigned NOT NULL AUTO_INCREMENT,
  `dom_pid` INT(10) unsigned NOT NULL DEFAULT '0',
  `dom_modid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `dom_catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `dom_name` varchar(25) NOT NULL DEFAULT '',
  `dom_title` varchar(255) NOT NULL DEFAULT '',
  `dom_value` text,
  `dom_desc` varchar(255) NOT NULL DEFAULT '',
  `dom_formtype` varchar(15) NOT NULL DEFAULT '',
  `dom_valuetype` varchar(10) NOT NULL DEFAULT '',
  `dom_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`dom_id`),
  KEY `dom_mod_cat_id` (`dom_modid`,`dom_catid`)
) ENGINE=MyISAM;
# --------------------------------------------------------


#
# Table structure for table `domainscategory`
#
CREATE TABLE domaincategory (
  `domcat_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `domcat_name` varchar(255) NOT NULL DEFAULT '',
  `domcat_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`domcat_id`)
) ENGINE=MyISAM;
# --------------------------------------------------------


#
# Table structure for table `domainsoption`
#
CREATE TABLE domainoption (
  `domop_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `domop_name` varchar(255) NOT NULL DEFAULT '',
  `domop_value` varchar(255) NOT NULL DEFAULT '',
  `dom_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`domop_id`),
  KEY `dom_id` (`dom_id`)
) ENGINE=MyISAM;


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

insert into domaincategory values('1','XOOPS_DOMAIN','0');