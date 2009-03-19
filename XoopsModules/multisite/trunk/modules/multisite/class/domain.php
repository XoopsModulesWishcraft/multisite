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
     * Constructor
     *
     * @param    object  &$db    reference to database object
     */
    function MultisiteDomainHandler(&$db)
    {
        $this->_cHandler = new MultisiteDomainitemHandler($db);
        $this->_oHandler = new MultisiteDomainoptionHandler($db);
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
            $domain->setConfOptions($this->getdomainOptions(new Criteria('dom_id', $id)));
        }
        return $domain;
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
            $options = $this->getdomainOptions(new Criteria('dom_id', $domain->getVar('dom_id')));
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
            $domains = $this->getdomains($criteria, true);
            if (is_array($domains)) {
                foreach (array_keys($domains) as $i) {
                    $ret[$domains[$i]->getVar('dom_name')] = $domains[$i]->getConfValueForOutput();
                }
            }
            $_cacheddomains[$module][$category] = $ret;
            return $_cacheddomains[$module][$category];
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
