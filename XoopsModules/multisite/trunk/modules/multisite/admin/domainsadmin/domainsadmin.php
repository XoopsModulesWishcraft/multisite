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
function create_new_domain()
{

    $module_handler =& xoops_getmodulehandler('module','multisite');
    $module =& $module_handler->getByDirname('multisite');
	$config_handler =& xoops_getmodulehandler('domain', 'multisite');

	$critera_z = new CriteriaCompo(new Criteria('dom_catid', XOOPS_DOMAIN));
	$critera_z->add(new Criteria('dom_name', 'domain')) ;
	$critera_z->add(new Criteria('dom_value', strtolower($_POST['domain']))) ;
	$configs = $config_handler->getDomains($critera_z);
	
	$confcount = count($configs);

	if ($confcount==0)
	{
		$ndomain = $config_handler->createDomain();
		
		$ndomain->setVar("dom_modid", $module->getVar('mid'));
		$ndomain->setVar("dom_catid", XOOPS_DOMAIN);
		$ndomain->setVar("dom_name", 'domain');
		$ndomain->setVar("dom_value", strtolower($_POST['domain']));
		$ndomain->setVar("dom_title", '_MD_AM_DOMAIN');
		$ndomain->setVar("dom_desc", '_MD_AM_DOMAINDESC');
		$ndomain->setVar("dom_formtype", 'textbox');
		$ndomain->setVar("dom_valuetype", 'text');
		
		$config_handler->insertDomain($ndomain);
		
	
		redirect_header('admin.php?fct=domainsadmin', 3, _MD_AM_ADDDOMAIN_SUCCESS);
	} else {
		redirect_header('admin.php?fct=domainsadmin', 3, _MD_AM_ADDDOMAIN_EXISTS);
	}	
}

function delete_domain($op, $fct, $id)
{
	global $xoopsDB;
	
    $module_handler =& xoops_getmodulehandler('module','multisite');
    $module =& $module_handler->getByDirname('multisite');
	
	$config_handler =& xoops_getmodulehandler('domain', 'multisite');

	$config = $config_handler->getDomain($id);
	
	if ($config->getVar('dom_name')=='domain')
	{
		$yy = $config->getVar('dom_id');
	
		$sql = sprintf("DELETE FROM %s WHERE dom_id = %u", $xoopsDB->prefix('domain'), $config->getVar('dom_id'));
        if (!$xoopsDB->queryF($sql)) 
			redirect_header('admin.php?fct=domainsadmin', 3, _MD_AM_DELETEDOMAIN_UNSUCCESS);		
	
		$critera_x = new CriteriaCompo(new Criteria('dom_pid', $yy));
		$critera_x->add(new Criteria('dom_name', 'multi_language')) ;
		$configs_a = $config_handler->getDomains($critera_x);
		$sql = sprintf("DELETE FROM %s WHERE dom_id = %u", $xoopsDB->prefix('domain'), $configs_a[0]->getVar('dom_id'));
        if (!$xoopsDB->queryF($sql)) 
			redirect_header('admin.php?fct=domainsadmin', 3, _MD_AM_DELETEDOMAIN_UNSUCCESS);		
			
		$sql = sprintf("DELETE FROM %s WHERE dom_pid = %u", $xoopsDB->prefix('domain'), $yy);
        if (!$xoopsDB->queryF($sql)) 
			redirect_header('admin.php?fct=domainsadmin', 3, _MD_AM_DELETEDOMAIN_UNSUCCESS);		

		redirect_header('admin.php?fct=domainsadmin', 3, sprintf(_MD_AM_DELETEDOMAIN_SUCCESS,$config->getVar('dom_value')));
	} else {
		redirect_header('admin.php?fct=domainsadmin', 3, _MD_AM_DELETEDOMAIN_UNSUCCESS);
	}
}

