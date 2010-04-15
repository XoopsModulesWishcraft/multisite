<?php
// $Id: domain.php 2645 2009-01-10 06:37:38Z phppp $
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

require_once XOOPS_ROOT_PATH.'/modules/multisite/class/domainoption.php';
require_once XOOPS_ROOT_PATH.'/modules/multisite/class/domainitem.php';

/**
 * @package     kernel
 *
 * @author        Kazumi Ono    <onokazu@xoops.org>
 * @copyright    copyright (c) 2000-2003 XOOPS.org
 */


/**
* XOOPS domainuration handling class.
* This class acts as an interface for handling general domainurations of XOOPS
* and its modules.
*
*
* @author  Kazumi Ono <webmaster@myweb.ne.jp>
* @todo    Tests that need to be made:
*          - error handling
* @access  public
*/

class MultisiteDomainHandler
{

    /**
     * holds reference to domain item handler(DAO) class
     *
     * @var     object
     * @access    private
     */
    var $_cHandler;

    /**
     * holds reference to domain option handler(DAO) class
     *
     * @var        object
     * @access    private
     */
    var $_oHandler;

    /**
     * holds an array of cached references to domain value arrays,
     *  indexed on module id and category id
     *
     * @var     array
     * @access  private
     */
    var $_cacheddomains = array();

    /**
     * holds an id of creferences to domain value arrays,
     *  indexed on module id and category id
     *
     * @var     array
     * @access  private
     */
    var $_domain_id = 0;
	
	 /**
     * holds an domain object of creferences to domain of XOOPS_URL,
     * indexed on module id and category id
     *
     * @var     object
     * @access  private
     */
    var $_domain;
	
    /**
     * Constructor
     *
     * @param    object  &$db    reference to database object
     */
    function MultisiteDomainHandler(&$db)
    {
        $this->_cHandler = new MultisiteDomainItemHandler($db);
        $this->_oHandler = new MultisiteDomainOptionHandler($db);	
    }

    /**
     * Create a domain
     *
     * @see     MultisiteDomainItem
     * @return    object  reference to the new {@link MultisiteDomainItem}
     */
    function &createDomain()
    {
        $instance =& $this->_cHandler->create();
        return $instance;
    }

    /**
     * Get a domain
     *
     * @param    int     $id             ID of the domain
     * @param    bool    $withoptions    load the domain's options now?
     * @return    object  reference to the {@link MultisiteDomain}
     */
    function &getDomain($id, $withoptions = false)
    {
        $domain =& $this->_cHandler->get($id);
        if ($withoptions == true) {
            $domain->setConfOptions($this->getDomainOptions(new Criteria('dom_id', $id)));
        }
        return $domain;
    }

    /**
     * insert a new domain options in the database
     *
     * @param    object  &$domain    reference to the {@link MultisiteDomainItem}
     */

    function insertDomainOptions($options, &$domain)
    {
        $count = count($options);
        $dom_id = $domain->getVar('dom_id');
        for ($i = 0; $i < $count; $i++) {
            $options[$i]->setVar('dom_id', $dom_id);
            if (!$this->_oHandler->insert($options[$i])) {
                foreach($options[$i]->getErrors() as $msg){
                    $domain->setErrors($msg);
                }
            }
        }
	}
	
    /**
     * insert a new domain in the database
     *
     * @param    object  &$domain    reference to the {@link MultisiteDomainItem}
     */
    function insertDomain(&$domain)
    {
        if (!$this->_cHandler->insert($domain)) {
            return false;
        }
        $options =& $domain->getConfOptions();
		@$this->insertDomainOptions($options, $domain);
        if (!empty($this->_cacheddomains[$domain->getVar('dom_modid')][$domain->getVar('dom_catid')])) {
            unset ($this->_cacheddomains[$domain->getVar('dom_modid')][$domain->getVar('dom_catid')]);
        }
        return true;
    }

