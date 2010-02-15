<?php
// $Id: modulesadmin.php 2701 2009-01-20 23:45:46Z dugris $
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

if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit("Access Denied");
}
function create_new_domaingroup()
{

    $module_handler =& xoops_getmodulehandler('module','multisite');
    $module =& $module_handler->getByDirname('multisite');
	$domain_handler =& xoops_getmodulehandler('domain', 'multisite');
	$domopt_handler =& xoops_getmodulehandler('domainoption', 'multisite');
	
	

	$ndomain = $domain_handler->createDomain();
	
	$ndomain->setVar("dom_modid", $module->getVar('mid'));
	$ndomain->setVar("dom_catid", getDomainGroupCat());
	$ndomain->setVar("dom_name", 'domaingroup');
	$ndomain->setVar('dom_value', $_POST['name'][0]);
	$ndomain->setVar("dom_title", '_MD_AM_DOMAINGROUP');
	$ndomain->setVar("dom_desc", '_MD_AM_DOMAINGROUPDESC');
	$ndomain->setVar("dom_formtype", 'textbox');
	$ndomain->setVar("dom_valuetype", 'text');

	$domain_handler->insertDomain($ndomain);
	

	foreach($_POST['domaingroup'][0] as $key => $value)
	{
		$domopt = $domopt_handler->create();
		$domopt->setVar('domop_name', urldecode($value));
		$domopt->setVar('domop_value', $value);
		$domopt->setVar('dom_id', $ndomain->getVar('dom_id'));
		$domopt_handler->insert($domopt);
	}
	
		
	$bdomain = $domain_handler->createDomain();

	$bdomain->setVar("dom_pid", $ndomain->getVar('dom_pid'));	
	$bdomain->setVar("dom_modid", $module->getVar('mid'));
	$bdomain->setVar("dom_catid", getDomainGroupCat());
	$bdomain->setVar("dom_name", 'domaingroupprimary');
	$bdomain->setVar('dom_value', $_POST['primary'][0]);
	$bdomain->setVar("dom_title", '_MD_AM_DOMAINGROUPPRIM');
	$bdomain->setVar("dom_desc", '_MD_AM_DOMAINGROUPPRIMDESC');
	$bdomain->setVar("dom_formtype", 'textbox');
	$bdomain->setVar("dom_valuetype", 'text');
	
	$domain_handler->insertDomain($bdomain);
	
	redirect_header('admin.php?fct=domaingroupsadmin', 3, sprintf(_MD_AM_CREATEGROUP_SUCCESSFUL,$_POST['name'][0]));

}

function delete_domaingroup($op, $fct, $id)
{
	global $xoopsDB;
	
    $module_handler =& xoops_getmodulehandler('module','multisite');
    $module =& $module_handler->getByDirname('multisite');
	
	$config_handler =& xoops_getmodulehandler('domain', 'multisite');

	$config = $config_handler->getDomain($id);
	
	if ($config->getVar('dom_name')=='domain')
	{
		$yy = $config->getVar('dom_id');
	
		$sql = sprintf("DELETE FROM %s WHERE dom_id = %u or dom_pid = %u", $xoopsDB->prefix('domain'), $config->getVar('dom_id'), $config->getVar('dom_id'));
        if (!$xoopsDB->queryF($sql)) 
			redirect_header('admin.php?fct=domaingroupsadmin', 3, _MD_AM_DELETEDOMAINGROUP_UNSUCCESS);		
	
		$sql = sprintf("DELETE FROM %s WHERE dom_id = %u", $xoopsDB->prefix('domainoption'), $configs_a[0]->getVar('dom_id'));
        if (!$xoopsDB->queryF($sql)) 
			redirect_header('admin.php?fct=domaingroupsadmin', 3, _MD_AM_DELETEDOMAINGROUP_UNSUCCESS);		
			
		redirect_header('admin.php?fct=domaingroupsadmin', 3, sprintf(_MD_AM_DELETEDOMAINGROUP_SUCCESS,$config->getVar('dom_value')));
	} else {
		redirect_header('admin.php?fct=domaingroupsadmin', 3, _MD_AM_DELETEDOMAINGROUP_UNSUCCESS);
	}
}