function edit_domains($op, $fct)
{

	$module_handler =& xoops_getmodulehandler('module','multisite');
    $module =& $module_handler->getByDirname('multisite');
	$config_handler =& xoops_getmodulehandler('domain', 'multisite');
	
	
	for($ii=0;$ii<=(int)$_POST['total'];$ii++)
	{
	
		$id = (isset($_GET['id']))?(int)$_GET['id']:$_POST['id'][$ii];
		
		$critera_f = new CriteriaCompo(new Criteria('dom_catid', XOOPS_DOMAIN));
		$critera_f->add(new Criteria('dom_name', 'domain')) ;
		$critera_f->add(new Criteria('dom_id', $id)) ;
		$configs_f = $config_handler->getDomains($critera_f);
		if (!empty($configs_f[0]))
		{
			$configs_f[0]->setVar('dom_value',$_POST['domain'][$ii]);
			$config_handler->insertDomain($configs_f[0]);
		}
	}
	redirect_header('admin.php?fct=domainsadmin', 3, _MD_AM_EDITDOMAIN_SUCCESS);
}

function add_domain_form($op, $fct)
{

	$op = "adddomain";
	
	include(XOOPS_ROOT_PATH.'/class/xoopsformloader.php');
	include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';

	$form = new XoopsThemeForm(_MD_AM_NEWDOMAIN, array("op","fct"), xoops_getenv('PHP_SELF')."?fct=$fct&op=$op");
	$form->setExtra('enctype="multipart/form-data"');

	$xl = new XoopsLists;

	$ii++;
	$domain[$ii] = new XoopsFormText(_MD_AM_DOMAIN, "domain", 35, 255 , str_replace("www.","",strtolower($_SERVER['HTTP_HOST'])));
	$language[$ii] = new XoopsFormSelect(_MD_AM_LANGUAGE, "languages", 0, 1 , false);
	$language[$ii]->addOptionArray($xl->getLangList());
	$themes[$ii] = new XoopsFormSelect(_MD_AM_THEME, "themes", 0, 1, false);
	$themes[$ii]->addOptionArray($xl->getThemesList());
	$startmodule[$ii] = new XoopsFormSelect(_MD_AM_START_MODULE, "start_module", "", 1 , false);
	$startmodule[$ii]->addOptionArray(array_merge(array("" => "(none)"),$xl->getModulesList()));
	global $xoopsConfig;
	$pagetitle[$ii] = new XoopsFormText(_MD_AM_DOMAIN_PAGETITLE, "page_title", 35, 255 , $xoopsConfig['sitename']);
	$slogan[$ii] = new XoopsFormText(_MD_AM_DOMAIN_SLOGAN, "slogan", 35, 255 , $xoopsConfig['slogan']);
	$meta['description'][$ii] = new XoopsFormTextArea(_MD_AM_DOMAIN_METADESCRIPTION, "meta_description", $xoopsConfig['meta_description'],  4, 45  );
	$meta['footer'][$ii] = new XoopsFormTextArea(_MD_AM_DOMAIN_METAFOOTER, "meta_footer", $xoopsConfig['meta_footer'], 4, 45 );
	$meta['keywords'][$ii] = new XoopsFormTextArea(_MD_AM_DOMAIN_METAKEYWORDS, "meta_keywords", $xoopsConfig['meta_keywords'],  4, 45  );
			
	$form->addElement($domain[$ii]);	
	//$form->addElement($pagetitle[$ii]);	
	//$form->addElement($slogan[$ii]);		
	//$form->addElement($language[$ii]);
	//$form->addElement($themes[$ii]);		
	//$form->addElement($startmodule[$ii]);
	//$form->addElement($meta['description'][$ii]);				
	//$form->addElement($meta['footer'][$ii]);			
	//$form->addElement($meta['keywords'][$ii]);				
	$form->addElement(new XoopsFormHidden('op', 'adddomain'));
	$submit = new XoopsFormButton("", "submit", _SUBMIT, "submit");
	$form->addElement($submit);

	echo $form->render();
}

