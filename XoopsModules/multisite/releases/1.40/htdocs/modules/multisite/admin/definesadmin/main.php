<?php
// $Id$
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
} else {


    $op = 'list';
    if (isset($_REQUEST)) {
        foreach ( $_REQUEST as $k => $v ) {
            ${$k} = $v;
        }
    }
    if (isset($_REQUEST['op'])) {
        $op = trim($_REQUEST['op']);
    }

    if (isset($_REQUEST['define_var'])) {
        $define_var = $_REQUEST['define_var'];
	}

    if (isset($_REQUEST['define_name'])) {
		$define_name = $_REQUEST['define_name'];
    }

    if (isset($_REQUEST['define_id'])) {
        $define_id = intval($_REQUEST['define_id']);
	}

	if (isset($_REQUEST['domain'])) {
        $domain = (string)urldecode(($_REQUEST['domain']));
    } else {
		$domain = XOOPS_URL;
	}

	xoops_cp_header();
	adminMenu(XOOPS_MULTISITE_DEFINES);	

	include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
	if (!class_exists('XoopsFormSelectDomains'))
		include_once XOOPS_ROOT_PATH . '/modules/multisite/class/formselectdomains.php';

	$domain_handler = xoops_getmodulehandler('domain', 'multisite');

	$purl = parse_url($domain);
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('dom_name', 'domain'));
	$criteria->add(new Criteria('dom_value', $purl['host']));
	
	if(!$domain_handler->getDomainCount($criteria))
	{
		redirect_header('admin.php?fct=domainsadmin', 1);
	} else {
		$obj_domain = $domain_handler->getDomains($criteria);
		if (is_array($obj_domain))
			$obj_domain = $obj_domain[0];
	}
	
	$domaincat_handler = xoops_getmodulehandler('domaincategory', 'multisite');
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('domcat_name', 'XOOPS_DEFINE'));
	if (!$domaincat_handler->getCount($criteria))
	{
		$domcat = $domaincat_handler->create();
		$domcat->setVar('domcat_name', 'XOOPS_DEFINE');
		$domaincat_handler->insert($domcat);
	} else {
		$domcat = $domaincat_handler->getObjects($criteria);
		if (is_array($domcat))
			$domcat = $domcat[0];
	}	

	$module_handler = xoops_gethandler('module');
	$module = $module_handler->getByDirname('multisite');		

    if ($op == 'list') {
	
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('dom_name', 'define')) ;
		$criteria->add(new Criteria('dom_pid', $obj_domain->getVar('dom_id'))) ;
		$criteria->add(new Criteria('dom_catid', $domcat->getVar('domcat_id'))) ;		
		$criteria->add(new Criteria('dom_modid', $module->mid())) ;
		$defines = $domain_handler->getDomains($criteria, true);
		
		$definescount = count($defines);

		echo '<h4>'._MD_AM_SITEDEFS_DOMAIN.'</h4><ul>';
	
	    $sform = new XoopsThemeForm(_AM_SELECT_DOMAIN, array("domain","fct","op"),  "admin.php?fct=$fct&op=$op&domain=".urlencode($domain));
    	$sform->setExtra('enctype="multipart/form-data"');
		$sform->addElement(new XoopsFormSelectDomains(_AM_DOMAINS, "domain", !empty($domain)?urlencode($domain):urlencode(XOOPS_URL), 1, false));
		$sform->addElement(new XoopsFormButton('', '', _SUBMIT, 'submit'));
		$sform->display();
		

        include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
		$def_form = new XoopsThemeForm(_MD_AM_MODDEFINE, 'def_form', 'admin.php?fct=definesadmin', 'post', true);
		$def_form->addElement(new XoopsFormHidden('domain', urlencode($domain)));
		
		if ($definescount)
		foreach ($defines as $key => $define)
		{
			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('dom_name', 'define_var')) ;
			$criteria->add(new Criteria('dom_catid', $domcat->getVar('domcat_id'))) ;
			$criteria->add(new Criteria('dom_pid', $define->getVar('dom_id'))) ;
			$criteria->add(new Criteria('dom_modid', $module->mid())) ;

			if(!$domain_handler->getDomainCount($criteria))
			{

			} else {
				$domain_out = $domain_handler->getDomains($criteria);
				if (is_array($domain_out))
					$domain_out = $domain_out[0];

				$eletray[$domain_out->getVar('dom_id')] = new XoopsFormElementTray(htmlspecialchars($define->getConfValueForOutput()), "&nbsp;");
				$eletray[$domain_out->getVar('dom_id')]->addElement(new XoopsFormText('', 'define_var['.$domain_out->getVar('dom_id').']', 50, 255,htmlspecialchars($domain_out->getConfValueForOutput())));
				$eletray[$domain_out->getVar('dom_id')]->addElement(new XoopsFormLabel('',"<a href='admin.php?fct=$fct&op=edit&define_id=".$domain_out->getVar('dom_id')."&domain=".urlencode($domain)."'>"._EDIT."</a>&nbsp;<a href='admin.php?fct=$fct&op=delete&define_id=".$domain_out->getVar('dom_id')."&domain=".urlencode($domain)."'>"._DELETE."</a>"));
				$def_form->addElement($eletray[$domain_out->getVar('dom_id')]);	
			}
		}

        $def_form->addElement(new XoopsFormHidden('op', 'save'));	
		$def_form->addElement(new XoopsFormHidden('fct', $fct));				
        $def_form->addElement(new XoopsFormButton('', 'button', _GO, 'submit'));
        echo '<a href="'.XOOPS_URL.'/modules/multisite/admin.php?op=addnew&fct='.$fct.'&domain='.urlencode($domain).'">'. _MD_AM_DEFADD .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$module->name().'&nbsp;&raquo;&raquo;&nbsp;'.$obj_domain->getVar('dom_value').'<br /><br />';
		$def_form->display();

        $op = 'addnew';
    }


	if ($op == 'addnew')
	{

		if ($_GET['op']==$op)
			echo '<a href="'.XOOPS_URL.'/modules/multisite/admin.php?op=list&fct='.$fct.'&domain='.urlencode($domain).'">'. _MD_AM_DEFLIST .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$module->name().'&nbsp;&raquo;&raquo;&nbsp;'.$obj_domain->getVar('dom_value').'<br /><br />';
	    $nform = new XoopsThemeForm(_AM_SELECT_DEFINENEW, 'newform', 'admin.php?fct=definesadmin', 'post', true);
    	$nform->setExtra('enctype="multipart/form-data"');
		$eletray = new XoopsFormElementTray(_AM_SELECT_DEFINENEW, "&nbsp;");
		$eletray->addElement(new XoopsFormText(_AM_SELECT_DEFINENAME, 'define_name[\'new\']', 50, 255,""));
		$eletray->addElement(new XoopsFormText(_AM_SELECT_DEFINEVALUE, 'define_var[\'new\']', 50, 255,""));		
		$nform->addElement(new XoopsFormHidden('domain', urlencode($domain)));
		$nform->addElement(new XoopsFormHidden('fct', $fct));		
		$nform->addElement($eletray);
        $nform->addElement(new XoopsFormHidden('op', 'save'));	
        $nform->addElement(new XoopsFormButton('', 'button', _GO, 'submit'));
		$nform->display();
		footer_adminMenu();
		echo chronolabs_inline(false); xoops_cp_footer();	
		exit;
	}

	if ($op == 'delete')
	{

		$define = $domain_handler->getDomain($define_id);
		$define_name = $domain_handler->getDomain($define->getVar('dom_pid'));
		
        if (!empty($use_mysession) && $xoopsConfig['use_mysession'] == 0 && $session_name != '') {
            setcookie($session_name, session_id(), time()+(60*intval($session_expire)), '/', '.xoops.org', 0);
        }

		$delete_def_a = $domain_handler->deleteDomain($define);
		$delete_def_b = $domain_handler->deleteDomain($define_name);
	
        // Clean cached files, may take long time
        // User reigister_shutdown_function to keep running after connection closes so that cleaning cached files can be finished
        // Cache management should be performed on a separate page
        register_shutdown_function( array( &$xoopsTpl, 'clear_all_cache' ) );

        if ($lang_updated) {
            // Flush cache files for cpanel GUIs
            xoops_load("cpanel", "system");
            XoopsSystemCpanel::flush();
        }

		if ($delete_def_a && $delete_def_b)
	        redirect_header("admin.php?fct=definesadmin&domain=".urlencode($domain), 2, _MD_AM_DBUPDATED);
		else
		    redirect_header("admin.php?fct=definesadmin&domain=".urlencode($domain), 2, _MD_AM_DBFAILED);
		footer_adminMenu();
		echo chronolabs_inline(false); xoops_cp_footer();			
		exit;
	}

	if ($op == 'edit')
	{
		$define = $domain_handler->getDomain($define_id);
		$define_name = $domain_handler->getDomain($define->getVar('dom_pid'));

		if ($_GET['op']==$op)
			echo '<a href="'.XOOPS_URL.'/modules/multisite/admin.php?op=list&fct='.$fct.'&domain='.urlencode($domain).'">'. _MD_AM_DEFLIST .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$module->name().'&nbsp;&raquo;&raquo;&nbsp;'.$obj_domain->getVar('dom_value').'<br /><br />';
	    $nform = new XoopsThemeForm(_AM_SELECT_DEFINEEDIT, 'newform', 'admin.php?fct=definesadmin', 'post', true);
    	$nform->setExtra('enctype="multipart/form-data"');
		$eletray = new XoopsFormElementTray(_AM_SELECT_DEFINEEDIT, "&nbsp;");
		$eletray->addElement(new XoopsFormText(_AM_SELECT_DEFINENAME, 'define_name['.$define_id.']', 50, 255,$define_name->getConfValueForOutput()));
		$eletray->addElement(new XoopsFormText(_AM_SELECT_DEFINEVALUE, 'define_var['.$define_id.']', 50, 255,$define->getConfValueForOutput()));		
		$nform->addElement(new XoopsFormHidden('domain', urlencode($domain)));
		$nform->addElement(new XoopsFormHidden('fct', $fct));		
		$nform->addElement($eletray);
        $nform->addElement(new XoopsFormHidden('op', 'save'));	
        $nform->addElement(new XoopsFormButton('', 'button', _GO, 'submit'));
		$nform->display();
		footer_adminMenu();
		echo chronolabs_inline(false); xoops_cp_footer();	
		exit;
	}



    if ($op == 'save') {
		$domain_handler =& xoops_getmodulehandler('domain','multisite');
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header("admin.php?fct=definesadmin&domain=".urlencode($domain), 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
        }

        if (!empty($use_mysession) && $xoopsConfig['use_mysession'] == 0 && $session_name != '') {
            setcookie($session_name, session_id(), time()+(60*intval($session_expire)), '/', '.xoops.org', 0);
        }

		if (is_array($define_var))
			foreach($define_var as $key => $value)
			{
				switch ($key) {
				case "'new'":
				case "new":			
				
					$define = $domain_handler->createDomain();			
					$define->setVar('dom_pid', $obj_domain->getVar('dom_id'));
					$define->setVar('dom_modid', $module->mid());
					$define->setVar('dom_catid', $domcat->getVar('domcat_id'));
					$define->setVar('dom_name', 'define');
					$define->setVar('dom_value', $define_name[$key]);
					$define->setVar('dom_title', '_MD_AM_DEFINES');
					$define->setVar('dom_desc', '_MD_AM_DEFINESDESC');				
					$define->setVar('dom_formtype', 'textbox');
					$define->setVar('dom_valuetype', 'text');				
					$define->setVar('dom_order', 1);								
					$domain_handler->insertDomain($define);
	
					$defineb = $domain_handler->createDomain();			
					$defineb->setVar('dom_pid', $define->getVar('dom_id'));
					$defineb->setVar('dom_modid', $module->mid());
					$defineb->setVar('dom_catid', $domcat->getVar('domcat_id'));
					$defineb->setVar('dom_name', 'define_var');
					$defineb->setVar('dom_value', $value);
					$defineb->setVar('dom_title', '_MD_AM_DEFINESVAR');
					$defineb->setVar('dom_desc', '_MD_AM_DEFINESVARDESC');				
					$defineb->setVar('dom_formtype', 'textbox');
					$defineb->setVar('dom_valuetype', 'text');				
					$defineb->setVar('dom_order', 1);								
					$domain_handler->insertDomain($defineb);
					
					break;
				default:
					$define = $domain_handler->getDomain($key);
					$define->setVar('dom_value', $value);
					$domain_handler->insertDomain($define);
					
					if (isset($define_name[$key])&&!empty($define_name[$key])) {
						$defineb = $domain_handler->getDomain($define->getVar('dom_pid'));
						$defineb->setVar('dom_value', $define_name[$key]);
						$domain_handler->insertDomain($defineb);
					}
					break;
				}
			
			}

        // Clean cached files, may take long time
        // User reigister_shutdown_function to keep running after connection closes so that cleaning cached files can be finished
        // Cache management should be performed on a separate page
        register_shutdown_function( array( &$xoopsTpl, 'clear_all_cache' ) );

        if ($lang_updated) {
            // Flush cache files for cpanel GUIs
            xoops_load("cpanel", "system");
            XoopsSystemCpanel::flush();
        }

        redirect_header("admin.php?fct=definesadmin&domain=".urlencode($domain), 2, _MD_AM_DBUPDATED);
		footer_adminMenu();
		echo chronolabs_inline(false); xoops_cp_footer();			
    }

}
?>