function edit_domaingroups($op, $fct)
{


	$module_handler =& xoops_getmodulehandler('module','multisite');
    $module =& $module_handler->getByDirname('multisite');
	$domain_handler =& xoops_getmodulehandler('domain', 'multisite');
	$domopt_handler =& xoops_getmodulehandler('domainoption', 'multisite');

	for($ii=0;$ii<=(int)$_POST['total'];$ii++)
	{
	
		$id = intval((isset($_GET['id']))?(int)$_GET['id'][$ii]:$_POST['id'][$ii]);
		
		if ($id>0)
		{
			$domaingroup = $domain_handler->getDomain($id);
			
			if (is_array($domaingroup))
				$domaingroup = $domaingroup[0];	
		
			$domaingroup->setVar('dom_value', $_POST['name'][$id]);
			$domain_handler->insertDomain($domaingroup);
			
			$domainoptions = $domain_handler->getDomainOptions(new Criteria('dom_id', $id), true);
			$contains = array();
			foreach($domainoptions as $key => $domainoption)
			{
				if (!in_array($domainoption->getVar('domop_value'), $_POST['domaingroup'][$id]))
					$domopt_handler->delete($domainoption);
				else
					$contains[$key] = $domainoption->getVar('domop_value');
			}
			
			foreach($_POST['domaingroup'][$id] as $key => $value)
				if (!in_array($value, $contains))
				{
					$domopt = $domopt_handler->create();
					$domopt->setVar('domop_name', urldecode($value));
					$domopt->setVar('domop_value', $value);
					$domopt->setVar('dom_id', $id);
					$domopt_handler->insert($domopt);
				}
		
			$criteria = new CriteriaCompo(new Criteria('dom_catid', getDomainGroupCat()));
			$criteria->add(new Criteria('dom_name', 'domaingroupprimary')) ;
			$criteria->add(new Criteria('dom_pid', $id)) ;
			$domaingroup = $domain_handler->getDomains($criteria);
			if (is_array($domaingroup))
				$domaingroup = $domaingroup[0];
				
			$domaingroup->setVar('dom_value', $_POST['primary'][$id]);
			$domain_handler->insertDomain($domaingroup);
		}
	}
	redirect_header('admin.php?fct=domaingroupsadmin', 3, _MD_AM_EDITDOMAINGROUP_SUCCESS);
}

function add_domaingroup_form($op, $fct)
{

	$op = "adddomain";
	
	include(XOOPS_ROOT_PATH.'/class/xoopsformloader.php');
	
	$form = new XoopsThemeForm(_MD_AM_NEWDOMAINGROUP, array("op","fct"), xoops_getenv('PHP_SELF')."?fct=$fct&op=$op");
	$form->setExtra('enctype="multipart/form-data"');

	$ii++;
	$domain[$ii] = new XoopsFormText(_MD_AM_DOMAINGROUP, "name[0]", 35, 255 , $xoopsConfig['sitename']);
	$primary[$ii] = new XoopsFormSelectDomains(_MD_AM_PRIMARYDOMAINGROUP, "primary[0]", array(0=>urlencode(XOOPS_URL)));

	$group[$ii] = new XoopsFormCheckBoxDomains(_MD_AM_INGROUPDOMAINGROUPS, "domaingroup[0]", array(0=>urlencode(XOOPS_URL)), '&nbsp;', false);
			
	$form->addElement($domain[$ii]);	
	$form->addElement($primary[$ii]);	
	$form->addElement($group[$ii]);		

	$form->addElement(new XoopsFormHidden('id[0]', '0'));
	$form->addElement(new XoopsFormHidden('op', 'adddomain'));
	$submit = new XoopsFormButton("", "submit", _SUBMIT, "submit");
	$form->addElement($submit);

	echo $form->render();
}

function edit_domaingroup_form($id, $op, $fct)
{

	$op = "edit";
	
	include(XOOPS_ROOT_PATH.'/class/xoopsformloader.php');

    xoops_cp_header();
	adminMenu(XOOPS_MULTISITE_DOMAINGROUPS);
	
	$ii++;
	
	$module_handler =& xoops_getmodulehandler('module','multisite');
    $module =& $module_handler->getByDirname('multisite');
	$domain_handler =& xoops_getmodulehandler('domain', 'multisite');
	
	$domaingroup = $domain_handler->getDomain($id);
	
	if (is_array($domaingroup))
		$domaingroup = $domaingroup[0];	

	$form = new XoopsThemeForm(_MD_AM_NEWDOMAINGROUP, array("op","fct"), xoops_getenv('PHP_SELF')."?fct=$fct&op=$op");
	$form->setExtra('enctype="multipart/form-data"');

	$domain[$ii] = new XoopsFormText(_MD_AM_DOMAINGROUP, "name[$id]", 35, 255 , $domaingroup->getConfValueForOutput());
	$form->addElement(new XoopsFormHidden("id[$ii]", $id));

	foreach($domain_handler->getDomainOptions(new Criteria('dom_id', $domaingroup->getVar('dom_id')), true) as $key => $dmgroup)
		$dgroup[$key] = $dmgroup->getVar('domop_value');

	$group[$ii] = new XoopsFormCheckBoxDomains(_MD_AM_INGROUPDOMAINGROUPS, "domaingroup[$id]", $dgroup, '&nbsp;', false);
	
	$criteria = new CriteriaCompo(new Criteria('dom_catid', getDomainGroupCat()));
	$criteria->add(new Criteria('dom_name', 'domaingroupprimary')) ;
	$criteria->add(new Criteria('dom_pid', $id)) ;
	$domaingroup = $domain_handler->getDomains($criteria);
	if (is_array($domaingroup))
		$domaingroup = $domaingroup[0];
	
	$primary[$ii] = new XoopsFormSelectDomains(_MD_AM_PRIMARYDOMAINGROUP, "primary[$id]", $domaingroup->getConfValueForOutput());

			
	$form->addElement($domain[$ii]);	
	$form->addElement($primary[$ii]);	
	$form->addElement($group[$ii]);		

	$form->addElement(new XoopsFormHidden('op', 'edit'));
	$form->addElement(new XoopsFormHidden('total', $ii));	
	$submit = new XoopsFormButton("", "submit", _SUBMIT, "submit");
	$form->addElement($submit);

	echo $form->render();
	
	xoops_cp_footer();
}