    /**
     * Delete a domain from the database
     *
     * @param    object  &$domain    reference to a {@link MultisiteDomainItem}
     */
    function deleteDomain(&$domain)
    {
        if (!$this->_cHandler->delete($domain)) {
            return false;
        }
        $options =& $domain->getConfOptions();
        $count = count($options);
        if ($count == 0) {
            $options = $this->getDomainOptions(new Criteria('dom_id', $domain->getVar('dom_id')));
            $count = count($options);
        }
        if (is_array($options) && $count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $this->_oHandler->delete($options[$i]);
            }
        }
        if (!empty($this->_cacheddomains[$domain->getVar('dom_modid')][$domain->getVar('dom_catid')])) {
            unset ($this->_cacheddomains[$domain->getVar('dom_modid')][$domain->getVar('dom_catid')]);
        }
        return true;
    }

    /**
     * get one or more domains
     *
     * @param    object  $criteria       {@link CriteriaElement}
     * @param    bool    $id_as_key      Use the domains' ID as keys?
     * @param    bool    $with_options   get the options now?
     *
     * @return    array   Array of {@link MultisiteDomainItem} objects
     */
    function getDomains($criteria = null, $id_as_key = false, $with_options = false)
    {
        return $this->_cHandler->getObjects($criteria, $id_as_key);
    }

    /**
     * Count some domains
     *
     * @param    object  $criteria   {@link CriteriaElement}
     */
    function getDomainCount($criteria = null)
    {
        return $this->_cHandler->getCount($criteria);
    }

    /**
     * Get domains from a certain category
     *
     * @param    int $category   ID of a category
     * @param    int $module     ID of a module
     *
     * @return    array   array of {@link MultisiteDomain}s
     */
    function &getDomainsByCat($category, $module = 0)
    {
        static $_cacheddomains;
        if (!empty($_cacheddomains[$module][$category])) {
            return $_cacheddomains[$module][$category];
        } else {
            $ret = array();
            $criteria = new CriteriaCompo(new Criteria('dom_modid', intval($module)));
            if (!empty($category)) {
                $criteria->add(new Criteria('dom_catid', intval($category)));
            }
			$criteria->add(new Criteria('dom_pid', intval($this->_domain_id)));
            $domains = $this->getDomains($criteria, true);
            if (is_array($domains)) {
                foreach (array_keys($domains) as $i) {
                    $ret[$domains[$i]->getVar('dom_name')] = $domains[$i]->getConfValueForOutput();
                }
            } else {
				$config_handler = xoops_gethandler('config');
				$ret = array();
				$criteria = new CriteriaCompo(new Criteria('conf_modid', intval($module)));
				if (! empty($category)) {
					$criteria->add(new Criteria('conf_catid', intval($category)));
				}
				$configs = $config_handler->getConfigs($criteria, true);
				if (is_array($configs)) {
					foreach(array_keys($configs) as $i) {
						$ret[$configs[$i]->getVar('conf_name')] = $configs[$i]->getConfValueForOutput();
					}
				}
				$_cacheddomains[$module][$category] = $ret;
				return $_cacheddomains[$module][$category];
			}
            $_cacheddomains[$module][$category] = $ret;
            return $_cacheddomains[$module][$category];
        }
    }

	function set_domain_id($domain)
	{
		if (is_a($domain, "MultisiteDomainitem"))
		{
			$this->_domain = $domain;
			$this->_domain_id = $domain->getVar('dom_id'); 
		}
	}
	
	function get_domain_id()
	{
		return $this->_domain_id; 
	}
	
    /**
     * Get converts a XoopsConfigItem to a MultisiteDomainItem
     *
     * @param    int $category   ID of a category
     * @param    int $module     ID of a module
     *
     * @return    array   array of {@link MultisiteDomain}s
     */
	
	function getConfigItem($config, $withoptions = false, $oninsert= false)
	{
		global $xoopsDB;
		
        $confcat_handler =& xoops_gethandler('configcategory');
        $confcat =& $confcat_handler->get($config->getVar('conf_catid'));	

		if (is_object($confcat))
		{
			$domcat_handler =& xoops_getmodulehandler('domaincategory','multisite');
			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('domcat_name', $confcat->getVar('confcat_name')));
			$domcat = $domcat_handler->getObjects($criteria);
		}
		
		if (is_object($domcat[0]))
		{
			$domain_handler =& xoops_getmodulehandler('domain','multisite');
			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('dom_pid', $this->_domain_id));
			$criteria->add(new Criteria('dom_modid', $config->getVar('conf_modid')));
			$criteria->add(new Criteria('dom_name', $config->getVar('conf_name')));			 
			$criteria->add(new Criteria('dom_catid', $domcat[0]->getVar('domcat_id')));
			if ($domain_handler->getDomainCount($criteria)>0) {
				$domain_obj =$domain_handler->getDomain($criteria);
				if ($withoptions == true) {
         		   $domain_obj->setConfOptions($this->getDomainOptions(new Criteria('dom_id', $domain_obj->getVar('dom_id'))));
        		}
				if ($oninsert==true)
					$domain_obj->setVar('dom_value', $config->getVar('conf_value'));

				$newconfig = new XoopsConfigItem();
				$newconfig->setVar('conf_id', $config->getVar('conf_id'));
				$newconfig->setVar('conf_modid', $domain_obj->getVar('dom_modid'));
				$newconfig->setVar('conf_catid', $domain_obj->getVar('dom_catid'));				
				$newconfig->setVar('conf_name', $domain_obj->getVar('dom_name'));
				$newconfig->setVar('conf_title', $domain_obj->getVar('dom_title'));				
				$newconfig->setVar('conf_value', $domain_obj->getVar('dom_value'));
				$newconfig->setVar('conf_desc', $domain_obj->getVar('dom_desc'));				
				$newconfig->setVar('conf_formtype', $domain_obj->getVar('dom_formtype'));
				$newconfig->setVar('conf_valuetype', $domain_obj->getVar('dom_valuetype'));				
				$newconfig->setVar('conf_order', $domain_obj->getVar('dom_order'));
				if ($withoptions == true) {
					$configOptionHandler = new XoopsConfigOptionHandler($xoopsDB);
         		   	$config->setConfOptions($configOptionHandler->getConfigOptions(new Criteria('conf_id', $config->getVar('conf_id'))));
        		}
				return $newconfig;
			} else {
				if ($withoptions == true) {
					$configOptionHandler = new XoopsConfigOptionHandler($xoopsDB);
         		   	$config->setConfOptions($configOptionHandler->getConfigOptions(new Criteria('conf_id', $config->getVar('conf_id'))));
        		}
				return $config;
			}
		} else {
			if ($withoptions == true) {
				$configOptionHandler = new XoopsConfigOptionHandler($xoopsDB);
				$config->setConfOptions($configOptionHandler->getConfigOptions(new Criteria('conf_id', $config->getVar('conf_id'))));
			}
			return $config;
		}		
	}


    /**
     * Get converts a XoopsConfigItem to a MultisiteDomainItem
     *
     * @param    int $category   ID of a category
     * @param    int $module     ID of a module
     *
     * @return    array   array of {@link MultisiteDomain}s
     */
	
	function convertConfigItem($config, $withoptions = false, $oninsert= false)
	{
		global $xoopsDB;
        $confcat_handler =& xoops_gethandler('configcategory');
        $confcat =& $confcat_handler->get($config->getVar('conf_catid'));	

		if (is_object($confcat))
		{
			$domcat_handler =& xoops_getmodulehandler('domaincategory','multisite');
			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('domcat_name', $confcat->getVar('confcat_name')));
			$domcat = $domcat_handler->getObjects($criteria);
		}
		
		if (is_object($domcat[0]))
		{
			$domain_handler =& xoops_getmodulehandler('domain','multisite');
			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('dom_pid', $this->_domain_id));
			$criteria->add(new Criteria('dom_modid', $config->getVar('conf_modid')));
			$criteria->add(new Criteria('dom_name', $config->getVar('conf_name')));			 
			$criteria->add(new Criteria('dom_catid', $domcat[0]->getVar('domcat_id')));
			if ($domain_handler->getDomainCount($criteria)>0) {
				$domain_obj =$domain_handler->getDomain($criteria);
				if ($withoptions == true) {
         		   $domain_obj[0]->setConfOptions($this->getDomainOptions(new Criteria('dom_id', $domain_obj[0]->getVar('dom_id'))));
        		}
				if ($oninsert==true)
					$domain_obj[0]->setVar('dom_value', $config->getVar('conf_value'));
				return $domain_obj[0];
			} else {
				$domain = new MultisiteDomainitem();
				$domain->setVar('dom_modid', $config->getVar('conf_modid'));
				$domain->setVar('dom_catid', $config->getVar('conf_catid'));				
				$domain->setVar('dom_name', $config->getVar('conf_name'));
				$domain->setVar('dom_title', $config->getVar('conf_title'));				
				$domain->setVar('dom_value', $config->getVar('conf_value'));
				$domain->setVar('dom_desc', $config->getVar('conf_desc'));				
				$domain->setVar('dom_formtype', $config->getVar('conf_formtype'));
				$domain->setVar('dom_valuetype', $config->getVar('conf_valuetype'));				
				$domain->setVar('dom_order', $config->getVar('conf_order'));
				if ($withoptions == true) {
					$configOptionHandler = new XoopsConfigOptionHandler($xoopsDB);
         		   	$domain->setConfOptions($configOptionHandler->getConfigOptions(new Criteria('conf_id', $config->getVar('conf_id'))));
        		}
				$domain->setVar('dom_pid', $this->_domain_id);				

				return $domain;
			}
		} else {
			$domain = new MultisiteDomainitem();
			$domain->setVar('dom_modid', $config->getVar('conf_modid'));
			$domain->setVar('dom_catid', $config->getVar('conf_catid'));				
			$domain->setVar('dom_name', $config->getVar('conf_name'));
			$domain->setVar('dom_title', $config->getVar('conf_title'));				
			$domain->setVar('dom_value', $config->getVar('conf_value'));
			$domain->setVar('dom_desc', $config->getVar('conf_desc'));				
			$domain->setVar('dom_formtype', $config->getVar('conf_formtype'));
			$domain->setVar('dom_valuetype', $config->getVar('conf_valuetype'));				
			$domain->setVar('dom_order', $config->getVar('conf_order'));
			if ($withoptions == true) {
				$configOptionHandler = new XoopsConfigOptionHandler($xoopsDB);
				$domain->setConfOptions($configOptionHandler->getConfigOptions(new Criteria('conf_id', $config->getVar('conf_id'))));
			}
			$domain->setVar('dom_pid', $this->_domain_id);				

			return $domain;
		}
	}

    /**
     * Get domains from a certain category
     *
     * @param    int $category   ID of a category
     * @param    int $module     ID of a module
     *
     * @return    array   array of {@link MultisiteDomain}s
     */
    function &getConfigByDomainCat($category, $module=0)
    {


        $confcat_handler =& xoops_gethandler('configcategory');
        $confcat =& $confcat_handler->get($category);

		if (is_object($confcat))
		{
			$domcat_handler =& xoops_getmodulehandler('domaincategory','multisite');
			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('domcat_name', $confcat->getVar('confcat_name')));
			$domcat = $domcat_handler->getObjects($criteria);
		}
		
		if (is_object($domcat[0]))
		{
			$domain_handler =& xoops_getmodulehandler('domain','multisite');
			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('dom_pid', $this->_domain_id));
			$criteria->add(new Criteria('dom_modid', $module));
			$criteria->add(new Criteria('dom_catid', $domcat[0]->getVar('domcat_id')));
			if ($domain_handler->getDomainCount($criteria)>0) {
				if (!empty($domcat[0])&&is_object($domcat[0]))
				{
					$category = intval($domcat[0]->getVar('domcat_id'));
					
					static $_cacheddomains;
					if (!empty($_cacheddomains[$module][$category])) {
						return $_cacheddomains[$module][$category];
					} else {
						$ret = array();
						$criteria = new CriteriaCompo(new Criteria('dom_modid', intval($module)));
						$criteria->add(new Criteria('dom_pid', intval($this->_domain_id)));
						if (!empty($category)) {
							$criteria->add(new Criteria('dom_catid', intval($category)));
						}
						$domains = $this->getDomains($criteria, true);
						if (is_array($domains)) {
							foreach (array_keys($domains) as $i) {
								$ret[$domains[$i]->getVar('dom_name')] = $domains[$i]->getConfValueForOutput();
							}
						}
						$_cacheddomains[$module][$category] = $ret;
						return $_cacheddomains[$module][$category];
					}
				} else {
					$config_handler = xoops_gethandler('config');
					$ret = array();
					$criteria = new CriteriaCompo(new Criteria('conf_modid', intval($module)));
					if (! empty($category)) {
						$criteria->add(new Criteria('conf_catid', intval($category)));
					}
					$configs = $config_handler->getConfigs($criteria, true);
					if (is_array($configs)) {
						foreach(array_keys($configs) as $i) {
							$ret[$configs[$i]->getVar('conf_name')] = $configs[$i]->getConfValueForOutput();
						}
					}
					$_cacheddomains[$module][$category] = $ret;
					return $_cacheddomains[$module][$category];

				}
			} else {
				$config_handler = xoops_gethandler('config');
				$ret = array();
				$criteria = new CriteriaCompo(new Criteria('conf_modid', intval($module)));
				if (! empty($category)) {
					$criteria->add(new Criteria('conf_catid', intval($category)));
				}
				$configs = $config_handler->getConfigs($criteria, true);
				if (is_array($configs)) {
					foreach(array_keys($configs) as $i) {
						$ret[$configs[$i]->getVar('conf_name')] = $configs[$i]->getConfValueForOutput();
					}
				}
				$_cacheddomains[$module][$category] = $ret;
				return $_cacheddomains[$module][$category];

			}
		}
    }
    /**
     * Make a new {@link MultisiteDomainOption}
     *
     * @return    object  {@link MultisiteDomainOption}
     */
    function &createDomainOption() {
        $inst =& $this->_oHandler->create();
        return $inst;
    }

    /**
     * Get a {@link MultisiteDomainOption}
     *
     * @param    int $id ID of the domain option
     *
     * @return    object  {@link MultisiteDomainOption}
     */
    function &getDomainOption($id) {
        $inst =& $this->_oHandler->get($id);
        return $inst;
    }

    /**
     * Get one or more {@link MultisiteDomainOption}s
     *
     * @param    object  $criteria   {@link CriteriaElement}
     * @param    bool    $id_as_key  Use IDs as keys in the array?
     *
     * @return    array   Array of {@link MultisiteDomainOption}s
     */
    function getDomainOptions($criteria = null, $id_as_key = false)
    {
        return $this->_oHandler->getObjects($criteria, $id_as_key);
    }

    /**
     * Count some {@link MultisiteDomainOption}s
     *
     * @param    object  $criteria   {@link CriteriaElement}
     *
     * @return    int     Count of {@link MultisiteDomainOption}s matching $criteria
     */
    function getDomainOptionsCount($criteria = null)
    {
        return $this->_oHandler->getCount($criteria);
    }

    /**
     * Get a list of domains
     *
     * @param    int $dom_modid ID of the modules
     * @param    int $dom_catid ID of the category
     *
     * @return    array   Associative array of name=>value pairs.
     */
    function getDomainList($dom_modid, $dom_catid = 0)
    {
        if (!empty($this->_cacheddomains[$dom_modid][$dom_catid])) {
            return $this->_cacheddomains[$dom_modid][$dom_catid];
        } else {
            $criteria = new CriteriaCompo(new Criteria('dom_modid', $dom_modid));
            if (empty($dom_catid)) {
                $criteria->add(new Criteria('dom_catid', $dom_catid));
            }
            $domains = $this->_cHandler->getObjects($criteria);
            $confcount = count($domains);
            $ret = array();
            for ($i = 0; $i < $confcount; $i++) {
                $ret[$domains[$i]->getVar('dom_name')] = $domains[$i]->getConfValueForOutput();
            }
            $this->_cacheddomains[$dom_modid][$dom_catid] =& $ret;
            return $ret;
        }
    }
}
?>