function edit_domain_form($id, $op, $fct)
{

	$op = "edit";
	
	include(XOOPS_ROOT_PATH.'/class/xoopsformloader.php');
	include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';

    xoops_cp_header();
	adminMenu(XOOPS_MULTISITE_DOMAINS);
	
	$xl = new XoopsLists;

	$ii++;
	
	$module_handler =& xoops_getmodulehandler('module','multisite');
    $module =& $module_handler->getByDirname('multisite');
	$config_handler =& xoops_getmodulehandler('domain', 'multisite');
	
	$critera_f = new CriteriaCompo(new Criteria('dom_catid', XOOPS_DOMAIN));
	$critera_f->add(new Criteria('dom_name', 'domain')) ;
	$critera_f->add(new Criteria('dom_id', $id)) ;
	$configs_f = $config_handler->getDomains($critera_f);
	
	$config = $configs_f[0];	
	$yy = (int)$id;
	
		
	$form = new XoopsThemeForm(sprintf(_MD_AM_EDITDOMAIN, $config->getVar('dom_value')), array("op","fct", "id"), xoops_getenv('PHP_SELF')."?fct=$fct&op=$op&id=$id");
	$form->setExtra('enctype="multipart/form-data"');
	
	$critera_x = new CriteriaCompo(new Criteria('dom_id', $yy));
	$critera_x->add(new Criteria('dom_name', 'domain')) ;
	$configs_a = $config_handler->getDomains($critera_x);
	if (isset($configs_a[0]))
		$domain[$ii] = new XoopsFormText(_MD_AM_DOMAIN, "domain[$ii]", 35, 255 , $configs_a[0]->getVar('dom_value'));
	else
		$pagetitle[$ii] = new XoopsFormText(_MD_AM_DOMAIN, "domain[$ii]", 35, 255 , '');
		
	$form->addElement($domain[$ii]);	
	$form->addElement(new XoopsFormHidden('op', 'editdomain'));
	$form->addElement(new XoopsFormHidden('total', $ii));
	$form->addElement(new XoopsFormHidden("id[$ii]", $id));
	$submit = new XoopsFormButton("", "submit", _SUBMIT, "submit");
	$form->addElement($submit);

	echo $form->render();
	
	xoops_cp_footer();
}


