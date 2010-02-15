<?php
// $Id: main.php 2879 2009-02-27 00:53:34Z wishcraft $
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
    if (isset($_POST)) {
        foreach ( $_POST as $k => $v ) {
            ${$k} = $v;
        }
    }
    if (isset($_REQUEST['op'])) {
        $op = trim($_REQUEST['op']);
    }

    if (isset($_REQUEST['confcat_id'])) {
        $confcat_id = intval($_REQUEST['confcat_id']);
    }
	if (isset($_REQUEST['domain'])) {
        $domain = (string)urldecode(($_REQUEST['domain']));
    } else {
		$domain = XOOPS_URL;
	}
	if ($op == 'copycat')
	{
		   xoops_cp_header();
		   
			$config_handler =& xoops_gethandler('config');
			$confopt_handler =& xoops_gethandler('configoption');
			
			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('conf_modid', 0));
			$criteria->add(new Criteria('conf_catid', $confcat_id));
			$configs = $config_handler->getConfigs($criteria);
			$domain_handler =& xoops_getmodulehandler('domainitem','multisite');
			$domainoption_handler =& xoops_getmodulehandler('domainoption','multisite');
			$dom_catid = (int)$_REQUEST['dom_catid'];
			$dom_pid = (int)$_REQUEST['dom_id'];
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
 					$msg .= _MD_AM_ERRORCOPY."&nbsp;<strong>".$domconf->getVar('dom_name')."</strong><br/>";
				} else {
					$msg .= _MD_AM_SUCCESSCOPY."&nbsp;<strong>".$domconf->getVar('dom_name')."</strong><br/>";
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
			
   		   redirect_header(XOOPS_URL."/modules/multisite/admin.php?fct=$fct&op=show&confcat_id=$confcat_id&domain=".urlencode($domain),1,$msg);
		   xoops_cp_footer();
		   exit;
	}
	
	if ($op == 'copymodule')
	{
		   xoops_cp_header();
		   
			$config_handler =& xoops_gethandler('config');
			$confopt_handler =& xoops_gethandler('configoption');

			$mod_id = (int)$_REQUEST['mod_id'];			
			
			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('conf_modid', $mod_id));
			$configs = $config_handler->getConfigs($criteria);
			$domain_handler =& xoops_getmodulehandler('domainitem','multisite');
			$domainoption_handler =& xoops_getmodulehandler('domainoption','multisite');

			$dom_pid = (int)$_REQUEST['dom_id'];
			foreach ($configs as $config) {
				$domconf = $domain_handler->create();
				$domconf->setVar('dom_pid', $dom_pid);
				$domconf->setVar('dom_modid', $config->getVar('conf_modid'));
				$domconf->setVar('dom_catid', $config->getVar('conf_catid'));
				$domconf->setVar('dom_name', $config->getVar('conf_name'));
				$domconf->setVar('dom_title', $config->getVar('conf_title'));
				$domconf->setVar('dom_value', $config->getConfValueForOutput());
				$domconf->setVar('dom_desc', $config->getVar('conf_desc'));
				$domconf->setVar('dom_formtype', $config->getVar('conf_formtype'));
				$domconf->setVar('dom_valuetype', $config->getVar('conf_valuetype'));
				$domconf->setVar('dom_order', $config->getVar('conf_order'));
				
				if(!$domain_handler->insert($domconf))
 {
 					$msg .= _MD_AM_ERRORCOPY."&nbsp;<strong>".$domconf->getVar('dom_name')."</strong><br/>";
				} else {
					$msg .= _MD_AM_SUCCESSCOPY."&nbsp;<strong>".$domconf->getVar('dom_name')."</strong><br/>";
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

   		   redirect_header(XOOPS_URL."/modules/multisite/admin.php?fct=$fct&op=showmod&mod=$mod_id&domain=".urlencode($domain),1,$msg);
		   xoops_cp_footer();
		   exit;
	}
	
    if ($op == 'list') {
        $confcat_handler = xoops_gethandler('configcategory');
		$module_handler = xoops_gethandler('module');
        $modules = $module_handler->getObjects();
		$confcats = $confcat_handler->getObjects();
        $modulecount = count($modules);
		$catcount = count($confcats);
        xoops_cp_header();
		adminMenu(XOOPS_MULTISITE_PREF);
		echo '<h4>'._MD_AM_SITEPREF_DOMAIN.'</h4><ul>';
		error_reporting(E_ALL);
		include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
		if (!class_exists('XoopsFormSelectDomains'))
			include_once XOOPS_ROOT_PATH . '/modules/multisite/class/formselectdomains.php';
	
	    $sform = new XoopsThemeForm(_AM_SELECT_DOMAIN, array("domain","fct","op", "confcat_id"),  "admin.php?fct=$fct&op=$op&confcat_id=$confcat_id&domain=".urlencode($domain));
    	$sform->setExtra('enctype="multipart/form-data"');
		$sform->addElement(new XoopsFormSelectDomains(_AM_DOMAINS, "domain", !empty($domain)?urlencode($domain):urlencode(XOOPS_URL), 1, false));
		$sform->addElement(new XoopsFormButton('', '', _SUBMIT, 'submit'));
		$sform->display();
		
        echo '<h4>'._MD_AM_SITEPREF.'</h4><ul>';
        for ($i = 0; $i < $catcount; $i++) {
            echo '<li>'.constant($confcats[$i]->getVar('confcat_name')).' [<a href="admin.php?fct=preferences&amp;op=show&amp;confcat_id='.$confcats[$i]->getVar('confcat_id')."&amp;domain=".urlencode($domain)."\">"._EDIT."</a>]</li>";
        }
        echo '</ul>';
			
        echo '<h4>'._MD_AM_MODULEPREF.'</h4><ul>';
        for ($i = 0; $i < $modulecount; $i++) {
			switch ($modules[$i]->dirname()) {
			case "multisite":
			case "system":
				break;
			default:
	            echo '<li>'.$modules[$i]->name().' [<a href="admin.php?fct=preferences&amp;op=showmod&amp;mod='.$modules[$i]->getVar('mid')."&amp;domain=".urlencode($domain)."\">"._EDIT."</a>]</li>";
			}
        }
        echo '</ul>';
        footer_adminMenu();
		xoops_cp_footer();
        exit();
    }

    if ($op == 'showmod') {
        $domain_handler =& xoops_getmodulehandler('domain','multisite');
        $mod = isset($_GET['mod']) ? intval($_GET['mod']) : 0;
        if (empty($mod)) {
            header('Location: admin.php?fct=preferences');
            exit();
        }
		
		$purl = parse_url($domain);
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('dom_name', 'domain'));
		$criteria->add(new Criteria('dom_value', $purl['host']));
		
		if(!$domain_handler->getDomainCount($criteria))
		{
			redirect_header('admin.php?fct=domainsadmin', 1);
		} else {
			$obj_domain = $domain_handler->getDomains($criteria);
			$obj_domain = $obj_domain[0];
		}

        include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
        $form = new XoopsThemeForm(_MD_AM_MODCONFIG, 'pref_form', 'admin.php?fct=preferences', 'post', true);
        $module_handler =& xoops_gethandler('module');
        $module =& $module_handler->get($mod);

		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('dom_pid', $obj_domain->getVar('dom_id')));
		$criteria->add(new Criteria('dom_modid', $mod));
		if ($domain_handler->getDomainCount($criteria)==0)
		{
			xoops_cp_header();
			xoops_confirm(array('fct' => $fct, 'op' => 'copymodule', 'dom_id' => $obj_domain->getVar('dom_id'), 'mod_id' => $mod, "domain" => $domain), "admin.php", sprintf(_MD_AM_MESSAGEMODULECOPY, $purl['host'], $module->name()));
			xoops_cp_footer();
			exit;
		}

		$criteria = new CriteriaCompo();
        $criteria->add(new Criteria('dom_pid', $obj_domain->getVar('dom_id')));
		$criteria->add(new Criteria('dom_modid', $mod));
		$domains = $domain_handler->getDomains($criteria);
        $count = count($domains);
        if ($count < 1) {
            redirect_header('admin.php?fct=preferences', 1);
        }
		
        if (file_exists(XOOPS_ROOT_PATH.'/modules/'.$module->getVar('dirname').'/language/'.$xoopsConfig['language'].'/modinfo.php')) {
            include_once XOOPS_ROOT_PATH.'/modules/'.$module->getVar('dirname').'/language/'.$xoopsConfig['language'].'/modinfo.php';
        }

        // if has comments feature, need comment lang file
        if ($module->getVar('hascomments') == 1) {
            include_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/comment.php';
        }
        // RMV-NOTIFY
        // if has notification feature, need notification lang file
        if ($module->getVar('hasnotification') == 1) {
            include_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/notification.php';
        }

        $modname = $module->getVar('name');
        if ($module->getInfo('adminindex')) {
            $form->addElement(new XoopsFormHidden('redirect', XOOPS_URL.'/modules/'.$module->getVar('dirname').'/'.$module->getInfo('adminindex')));
        }
        for ($i = 0; $i < $count; $i++) {
            $title = (!defined($domains[$i]->getVar('dom_desc')) || constant($domains[$i]->getVar('dom_desc')) == '') ? constant($domains[$i]->getVar('dom_title')) : constant($domains[$i]->getVar('dom_title')).'<br /><br /><span style="font-weight:normal;">'.constant($domains[$i]->getVar('dom_desc')).'</span>';
            switch ($domains[$i]->getVar('dom_formtype')) {
            case 'textarea':
                $myts =& MyTextSanitizer::getInstance();
                if ($domains[$i]->getVar('dom_valuetype') == 'array') {
                    // this is exceptional.. only when value type is arrayneed a smarter way for this
                    $ele = ($domains[$i]->getVar('dom_value') != '') ? new XoopsFormTextArea($title, $domains[$i]->getVar('dom_name'), $myts->htmlspecialchars(implode('|', $domains[$i]->getConfValueForOutput())), 5, 50) : new XoopsFormTextArea($title, $domains[$i]->getVar('dom_name'), '', 5, 50);
                } else {
                    $ele = new XoopsFormTextArea($title, $domains[$i]->getVar('dom_name'), $myts->htmlspecialchars($domains[$i]->getConfValueForOutput()), 5, 50);
                }
                break;
            case 'domain':
                $ele = new XoopsFormSelectDomains($title, $domains[$i]->getVar('dom_name'), $domains[$i]->getConfValueForOutput());
				break;
            case 'multidomain':
		    	$ele = new XoopsFormSelectDomains($title, $domains[$i]->getVar('dom_name'), $domains[$i]->getConfValueForOutput(), 5, true);
				break;
            case 'select':
                $ele = new XoopsFormSelect($title, $domains[$i]->getVar('dom_name'), $domains[$i]->getConfValueForOutput());
                $options = $domain_handler->getDomainOptions(new Criteria('dom_id', $domains[$i]->getVar('dom_id')));
                $opcount = count($options);
                for ($j = 0; $j < $opcount; $j++) {
                    $optval = defined($options[$j]->getVar('confop_value')) ? constant($options[$j]->getVar('confop_value')) : $options[$j]->getVar('confop_value');
                    $optkey = defined($options[$j]->getVar('confop_name')) ? constant($options[$j]->getVar('confop_name')) : $options[$j]->getVar('confop_name');
                    $ele->addOption($optval, $optkey);
                }
                break;
            case 'select_multi':
                $ele = new XoopsFormSelect($title, $domains[$i]->getVar('dom_name'), $domains[$i]->getConfValueForOutput(), 5, true);
                $options = $domain_handler->getDomainOptions(new Criteria('dom_id', $domains[$i]->getVar('dom_id')));
                $opcount = count($options);
                for ($j = 0; $j < $opcount; $j++) {
                    $optval = defined($options[$j]->getVar('confop_value')) ? constant($options[$j]->getVar('confop_value')) : $options[$j]->getVar('confop_value');
                    $optkey = defined($options[$j]->getVar('confop_name')) ? constant($options[$j]->getVar('confop_name')) : $options[$j]->getVar('confop_name');
                    $ele->addOption($optval, $optkey);
                }
                break;
            case 'yesno':
                $ele = new XoopsFormRadioYN($title, $domains[$i]->getVar('dom_name'), $domains[$i]->getConfValueForOutput(), _YES, _NO);
                break;
            case 'group':
                include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
                $ele = new XoopsFormSelectGroup($title, $domains[$i]->getVar('dom_name'), false, $domains[$i]->getConfValueForOutput(), 1, false);
                break;
            case 'group_multi':
                include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
                $ele = new XoopsFormSelectGroup($title, $domains[$i]->getVar('dom_name'), false, $domains[$i]->getConfValueForOutput(), 5, true);
                break;
            // RMV-NOTIFY: added 'user' and 'user_multi'
            case 'user':
                include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
                $ele = new XoopsFormSelectUser($title, $domains[$i]->getVar('dom_name'), false, $domains[$i]->getConfValueForOutput(), 1, false);
                break;
            case 'user_multi':
                include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
                $ele = new XoopsFormSelectUser($title, $domains[$i]->getVar('dom_name'), false, $domains[$i]->getConfValueForOutput(), 5, true);
                break;
            case 'password':
                $myts =& MyTextSanitizer::getInstance();
                $ele = new XoopsFormPassword($title, $domains[$i]->getVar('dom_name'), 50, 255, $myts->htmlspecialchars($domains[$i]->getConfValueForOutput()));
                break;
            case 'color':
                $myts =& MyTextSanitizer::getInstance();
                $ele = new XoopsFormColorPicker($title, $domains[$i]->getVar('dom_name'), $myts->htmlspecialchars($domains[$i]->getConfValueForOutput()));
                break;
            case 'hidden':
                $myts =& MyTextSanitizer::getInstance();
                $ele = new XoopsFormHidden( $domains[$i]->getVar('dom_name'), $myts->htmlspecialchars( $domains[$i]->getConfValueForOutput() ) );
                break;
            case 'textbox':
            default:
                $myts =& MyTextSanitizer::getInstance();
                $ele = new XoopsFormText($title, $domains[$i]->getVar('dom_name'), 50, 255, $myts->htmlspecialchars($domains[$i]->getConfValueForOutput()));
                break;
            }
            $hidden = new XoopsFormHidden('dom_ids[]', $domains[$i]->getVar('dom_id'));
            $form->addElement($ele);
            $form->addElement($hidden);
            unset($ele);
            unset($hidden);
        }
        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormHidden('domain', $domain));		
        $form->addElement(new XoopsFormButton('', 'button', _GO, 'submit'));
		xoops_cp_header();
		adminMenu(XOOPS_MULTISITE_PREF);
        echo '<a href="'.XOOPS_URL.'/modules/multisite/admin.php?fct='.$fct.'&domain='.urlencode($domain).'">'. _MD_AM_PREFMAIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$module->name().'&nbsp;&raquo;&raquo;&nbsp;'.$obj_domain->getVar('dom_value').'<br /><br />';
		$form->display();
		footer_adminMenu();
		xoops_cp_footer();
        exit();
    }

    if ($op == 'show') {
		error_reporting(E_ALL);
        if (empty($confcat_id)) {
            $confcat_id = 1;
        }
        $confcat_handler =& xoops_gethandler('configcategory');
        $confcat =& $confcat_handler->get($confcat_id);
        if (!is_object($confcat)) {
            redirect_header('admin.php?fct=preferences', 1);
        }
		
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
		
		
		$purl = parse_url($domain);
		$domain_handler =& xoops_getmodulehandler('domain','multisite');
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('dom_name', 'domain'));
		$criteria->add(new Criteria('dom_value', $purl['host']));
		
		if(!$domain_handler->getDomainCount($criteria))
		{
			redirect_header('admin.php?fct=domainsadmin', 1);
		} else {
			$obj_domain = $domain_handler->getDomains($criteria);
			$obj_domain = $obj_domain[0];
		}

		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('dom_pid', $obj_domain->getVar('dom_id')));
		$criteria->add(new Criteria('dom_catid', $domcat->getVar('domcat_id')));
		if ($domain_handler->getDomainCount($criteria)==0)
		{
			xoops_cp_header();
			xoops_confirm(array('fct' => $fct, 'op' => 'copycat', 'dom_id' => $obj_domain->getVar('dom_id'), 'confcat_id' => $confcat_id, 'dom_catid' => $domcat->getVar('domcat_id'), "domain" => $domain), "admin.php", sprintf(_MD_AM_MESSAGECOPY, $purl['host'], constant($domcat->getVar('domcat_name'))));
			xoops_cp_footer();
			exit;
		}
		
        include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
        include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
        $form = new XoopsThemeForm(constant($confcat->getVar('confcat_name')), 'pref_form', 'admin.php?fct=preferences', 'post', true);
        $config_handler =& xoops_gethandler('config');
        $criteria = new CriteriaCompo();
		$criteria->add(new Criteria('dom_pid', $obj_domain->getVar('dom_id')));
        $criteria->add(new Criteria('dom_modid', 0));
        $criteria->add(new Criteria('dom_catid', $domcat->getVar('domcat_id')));
        $config = $domain_handler->getDomains($criteria);
        $confcount = count($config);
        for ($i = 0; $i < $confcount; $i++) {
            $title = (!defined($config[$i]->getVar('dom_desc')) || constant($config[$i]->getVar('dom_desc')) == '') ? constant($config[$i]->getVar('dom_title')) : constant($config[$i]->getVar('dom_title')).'<br /><br /><span style="font-weight:normal;">'.constant($config[$i]->getVar('dom_desc')).'</span>';
            switch ($config[$i]->getVar('dom_formtype')) {
            case 'textarea':
                $myts =& MyTextSanitizer::getInstance();
                if ($config[$i]->getVar('dom_valuetype') == 'array') {
                    // this is exceptional.. only when value type is arrayneed a smarter way for this
                    $ele = ($config[$i]->getVar('dom_value') != '') ? new XoopsFormTextArea($title, $config[$i]->getVar('dom_name'), $myts->htmlspecialchars(implode('|', $config[$i]->getConfValueForOutput())), 5, 50) : new XoopsFormTextArea($title, $config[$i]->getVar('dom_name'), '', 5, 50);
                } else {
                    $ele = new XoopsFormTextArea($title, $config[$i]->getVar('dom_name'), $myts->htmlspecialchars($config[$i]->getConfValueForOutput()), 5, 50);
                }
                break;
            case 'select':
                $ele = new XoopsFormSelect($title, $config[$i]->getVar('dom_name'), $config[$i]->getConfValueForOutput());
                $options = $domain_handler->getDomainOptions(new Criteria('dom_id', $config[$i]->getVar('dom_id')));
                $opcount = count($options);
                for ($j = 0; $j < $opcount; $j++) {
                    $optval = defined($options[$j]->getVar('domop_value')) ? constant($options[$j]->getVar('domop_value')) : $options[$j]->getVar('domop_value');
                    $optkey = defined($options[$j]->getVar('domop_name')) ? constant($options[$j]->getVar('domop_name')) : $options[$j]->getVar('domop_name');
                    $ele->addOption($optval, $optkey);
                }
                break;
            case 'domain':
                $ele = new XoopsFormSelectDomains($title, $domains[$i]->getVar('dom_name'), $domains[$i]->getConfValueForOutput());
				break;
            case 'multidomain':
		    	$ele = new XoopsFormSelectDomains($title, $domains[$i]->getVar('dom_name'), $domains[$i]->getConfValueForOutput(), 5, true);
				break;
            case 'select_multi':
                $ele = new XoopsFormSelect($title, $config[$i]->getVar('dom_name'), $config[$i]->getConfValueForOutput(), 5, true);
                $options = $domain_handler->getDomainOptions(new Criteria('dom_id', $config[$i]->getVar('dom_id')));
                $opcount = count($options);
                for ($j = 0; $j < $opcount; $j++) {
                    $optval = defined($options[$j]->getVar('domop_value')) ? constant($options[$j]->getVar('domop_value')) : $options[$j]->getVar('domop_value');
                    $optkey = defined($options[$j]->getVar('domop_name')) ? constant($options[$j]->getVar('domop_name')) : $options[$j]->getVar('domop_name');
                    $ele->addOption($optval, $optkey);
                }
                break;
            case 'yesno':
                $ele = new XoopsFormRadioYN($title, $config[$i]->getVar('dom_name'), $config[$i]->getConfValueForOutput(), _YES, _NO);
                break;
            case 'theme':
            case 'theme_multi':
                $ele = ($config[$i]->getVar('dom_formtype') != 'theme_multi') ? new XoopsFormSelect($title, $config[$i]->getVar('dom_name'), $config[$i]->getConfValueForOutput()) : new XoopsFormSelect($title, $config[$i]->getVar('dom_name'), $config[$i]->getConfValueForOutput(), 5, true);
                require_once XOOPS_ROOT_PATH."/class/xoopslists.php";
                $dirlist = XoopsLists::getThemesList();
                if (!empty($dirlist)) {
                    asort($dirlist);
                    $ele->addOptionArray($dirlist);
                }
                //$themeset_handler =& xoops_gethandler('themeset');
                //$themesetlist =& $themeset_handler->getList();
                //asort($themesetlist);
                //foreach ($themesetlist as $key => $name) {
                //  $ele->addOption($key, $name.' ('._MD_AM_THEMESET.')');
                //}
                // old theme value is used to determine whether to update cache or not. kind of dirty way
                $form->addElement(new XoopsFormHidden('_old_theme', $config[$i]->getConfValueForOutput()));
                break;
            case 'tplset':
                $ele = new XoopsFormSelect($title, $config[$i]->getVar('dom_name'), $config[$i]->getConfValueForOutput());
                $tplset_handler =& xoops_gethandler('tplset');
                $tplsetlist = $tplset_handler->getList();
                asort($tplsetlist);
                foreach ($tplsetlist as $key => $name) {
                    $ele->addOption($key, $name);
                }
                // old theme value is used to determine whether to update cache or not. kind of dirty way
                $form->addElement(new XoopsFormHidden('_old_theme', $config[$i]->getConfValueForOutput()));
                break;
            case 'cpanel':
                $ele = new XoopsFormSelect($title, $config[$i]->getVar('dom_name'), $config[$i]->getConfValueForOutput());
                xoops_load("cpanel", "system");
                $list = XoopsSystemCpanel::getGuis();
                $ele->addOptionArray( $list );
                break;
            case 'timezone':
                $ele = new XoopsFormSelectTimezone($title, $config[$i]->getVar('dom_name'), $config[$i]->getConfValueForOutput());
                break;
            case 'language':
                $ele = new XoopsFormSelectLang($title, $config[$i]->getVar('dom_name'), $config[$i]->getConfValueForOutput());
                break;
            case 'startpage':
                $ele = new XoopsFormSelect($title, $config[$i]->getVar('dom_name'), $config[$i]->getConfValueForOutput());
                $module_handler =& xoops_getmodulehandler('module','multisite');
                $criteria = new CriteriaCompo(new Criteria('hasmain', 1));
                $criteria->add(new Criteria('isactive', 1));
                $moduleslist = $module_handler->getList($criteria, true);
                $moduleslist['--'] = _MD_AM_NONE;
                $ele->addOptionArray($moduleslist);
                break;
            case 'group':
                $ele = new XoopsFormSelectGroup($title, $config[$i]->getVar('dom_name'), false, $config[$i]->getConfValueForOutput(), 1, false);
                break;
            case 'group_multi':
                $ele = new XoopsFormSelectGroup($title, $config[$i]->getVar('dom_name'), true, $config[$i]->getConfValueForOutput(), 5, true);
                break;
            // RMV-NOTIFY - added 'user' and 'user_multi'
            case 'user':
                $ele = new XoopsFormSelectUser($title, $config[$i]->getVar('dom_name'), false, $config[$i]->getConfValueForOutput(), 1, false);
                break;
            case 'user_multi':
                $ele = new XoopsFormSelectUser($title, $config[$i]->getVar('dom_name'), false, $config[$i]->getConfValueForOutput(), 5, true);
                break;
            case 'module_cache':
                $module_handler =& xoops_getmodulehandler('module','multisite');
                $modules = $module_handler->getObjects(new Criteria('hasmain', 1), true);
                $currrent_val = $config[$i]->getConfValueForOutput();
                $cache_options = array('0' => _NOCACHE, '30' => sprintf(_SECONDS, 30), '60' => _MINUTE, '300' => sprintf(_MINUTES, 5), '1800' => sprintf(_MINUTES, 30), '3600' => _HOUR, '18000' => sprintf(_HOURS, 5), '86400' => _DAY, '259200' => sprintf(_DAYS, 3), '604800' => _WEEK);
                if (count($modules) > 0) {
                    $ele = new XoopsFormElementTray($title, '<br />');
                    foreach (array_keys($modules) as $mid) {
                        $c_val = isset($currrent_val[$mid]) ? intval($currrent_val[$mid]) : null;
                        $selform = new XoopsFormSelect($modules[$mid]->getVar('name'), $config[$i]->getVar('dom_name')."[$mid]", $c_val);
                        $selform->addOptionArray($cache_options);
                        $ele->addElement($selform);
                        unset($selform);
                    }
                } else {
                    $ele = new XoopsFormLabel($title, _MD_AM_NOMODULE);
                }
                break;
            case 'site_cache':
                $ele = new XoopsFormSelect($title, $config[$i]->getVar('dom_name'), $config[$i]->getConfValueForOutput());
                $ele->addOptionArray(array('0' => _NOCACHE, '30' => sprintf(_SECONDS, 30), '60' => _MINUTE, '300' => sprintf(_MINUTES, 5), '1800' => sprintf(_MINUTES, 30), '3600' => _HOUR, '18000' => sprintf(_HOURS, 5), '86400' => _DAY, '259200' => sprintf(_DAYS, 3), '604800' => _WEEK));
                break;
            case 'password':
                $myts =& MyTextSanitizer::getInstance();
                $ele = new XoopsFormPassword($title, $config[$i]->getVar('dom_name'), 50, 255, $myts->htmlspecialchars($config[$i]->getConfValueForOutput()));
                break;
            case 'color':
                $myts =& MyTextSanitizer::getInstance();
                $ele = new XoopsFormColorPicker($title, $config[$i]->getVar('dom_name'), $myts->htmlspecialchars($config[$i]->getConfValueForOutput()));
                break;
            case 'hidden':
                $myts =& MyTextSanitizer::getInstance();
                $ele = new XoopsFormHidden( $config[$i]->getVar('dom_name'), $myts->htmlspecialchars( $config[$i]->getConfValueForOutput() ) );
                break;
            case 'textbox':
            default:
                $myts =& MyTextSanitizer::getInstance();
                $ele = new XoopsFormText($title, $config[$i]->getVar('dom_name'), 50, 255, $myts->htmlspecialchars($config[$i]->getConfValueForOutput()));
                break;
            }
            $hidden = new XoopsFormHidden('dom_ids[]', $config[$i]->getVar('dom_id'));
            $form->addElement($ele);
            $form->addElement($hidden);
            $hidden_pid = new XoopsFormHidden('dom_pids[]', $config[$i]->getVar('dom_pid'));
			$form->addElement($hidden);
            unset($ele);
            unset($hidden);
        }
        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormButton('', 'button', _GO, 'submit'));
            xoops_cp_header();
	adminMenu(XOOPS_MULTISITE_PREF);
        echo '<a href="'.XOOPS_URL.'/modules/multisite/admin.php?fct='.$fct.'&domain='.urlencode($domain).'">'. _MD_AM_PREFMAIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.constant($confcat->getVar('confcat_name')).'&nbsp;&raquo;&raquo;&nbsp;'.$obj_domain->getVar('dom_value').'<br /><br />';
		$form->display();
		footer_adminMenu();
		xoops_cp_footer();
        exit();
    }

    if ($op == 'save') {
		$domain_handler =& xoops_getmodulehandler('domain','multisite');
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header("admin.php?fct=preferences&domain=".urlencode($domain), 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        require_once(XOOPS_ROOT_PATH.'/class/template.php');
        $xoopsTpl = new XoopsTpl();
        $count = count($dom_ids);
        $tpl_updated = false;
        $theme_updated = false;
        $startmod_updated = false;
        $lang_updated = false;
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $config =& $domain_handler->getDomain($dom_ids[$i]);
                $new_value =& ${$config->getVar('dom_name')};
                if (is_array($new_value) || $new_value != $config->getVar('dom_value')) {
                    // if language has been changed
                    if (!$lang_updated && $config->getVar('dom_name') == 'language') {
                        $xoopsConfig['language'] = ${$config->getVar('dom_name')};
                        $lang_updated = true;
                    }

                    // if default theme has been changed
                    if (!$theme_updated && $config->getVar('dom_name') == 'theme_set') {
                        $member_handler =& xoops_gethandler('member');
                        $member_handler->updateUsersByField('theme', ${$config->getVar('dom_name')});
                        $theme_updated = true;
                    }

                    // if default template set has been changed
                    if (!$tpl_updated && $config->getVar('dom_name') == 'template_set') {
                        // clear cached/compiled files and regenerate them if default theme has been changed
                        if ($xoopsConfig['template_set'] != ${$config->getVar('dom_name')}) {
                            $newtplset = ${$config->getVar('dom_name')};

                            // clear all compiled and cachedfiles
                            $xoopsTpl->clear_compiled_tpl();

                            // generate compiled files for the new theme
                            // block files only for now..
                            $tplfile_handler =& xoops_gethandler('tplfile');
                            $dtemplates = $tplfile_handler->find('default', 'block');
                            $dcount = count($dtemplates);

                            // need to do this to pass to xoops_template_touch function
                            $GLOBALS['xoopsConfig']['template_set'] = $newtplset;

                            for ($i = 0; $i < $dcount; $i++) {
                                $found = $tplfile_handler->find($newtplset, 'block', $dtemplates[$i]->getVar('tpl_refid'), null);
                                if (count($found) > 0) {
                                    // template for the new theme found, compile it
                                    xoops_template_touch($found[0]->getVar('tpl_id'));
                                } else {
                                    // not found, so compile 'default' template file
                                    xoops_template_touch($dtemplates[$i]->getVar('tpl_id'));
                                }
                            }

                            // generate image cache files from image binary data, save them under cache/
                            $image_handler =& xoops_gethandler('imagesetimg');
                            $imagefiles = $image_handler->getObjects(new Criteria('tplset_name', $newtplset), true);
                            foreach (array_keys($imagefiles) as $i) {
                                if (!$fp = fopen(XOOPS_CACHE_PATH.'/'.$newtplset.'_'.$imagefiles[$i]->getVar('imgsetimg_file'), 'wb')) {
                                } else {
                                    fwrite($fp, $imagefiles[$i]->getVar('imgsetimg_body'));
                                    fclose($fp);
                                }
                            }
                        }
                        $tpl_updated = true;
                    }

                    // add read permission for the start module to all groups
                    if (!$startmod_updated  && $new_value != '--' && $config->getVar('dom_name') == 'startpage') {
                        $member_handler =& xoops_gethandler('member');
                        $groups = $member_handler->getGroupList();
                        $moduleperm_handler =& xoops_gethandler('groupperm');
                        $module_handler =& xoops_getmodulehandler('module','multisite');
                        $module = $module_handler->getByDirname($new_value);
                        foreach ($groups as $groupid => $groupname) {
                            if (!$moduleperm_handler->checkRight('module_read', $module->getVar('mid'), $groupid)) {
                                $moduleperm_handler->addRight('module_read', $module->getVar('mid'), $groupid);
                            }
                        }
                        $startmod_updated = true;
                    }

                    $config->setConfValueForInput($new_value);
                    $domain_handler->insertDomain($config);
                }
                unset($new_value);
            }
        }

        if (!empty($use_mysession) && $xoopsConfig['use_mysession'] == 0 && $session_name != '') {
            setcookie($session_name, session_id(), time()+(60*intval($session_expire)), '/',  '', 0);
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

        redirect_header("admin.php?fct=preferences&domain=".urlencode($domain), 2, _MD_AM_DBUPDATED);
    }
}

?>