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

function write_policy()
{
	$policy_handler =& xoops_getmodulehandler('policy','multisite');
	$pcid = intval($_POST['pcid']);
	if ( $pcid != 0)
    	$policy = $policy_handler->get($pcid);
	else
		$policy = $policy_handler->create();
		
	$policy->setVar('name',$_POST['name']);
	$policy->setVar('ipstart', $_POST['ipstart']);
	$policy->setVar('ipend', $_POST['ipend']);
	$policy->setVar('agents',$_POST['agents']);
	$policy->setVar('networknames', $_POST['networknames']);
	$policy->setVar('status', implode('|',$_POST['status']));
	$policy->setVar('groups', implode('|',$_POST['groups']));
	$policy->setVar('modules', implode('|',$_POST['modules']));
	$policy->setVar('domains', "|".implode('|',$_POST['domains']));
	$policy->setVar('redirect_url',$_POST['redirect_url']);
	$policy->setVar('redirect_message',$_POST['redirect_message']);
	$policy->setVar('redirect_time',$_POST['redirect_time']);

	if (!$policy_handler->insert($policy))
		redirect_header('admin.php?fct=policiesadmin', 3, _MD_AM_WRITEPOLICY_UNSUCCESS);		
	else
		redirect_header('admin.php?fct=policiesadmin', 3, _MD_AM_WRITEPOLICY_SUCCESS);

}

function delete_policy($op, $fct, $id)
{
	global $xoopsDB;
	$sql = "DELETE FROM ".$xoopsDB->prefix('policies')." WHERE pcid = $id";
	if (!$xoopsDB->queryF($sql))
		redirect_header('admin.php?fct=policiesadmin', 3, _MD_AM_DELETEPOLICY_UNSUCCESS);		
	else
		redirect_header('admin.php?fct=policiesadmin', 3, _MD_AM_DELETEPOLICY_SUCCESS);
}

function edit_policies($op, $fct)
{

	global $xoopsDB;
	
	for($ii=1;$ii<=(int)$_POST['total'];$ii++)
	{

		$sql = "UPDATE ".$xoopsDB->prefix('policies')." set `name` = '".addslashes($_POST['name'][$ii])."',  
					`name` = '".addslashes($_POST['name'][$ii])."',  
					`status` = '".addslashes(implode('|',$_POST['status'][$ii]))."', 
					`groups` = '".addslashes(implode('|',$_POST['groups'][$ii]))."', 
					`modules` = '".addslashes(implode('|',$_POST['modules'][$ii]))."', 
					`domains` = '".addslashes("|".implode('|',$_POST['domains'][$ii]))."'
					WHERE pcid = ".$_POST['id'][$ii];

		if(!$xoopsDB->queryF($sql))
			redirect_header('admin.php?fct=policiesadmin', 3, _MD_AM_WRITEPOLICY_UNSUCCESS);
		
	}
	redirect_header('admin.php?fct=policiesadmin', 3, _MD_AM_WRITEPOLICY_SUCCESS);
}

