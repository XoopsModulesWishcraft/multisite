<?php
// $Id: update.php 2 2005-11-02 18:23:29Z skalpa $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

function xoops_module_pre_install_multisite(&$module) {

	global $xoopsDB, $module_handler;
	
	$module_handler = &xoops_gethandler( 'module' );
	$module_handler->insert($module);
	
	$result = $xoopsDB->queryF("ALTER TABLE ".$xoopsDB->prefix('config')." ADD COLUMN (`conf_did` INT(10) NOT NULL DEFAULT '0')");
	$result = $xoopsDB->queryF("ALTER TABLE ".$xoopsDB->prefix('newblocks')." ADD COLUMN(domains MEDIUMTEXT)");
	$result = $xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('newblocks')." SET domains='|all'");
	$result = $xoopsDB->queryF("ALTER TABLE ".$xoopsDB->prefix('modules')." ADD COLUMN (`hasrss` TINYINT(1) NOT NULL DEFAULT '0')");
	$result = $xoopsDB->queryF("ALTER TABLE ".$xoopsDB->prefix('modules')." ADD COLUMN (`hasatom` TINYINT(1) NOT NULL DEFAULT '0')");		
	$result = $xoopsDB->queryF("ALTER TABLE ".$xoopsDB->prefix('modules')." ADD COLUMN (`hassitemap` TINYINT(1) NOT NULL DEFAULT '0')");
	$result = $xoopsDB->queryF("ALTER TABLE ".$xoopsDB->prefix('modules')." ADD COLUMN(domains MEDIUMTEXT)");		
	$result = $xoopsDB->queryF("UPDATE ".$xoopsDB->prefix('modules')." SET domains='|all'");
	$result = $xoopsDB->queryF("CREATE TABLE ".$xoopsDB->prefix('domain')." (`dom_id` INT(10) unsigned NOT NULL AUTO_INCREMENT, `dom_pid` INT(10) unsigned NOT NULL DEFAULT '0', `dom_modid` smallint(5) unsigned NOT NULL DEFAULT '0', `dom_catid` smallint(5) unsigned NOT NULL DEFAULT '0', `dom_name` varchar(25) NOT NULL DEFAULT '', `dom_title` varchar(255) NOT NULL DEFAULT '', `dom_value` text, `dom_desc` varchar(255) NOT NULL DEFAULT '', `dom_formtype` varchar(15) NOT NULL DEFAULT '', `dom_valuetype` varchar(10) NOT NULL DEFAULT '', `dom_order` smallint(5) unsigned NOT NULL DEFAULT '0', PRIMARY KEY (`dom_id`), KEY `dom_mod_cat_id` (`dom_modid`,`dom_catid`)) ENGINE=MyISAM");

	$result = $xoopsDB->queryF("CREATE TABLE ".$xoopsDB->prefix('domainoption')." (`domop_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT, `domop_name` varchar(255) NOT NULL DEFAULT '', `domop_value` varchar(255) NOT NULL DEFAULT '', `dom_id` smallint(5) unsigned NOT NULL DEFAULT '0', PRIMARY KEY (`domop_id`), KEY `dom_id` (`dom_id`)) ENGINE=MyISAM");
$result = $xoopsDB->queryF("CREATE TABLE ".$xoopsDB->prefix('domaincategory')." (`domcat_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT, `domcat_name` varchar(255) NOT NULL DEFAULT '', `domcat_order` smallint(5) unsigned NOT NULL DEFAULT '0', PRIMARY KEY (`domcat_id`)) ENGINE=MyISAM");

	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix('domain')." (`dom_modid`, `dom_catid`, `dom_name`, `dom_value`, `dom_title`, `dom_desc`, `dom_formtype`, `dom_valuetype`) VALUE('".$module->getVar('mid')."', '1', 'domain', '".strtolower($_SERVER['HTTP_HOST'])."', '_MD_AM_DOMAIN', '_MD_AM_DOMAINDESC', 'textbox', 'text')");	

	$result = $xoopsDB->queryF("INSERT INTO ".$xoopsDB->prefix('domaincategory')." values('1','XOOPS_DOMAIN','0')");
	
	$confcat_handler =& xoops_gethandler('configcategory');
	$confcat =& $confcat_handler->get(1);

	$domain_handler =& xoops_getmodulehandler('domain','multisite');	
	$domcat_handler =& xoops_getmodulehandler('domaincategory','multisite');
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('domcat_name', $confcat->getVar('confcat_name')));
	$domcat = $domcat_handler->getObjects($criteria);
	if($domcat_handler->getCount($criteria)!='0') {
		$domcat = $domcat[0];
	} else {
		$domcat = $domcat_handler->create();
		$domcat->setVar('domcat_name', $confcat->getVar('confcat_name'));
		$domcat->setVar('domcat_order', $confcat->getVar('confcat_order'));
		@$domcat_handler->insert($domcat);
	}
		
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('dom_pid', 1));
	$criteria->add(new Criteria('dom_catid', $domcat->getVar('domcat_id')));
	if ($domain_handler->getDomainCount($criteria)==0)
	{

		$config_handler =& xoops_gethandler('config');
		$confopt_handler =& xoops_gethandler('configoption');
		
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('conf_modid', 0));
		$criteria->add(new Criteria('conf_catid', $confcat->getVar('confcat_id')));
		$configs = $config_handler->getConfigs($criteria);
		$domain_handler =& xoops_getmodulehandler('domainitem','multisite');
		$domainoption_handler =& xoops_getmodulehandler('domainoption','multisite');
		$dom_catid = $domcat->getVar('domcat_id');
		$dom_pid = 1;
		foreach ($configs as $config) {
			$domconf = $domain_handler->create();
			$domconf->setVar('dom_pid', $dom_pid);
			$domconf->setVar('dom_modid', $config->getVar('conf_modid'));
			$domconf->setVar('dom_catid', $dom_catid);
			$domconf->setVar('dom_name', $config->getVar('conf_name'));
			$domconf->setVar('dom_title', $config->getVar('conf_title'));
			$domconf->setVar('dom_value', $config->getConfValueForOutput());
			$domconf->setVar('dom_desc', $config->getVar('conf_desc'));
			$domconf->setVar('dom_formtype', $config->getVar('conf_formtype'));
			$domconf->setVar('dom_valuetype', $config->getVar('conf_valuetype'));
			$domconf->setVar('dom_order', $config->getVar('conf_order'));
			
			if(!$domain_handler->insert($domconf))
{
			} else {
				$criteria = new CriteriaCompo();
				$criteria->add(new Criteria('conf_id', $config->getVar('conf_id')));
				$confopts = $confopt_handler->getObjects($criteria);
				foreach ($confopts as $option)
				{
					$doption = $domainoption_handler->create();
					$doption->setVar('domop_name', $option->getVar('confop_name'));
					$doption->setVar('domop_value', $option->getVar('confop_value'));
					$doption->setVar('dom_id', $domconf->getVar('dom_id'));
					@$domainoption_handler->insert($doption);
				}
			}

		}
		

	}
	
	return true;
		
}

?>