function xoops_domain_list($op, $fct)
{
	include(XOOPS_ROOT_PATH.'/class/xoopsformloader.php');
	include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
	
	$op = "edit";
	
	$xl = new XoopsLists;
	
    xoops_cp_header();
	adminMenu(XOOPS_MULTISITE_DOMAINS);
	
    $module_handler =& xoops_getmodulehandler('module','multisite');
    $module =& $module_handler->getByDirname('system');
	
	$config_handler =& xoops_getmodulehandler('domain', 'multisite');

	$critera_z = new CriteriaCompo(new Criteria('dom_catid', XOOPS_DOMAIN));
	$critera_z->add(new Criteria('dom_name', 'domain')) ;
	$configs = $config_handler->getDomains($critera_z);
	
	$confcount = count($configs);
	
	$form = new XoopsThemeForm(_MD_AM_CURRENTDOMAIN, array("op","fct"), xoops_getenv('PHP_SELF')."?fct=$fct&op=$op");
	$form->setExtra('enctype="multipart/form-data"');
	$sprint = str_replace($_SERVER['HTTP_HOST'], '%s', strtolower(XOOPS_URL));

	foreach ($configs as $config)
	{
		$ii++;
		$yy = $config->getVar('dom_id');
				
		$tray[$ii] = new XoopsFormElementTray($config->getVar('dom_value'),'&nbsp;');

		$critera_x = new CriteriaCompo(new Criteria('dom_pid', $yy));
		$critera_x->add(new Criteria('dom_name', 'language')) ;
		$configs_a = $config_handler->getDomains($critera_x);
		
		if (count($configs_a)>0)
			$language[$ii] = new XoopsFormSelect(_MD_AM_LANGUAGE, "languages[$ii]", $configs_a[0]->getConfValueForOutput(), 1 , false);
		else
			$language[$ii] = new XoopsFormSelect(_MD_AM_LANGUAGE, "languages[$ii]", 0, 1 , false);
			
		$language[$ii]->addOptionArray($xl->getLangList());
		$language[$ii]->setExtra('disabled="1"');
		
		$critera_y = new CriteriaCompo(new Criteria('dom_pid', $yy));
		$critera_y->add(new Criteria('dom_name', 'theme')) ;
		$configs_b = $config_handler->getDomains($critera_y);
		
		if (count($configs_b)>0)
			$themes[$ii] = new XoopsFormSelect(_MD_AM_THEME, "themes[$ii]", $configs_b[0]->getConfValueForOutput(), 1, false);
		else
			$themes[$ii] = new XoopsFormSelect(_MD_AM_THEME, "themes[$ii]", 0, 1, false);		
		$themes[$ii]->addOptionArray($xl->getThemesList());
		$themes[$ii]->setExtra('disabled="1"');
		
		$critera_z = new CriteriaCompo(new Criteria('dom_pid', $yy));
		$critera_z->add(new Criteria('dom_name', 'startpage')) ;
		$configs_c = $config_handler->getDomains($critera_z);
	
		if (count($configs_c)>0)
			$startmodule[$ii] = new XoopsFormSelect(_MD_AM_START_MODULE, "start_module[$ii]", $configs_c[0]->getConfValueForOutput(), 1 , false);
		else
			$startmodule[$ii] = new XoopsFormSelect(_MD_AM_START_MODULE, "start_module[$ii]", 0, 1 , false);
			
		$startmodule[$ii]->addOptionArray(array_merge(array("" => "(none)"),$xl->getModulesList()));
		$startmodule[$ii]->setExtra('disabled="1"');
		
		$critera_z = new CriteriaCompo(new Criteria('dom_pid', $yy));
		$critera_z->add(new Criteria('dom_name', 'sitename')) ;
		$configs_c = $config_handler->getDomains($critera_z);
	
		if (count($configs_c)>0)
			$pagetitle[$ii] = new XoopsFormText(_MD_AM_DOMAIN_PAGETITLE, "page_title[$ii]", 23, 255 , $configs_c[0]->getConfValueForOutput());
		else
			$pagetitle[$ii] = new XoopsFormText(_MD_AM_DOMAIN_PAGETITLE, "page_title[$ii]", 23, 255 , '');
			$pagetitle[$ii]->setExtra('disabled="1"');
		$id[$ii] = new XoopsFormHidden("id[$ii]", $yy);
		
		$label_txt = "<a href='".XOOPS_URL."/modules/multisite/admin.php?fct=$fct&op=delete&id=$yy'>Delete</a> | ";
		$label_txt .= "<a href='".XOOPS_URL."/modules/multisite/admin.php?fct=$fct&op=editdomain&id=$yy'>Edit</a> | ";
		$label_txt .= "<a href='".XOOPS_URL."/modules/multisite/admin.php?fct=preferences&domain=".sprintf($sprint,$config->getVar('dom_value'))."'>Preferences</a>";

		$label[$ii] = new XoopsFormLabel("", $label_txt);

		$tray[$ii]->addElement($pagetitle[$ii]);	
		$tray[$ii]->addElement($language[$ii]);
		$tray[$ii]->addElement($themes[$ii]);		
		$tray[$ii]->addElement($startmodule[$ii]);
		$tray[$ii]->addElement($label[$ii]);
		$tray[$ii]->addElement($id[$ii]);

		
		$form->addElement($tray[$ii]);	
	}

	$form->addElement(new XoopsFormHidden('total', $ii));	
	$form->addElement(new XoopsFormHidden('op', $op));
	
	$form->addElement($submit);

	@add_domain_form($op, $fct);
	
	if ($confcount>0)
		echo $form->render();
		
   footer_adminMenu();
 xoops_cp_footer();
}


?>