function policy_form($pcid, $op, $fct)
{

	error_reporting(E_ALL);
	if (!class_exists('XoopsFormLoader'))
		include(XOOPS_ROOT_PATH.'/class/xoopsformloader.php');

	if (!class_exists('XoopsLists'))
		include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
		
	
	$op = "edit";
	
	$xl = new XoopsLists;
		
    $module_handler =& xoops_getmodulehandler('module','multisite');
    $module =& $module_handler->getByDirname('multisite');

	$policy_handler =& xoops_getmodulehandler('policy','multisite');
	$group_handler =& xoops_gethandler('group');
	$config_handler =& xoops_getmodulehandler('domain', 'multisite');

	$critera_z = new CriteriaCompo(new Criteria('1', 1));
	$critera_z->setStart($pos);
	$critera_z->setLimit($pos);
	$policies = $policy_handler->getObjects($critera_z);

	$critera_x = new CriteriaCompo(new Criteria('dom_catid', XOOPS_DOMAIN));
	$critera_x->add(new Criteria('dom_name', 'domain')) ;
	$domains = $config_handler->getDomains($critera_x);

	$domains_list = array();
	$domains_list['all'] = _AM_ALLDOMAINS;
	foreach($domains as $domain)
		$domains_list[$domain->getVar('dom_value')] = $domain->getVar('dom_value');
	unset($domains);
	unset($domain);
	
	$critera_y = new CriteriaCompo(new Criteria('1', '1'));
	$groups = $group_handler->getObjects($critera_y);
	foreach($groups as $group)
		$groups_list[$group->getVar('groupid')] = $group->getVar('name');
	
	unset($groups);
	unset($group);
	
	$modules_list = array_merge(array("" => "(none)"),$xl->getModulesList());
	$status_list = array('open' => _MD_AM_OPEN,'closed' => _MD_AM_CLOSED,'redirect' => _MD_AM_REDIRECT,'hold' => _MD_AM_HOLD,'sleep' => _MD_AM_SLEEP);

	unset($modules);
	unset($module);
	
	if ($pcid==0) {
		$op = "addpolicy";
		echo "<h2>Add Policy</h2>";
		
		$ipstart = '0.0.0.0';
		$ipend = '255.255.255.255';
		$agents = '*';
		$networknames='*';
		$status=array('open');
		$groups=explode('|','1|2|3');
		$modules=$xl->getModulesList();
		$domains = $domains_list;
	} else {
		$op = "editpolicy";
		echo "<h2>Edit Policy</h2>";
		$policy = $policy_handler->get($pcid);
		
		$name = $policy->name();
		$ipstart = $policy->ipstart();
		$ipend = $policy->ipend();
		$agents = $policy->agents();
		$networknames=$policy->networknames();
		$status=explode('|', $policy->status());
		$groups=explode('|', $policy->groups());
		$modules=explode('|', $policy->modules());
		$domains = explode('|', $policy->domains());
		$redirect_url = $policy->redirect_url();
		$redirect_message = $policy->redirect_message();
		$redirect_time = $policy->redirect_time();
	}

	$form = new XoopsThemeForm(_MD_AM_POLICYFORM, array("op","fct"), xoops_getenv('PHP_SELF')."?fct=$fct&op=$op");
	$form->setExtra('enctype="multipart/form-data"');

	$xl = new XoopsLists;

	$ii++;
	
	$form->addElement(new XoopsFormText(_MD_AM_NAME, "name", 35, 128 , $name));
	$form->addElement(new XoopsFormText(_MD_AM_IPSTART, "ipstart", 35, 128 , $ipstart));
	$form->addElement(new XoopsFormText(_MD_AM_IPEND, "ipend", 35, 128 , $ipend));
	$form->addElement(new XoopsFormText(_MD_AM_AGENTS, "agents", 35, 128 , $agents));
	$form->addElement(new XoopsFormTextArea(_MD_AM_NETNAMES, "networknames", $networknames, 4, 35  ));
	
	$status[$ii] = new XoopsFormSelect(_MD_AM_STATUS, "status[]", $status, 1 , false);
	$status[$ii]->addOptionArray( $status_list );
	$form->addElement( $status[$ii] );
	
	$group[$ii] = new XoopsFormSelect(_MD_AM_GROUPS, "groups", $groups, 4 , true);
	$group[$ii]->addOptionArray( $groups_list );
	$form->addElement( $group[$ii] );
	
	$module[$ii] = new XoopsFormSelect(_MD_AM_MODULES, "modules", $modules, 4 , true);
	$module[$ii]->addOptionArray( $modules_list );
	$form->addElement( $module[$ii] );

	$domain[$ii] = new XoopsFormSelect(_MD_AM_DOMAINSIN, "domains", $domains, 4 , true);
	$domain[$ii]->addOptionArray( $domains_list );
	$form->addElement( $domain[$ii] );
	
	$form->addElement(new XoopsFormText(_MD_AM_REDIRECTURL, "redirect_url", 35, 128 , $redirect_url));
	$form->addElement(new XoopsFormText(_MD_AM_REDIRECTMSG, "redirect_message", 35, 128 , $redirect_message));
	$form->addElement(new XoopsFormText(_MD_AM_REDIRECTTIME, "redirect_time", 35, 2 , $redirect_time));
	
	$form->addElement(new XoopsFormHidden('op', $op));
	$form->addElement(new XoopsFormHidden('pcid', $pcid));
	$submit = new XoopsFormButton("", "submit", _SUBMIT, "submit");
	$form->addElement($submit);

	echo $form->render();
}


