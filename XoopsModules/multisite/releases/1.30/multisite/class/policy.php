<?php
// $Id: user.php 2191 2008-09-29 13:01:00Z phppp $
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
if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}
/**
 * Class for policies
 * @author Simon Roberts <onokazu@xoops.org>
 * @copyright copyright (c) 2000-2003 XOOPS.org
 * @package kernel
 */
class MultisitePolicy extends XoopsObject
{

    function MultisitePolicy($id = null)
    {
        $this->initVar('pcid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 128);
        $this->initVar('ipstart', XOBJ_DTYPE_TXTBOX, null, true, 16);
        $this->initVar('ipend', XOBJ_DTYPE_TXTBOX, null, true, 16);
        $this->initVar('status', XOBJ_DTYPE_TXTBOX, null, false, 10);
        $this->initVar('agents', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('networknames', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('groups', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('protocol', XOBJ_DTYPE_TXTBOX, null, false, 128);
        $this->initVar('modules', XOBJ_DTYPE_TXTBOX, null, false, 250);
        $this->initVar('redirect_url', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('redirect_message', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('redirect_time', XOBJ_DTYPE_INT, null, false);
        $this->initVar('domains', XOBJ_DTYPE_OTHER, null, false);
        $this->initVar('xml_conf', XOBJ_DTYPE_OTHER, null, false);

    }


    /**
     * get the policies name
	 * @param string $format format for the output, see {@link XoopsObject::getVar()}
     * @return string
     */
    function name($format="S")
    {
        return $this->getVar("name", $format);
    }

    function ipstart($format="S")
    {
        return $this->getVar("ipstart", $format);
    }

    function ipend($format="S")
    {
        return $this->getVar("ipend", $format);
    }

    function status($format="S")
    {
        return $this->getVar("status", $format);
    }

    function agents()
    {
        return $this->getVar("agents");
    }

    function networknames($format="S")
    {
        return $this->getVar("networknames", $format);
    }

    function groups($format="S")
    {
        return $this->getVar("groups", $format);
    }
    function protocol($format="S")
    {
        return $this->getVar("protocol", $format);
    }

    function modules($format="S")
    {
        return $this->getVar("modules", $format);
    }

    function redirect_url($format="S")
    {
        return $this->getVar("redirect_url", $format);
    }

    function redirect_message($format="S")
    {
        return $this->getVar("redirect_message", $format);
    }
	
    function redirect_time()
    {
        return $this->getVar("redirect_time");
    }
	
    function domains()
    {
        return $this->getVar("domains");
    }

    function xml_conf()
    {
        return $this->getVar("xml_conf");
    }

}


/**
* XOOPS policies handler class.
* This class is responsible for providing data access mechanisms to the data source
* of XOOPS user class objects.
*
* @author  Simon Roberts <simon@chronolabs.org.au>
* @package kernel
*/
class MultisitePolicyHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "policies", 'MultisitePolicy', "pcid", "name");
    }
	
	function checkPolicy($policy)
	{
		if (!is_a($policy, 'MultisitePolicy'))
			return false;
			
		// Checks IP Range
		$ban_range_low=ip2long($policy->ipstart());
		$ban_range_up=ip2long($policy->ipend());
		$ip=ip2long($HTTP_SERVER_VARS["REMOTE_ADDR"]);
		if ($ip>=$ban_range_low && $ip<=$ban_range_up)
		{
			$ipmatch=true;
		} 

		// Checks Agents
		foreach(explode('|',$policy->agents()) as $agent)
			if (eregi ($agent, $HTTP_SERVER_VARS['HTTP_USER_AGENT']))
			{
				$agents_match=true;
			}
		
		// Checks Network Names
		$hostaddr = gethostbyaddr($ip);
		foreach(explode('|',$policy->networknames()) as $networkname)
			if (eregi ($networkname, $hostaddr))
			{
				$netnames_match=true;
			}
		
		// Checks User Groups
		global $xoopsUser;
		$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : 3;
		foreach($groups as $group)
			if (in_array ($group, explode("|",$policy->groups())) )
			{
				$groups_match=true;
			}
		
		// Checks Modules
		global $xoopsModule;
		$module = is_object($xoopsModule) ? $xoopsModule->dirname() : '(none)';
			if (in_array ($module, explode("|",$policy->modules())) )
			{
				$modules_match=true;
			}
		
		// Checks Domains
		$domain = str_replace('www.','',strtolower($_SERVER['HTTP_HOST']));
			if (in_array ($domain, explode("|",$policy->domains())) )
			{
				$domain_match=true;
			}
			
		if ($ipmatch == true && $agents_match == true && $netnames_match == true && $groups_match == true && $modules_match == true && $domain_match == true)
		{
			switch($policy->status){
			case "open":
				return true;
				break;
			case "closed":
				header('HTTP/1.1 403 Forbidden');
				exit;
				break;
			case "redirect":
				redirect_header($policy->redirect_url, $policy->redirect_time, $policy->redirect_message);
				return true;
				exit;
			case "hold":
				$tme = time()+$policy->redirect_time;
				while(time()<$tme)
				{
				}
				redirect_header($policy->redirect_url, $policy->redirect_time, $policy->redirect_message);
				return true;
				break;
			case "sleep":
				$tme = time()+$policy->redirect_time;
				while(time()<$tme)
				{
				}
				return true;
				break;
			default:
				return false;
			}
			return false;
		} else {
			return false;
		}
	}
}
?>