function xoops_domaingroup_list($op, $fct)
{
	include(XOOPS_ROOT_PATH.'/class/xoopsformloader.php');
	
	$op = "edit";
	
    xoops_cp_header();
	adminMenu(XOOPS_MULTISITE_DOMAINGROUPS);
	
	$module_handler =& xoops_getmodulehandler('module','multisite');
    $module =& $module_handler->getByDirname('multisite');
	$domain_handler =& xoops_getmodulehandler('domain', 'multisite');
	
	$criteria = new CriteriaCompo(new Criteria('dom_catid', getDomainGroupCat()));
	$criteria->add(new Criteria('dom_name', 'domaingroup')) ;
	$domaingroup = $domain_handler->getDomains($criteria);
	$dgcount = count($domaingroup);
	
	$form = new XoopsThemeForm(_MD_AM_CURRENTDOMAINGROUP, array("op","fct"), xoops_getenv('PHP_SELF')."?fct=$fct&op=$op");
	$form->setExtra('enctype="multipart/form-data"');
	
	foreach ($domaingroup as $dg)
	{

		$ii++;
		$yy = $dg->getVar('dom_id');
				
		$tray[$ii] = new XoopsFormElementTray($dg->getConfValueForOutput(),'&nbsp;');
		$tray[$ii]->addElement(new XoopsFormHidden("id[$ii]", $yy));

		$criteria = new CriteriaCompo(new Criteria('dom_catid', getDomainGroupCat()));
		$criteria->add(new Criteria('dom_name', 'domaingroupprimary')) ;
		$criteria->add(new Criteria('dom_pid', $yy)) ;
		$domaingroup = $domain_handler->getDomains($criteria);

		if (is_array($domaingroup))
			$domaingroup = $domaingroup[0];
		
		$tray[$ii]->addElement(new XoopsFormSelectDomains(_MD_AM_TRAYPRIMARYGROUP, "primary[$yy]", $domaingroup->getConfValueForOutput()));
		$dgroup = array();
		foreach($domain_handler->getDomainOptions(new Criteria('dom_id', $yy), true) as $key => $dmgroup)
			$dgroup[$key] = $dmgroup->getVar('domop_value');
	
		$tray[$ii]->addElement(new XoopsFormSelectDomains(_MD_AM_TRAYDOMAINGROUP, "domaingroup[$yy]", $dgroup, 3, true));
		
		$label_txt = "<a href='".XOOPS_URL."/modules/multisite/admin.php?fct=$fct&op=delete&id=$yy'>Delete</a> | ";
		$label_txt .= "<a href='".XOOPS_URL."/modules/multisite/admin.php?fct=$fct&op=editdomain&id=$yy'>Edit</a> ";

		$tray[$ii]->addElement(new XoopsFormLabel("", $label_txt));
		
		$form->addElement($tray[$ii]);	
	}

	$form->addElement(new XoopsFormHidden('total', $ii));	

	$form->addElement(new XoopsFormHidden('op', 'edit'));
	$submit = new XoopsFormButton("", "submit", _SUBMIT, "submit");
	$form->addElement($submit);

	@add_domaingroup_form($op, $fct);
	
	if ($dgcount>0)
		echo $form->render();
		
   footer_adminMenu();
   xoops_cp_footer();
}


function getDomainGroupCat() {
	$domaincat_handler = xoops_getmodulehandler('domaincategory', 'multisite');
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('domcat_name', 'XOOPS_DOMAIN_GROUP'));
	if (!$domaincat_handler->getCount($criteria))
	{
		$domcat = $domaincat_handler->create();
		$domcat->setVar('domcat_name', 'XOOPS_DOMAIN_GROUP');
		$domaincat_handler->insert($domcat);
	} else {
		$domcat = $domaincat_handler->getObjects($criteria);
		if (is_array($domcat))
			$domcat = $domcat[0];
	}
	
	return $domcat->getVar('domcat_id');
}

?>