function xoops_policy_list($op, $fct, $pos, $num)
{
	include(XOOPS_ROOT_PATH.'/class/xoopsformloader.php');
	include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
	error_reporting(E_ALL);
	$op = "editgroup";
	
	$xl = new XoopsLists;
	
        xoops_cp_header();
	adminMenu(0);
	
    $module_handler =& xoops_getmodulehandler('module','multisite');
    $module =& $module_handler->getByDirname('multisite');

	$policy_handler =& xoops_getmodulehandler('policy','multisite');
	$group_handler =& xoops_gethandler('group');
	$config_handler =& xoops_gethandler('config');
	$domain_handler =& xoops_getmodulehandler('domain', 'multisite');
	
	$critera_z = new CriteriaCompo(new Criteria('1', 1));
	$critera_z->setStart($pos);
	$critera_z->setLimit($pos);
	$policies = $policy_handler->getObjects($critera_z);

	$critera_x = new CriteriaCompo(new Criteria('dom_catid', XOOPS_DOMAIN));
	$critera_x->add(new Criteria('dom_name', 'domain')) ;
	$domains_a = $domain_handler->getDomains($critera_x);

	$critera_y = new CriteriaCompo(new Criteria('1', '1'));
	$groups = $group_handler->getObjects($critera_y);
	foreach($groups as $group)
		$groups_list[$group->getVar('groupid')] = $group->getVar('name');

	$domains_list = array();
	$domain_list['all'] = _AM_ALLDOMAINS;
	foreach($domains_a as $domainb)
		$domain_list[$domainb->getVar('dom_value')] = $domainb->getVar('dom_value');
	
	$modules_list = array_merge(array("" => "(none)"),$xl->getModulesList());
	$status_list = array('open' => _MD_AM_OPEN,'closed' => _MD_AM_CLOSED,'redirect' => _MD_AM_REDIRECT,'hold' => _MD_AM_HOLD,'sleep' => _MD_AM_SLEEP);

	$policycount = count($policies);
	echo "<h2>"._MD_AM_CURRENTPOLICIES."</h2>";
    echo "<form action='admin.php?fct=$fct&op=$op&pos=$pos&num=$num' name='policyadmin' method='post'>
    <table width='100%' class='outer' cellpadding='4' cellspacing='1'>
    <tr valign='middle' align='center'>
    <th width='15%'>"._MD_AM_NAME."</th>
    <th width='20%'>"._MD_AM_STATUS."</th>
    <th width='10%'>"._MD_AM_MODULES."</th>
    <th width='10%'>"._MD_AM_DOMAINSIN."</th>
    <th width='10%'>"._MD_AM_GROUPS."</th>
    <th>"._MD_AM_ACTION."</th>
    </tr>
    ";
	
	foreach ($policies as $policy)
	{
		echo "<tr>";
	    $class = ($class == 'even') ? 'odd' : 'even';
		
		$ii++;
		$yy = $policy->getVar('pcid');

		$domains_options = '';
		foreach ( $domain_list as $key => $mod ) {
			if (  in_array($key, explode('|',$policy->domains())) ) {
				$domains_options .= "<option value='$key' selected='selected'>$mod</a>" ;
			} else {
				$domains_options .= "<option value='$key'>$mod</a>" ;
			}
		}
		
		$modules_options = '';
		foreach ( $modules_list as $key => $mod ) {
			if (  in_array($key, explode('|',$policy->modules())) ) {
				$modules_options .= "<option value='$key' selected='selected'>$mod</a>" ;
			} else {
				$modules_options .= "<option value='$key'>$mod</a>" ;
			}
		}
		
		$groups_options = '';
		foreach ( $groups_list as $key => $mod ) {
			if (  in_array($key, explode('|',$policy->groups())) ) {
				$groups_options .= "<option value='$key' selected='selected'>$mod</a>" ;
			} else {
				$groups_options .= "<option value='$key'>$mod</a>" ;
			}
		}		
		
		$status_options = '';
		foreach ( $status_list as $key => $mod ) {
			if (  in_array($key, explode('|',$policy->status())) ) {
				$status_options .= "<option value='$key' selected='selected'>$mod</a>" ;
			} else {
				$status_options .= "<option value='$key'>$mod</a>" ;
			}
		}		

		echo "<td class='$class' align='center'>
             <input type='textbox' name='name[$ii]' value='".$policy->name()."' size='34' maxlength='128' />
        </td>";
		
		echo "<td class='$class' align='center'>
            <select name='status[$ii][]' size='5'>
            $status_options
            </select>
        </td>";

		echo "<td class='$class' align='center'>
            <select name='modules[$ii][]' size='5' multiple='multiple'>
            $modules_options
            </select>
        </td>";
		
		echo "<td class='$class' align='center'>
            <select name='domains[$ii][]' size='5' multiple='multiple'>
            $domains_options
            </select>
        </td>";

		echo "<td class='$class' align='center'>
            <select name='groups[$ii][]' size='5' multiple='multiple'>
            $groups_options
            </select>
        </td>";

		$label_txt = "<a href='".XOOPS_URL."/modules/multisite/admin.php?fct=$fct&op=delete&id=$yy'>Delete</a>";
		$label_txt .= "&nbsp;<a href='".XOOPS_URL."/modules/multisite/admin.php?fct=$fct&op=edit&id=$yy'>Edit</a>";

		echo "<td class='$class' align='center'>
            $label_txt
			<input type='hidden' name='id[$ii]' value='$yy' />
        </td>";
	
		echo "<tr/>";

		
	}

	echo "<tr class='footer'><td colspan='6'><input type='hidden' name='total' value='$ii' />";
	echo "<input type='hidden' name='op' value='$op' />";
	echo "<input type='hidden' name='num' value='$num' />";
	echo "<input type='hidden' name='pos' value='$pos' />";

    echo $GLOBALS['xoopsSecurity']->getTokenHTML()."
    <input type='submit' name='submit' value='"._SUBMIT."' />
    </td></tr></table>
    </form>	";

	@policy_form(0, $op, $fct);
		
   footer_adminMenu();
 xoops_cp_footer();
}


?>