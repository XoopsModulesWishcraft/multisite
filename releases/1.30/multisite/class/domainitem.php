<?php
// $Id: Domainitem.php 1947 2008-08-08 10:16:50Z phppp $
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
 * @package     kernel
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */

/**#@+
 * Config type
 */
define('XOOPS_DOMAIN', 1);

/**#@-*/

/**
 *
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class MultisiteDomainitem extends XoopsObject
{

    /**
     * Config options
     *
     * @var	array
     * @access	private
     */
    var $_confOptions = array();

    /**
     * Constructor
     */
    function MultisiteDomainitem()
    {
        $this->initVar('dom_id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('dom_pid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('dom_modid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('dom_catid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('dom_name', XOBJ_DTYPE_OTHER);
        $this->initVar('dom_title', XOBJ_DTYPE_TXTBOX);
        $this->initVar('dom_value', XOBJ_DTYPE_OTHER);
        $this->initVar('dom_desc', XOBJ_DTYPE_OTHER);
        $this->initVar('dom_formtype', XOBJ_DTYPE_OTHER);
        $this->initVar('dom_valuetype', XOBJ_DTYPE_OTHER);
        $this->initVar('dom_order', XOBJ_DTYPE_INT);
    }

    /**
     * Get a domain value in a format ready for output
     *
     * @return	string
     */
    function getConfValueForOutput()
    {
        switch ($this->getVar('dom_valuetype')) {
        case 'int':
            return intval($this->getVar('dom_value', 'N'));
            break;
        case 'array':
            $value = @unserialize( $this->getVar('dom_value', 'N') );
            return $value ? $value : array();
        case 'float':
            $value = $this->getVar('dom_value', 'N');
            return (float)$value;
            break;
        case 'textarea':
            return $this->getVar('dom_value');
        default:
            return $this->getVar('dom_value', 'N');
            break;
        }
    }

    /**
     * Set a domain value
     *
     * @param	mixed   &$value Value
     * @param	bool    $force_slash
     */
    function setConfValueForInput(&$value, $force_slash = false)
    {
        switch($this->getVar('dom_valuetype')) {
        case 'array':
            if (!is_array($value)) {
                $value = explode('|', trim($value));
            }
            $this->setVar('dom_value', serialize($value), $force_slash);
            break;
        case 'text':
            $this->setVar('dom_value', trim($value), $force_slash);
            break;
        default:
            $this->setVar('dom_value', $value, $force_slash);
            break;
        }
    }

    /**
     * Assign one or more {@link MultisiteDomainitemOption}s
     *
     * @param	mixed   $option either a {@link MultisiteDomainitemOption} object or an array of them
     */
    function setConfOptions($option)
    {
        if (is_array($option)) {
            $count = count($option);
            for ($i = 0; $i < $count; $i++) {
                $this->setConfOptions($option[$i]);
            }
        } else {
            if(is_object($option)) {
                $this->_confOptions[] =& $option;
            }
        }
    }

    /**
     * Get the {@link MultisiteDomainitemOption}s of this Config
     *
     * @return	array   array of {@link MultisiteDomainitemOption}
     */
    function &getConfOptions()
    {
        return $this->_confOptions;
    }
}


/**
* XOOPS domainuration handler class.
*
* This class is responsible for providing data access mechanisms to the data source
* of XOOPS domainuration class objects.
*
* @author       Kazumi Ono <onokazu@xoops.org>
* @copyright    copyright (c) 2000-2003 XOOPS.org
*/
class MultisiteDomainitemHandler extends XoopsObjectHandler
{

    /**
     * Create a new {@link MultisiteDomainitem}
     *
     * @see     MultisiteDomainitem
     * @param	bool    $isNew  Flag the domain as "new"?
     * @return	object  reference to the new domain
     */
    function &create($isNew = true)
    {
        $domain = new MultisiteDomainitem();
        if ($isNew) {
            $domain->setNew();
        }
        return $domain;
    }

    /**
     * Load a domain from the database
     *
     * @param	int $id ID of the domain
     * @return	object  reference to the domain, FALSE on fail
     */
    function &get($id)
    {
        $domain = false;
    	$id = intval($id);
        if ($id > 0) {
            $sql = 'SELECT * FROM '.$this->db->prefix('domain').' WHERE dom_id='.$id;
            if (!$result = $this->db->query($sql)) {
                return $domain;
            }
            $numrows = $this->db->getRowsNum($result);
            if ($numrows == 1) {
                $myrow = $this->db->fetchArray($result);
                $domain = new MultisiteDomainitem();
                $domain->assignVars($myrow);
            }
        }
        return $domain;
    }

    /**
     * Write a domain to the database
     *
     * @param	object  &$domain    {@link MultisiteDomainitem} object
     * @return  mixed   FALSE on fail.
     */
    function insert(&$domain)
    {
        /**
        * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
        */
        if (!is_a($domain, 'MultisiteDomainitem')) {
            return false;
        }
        if (!$domain->isDirty()) {
            return true;
        }
        if (!$domain->cleanVars()) {
            return false;
        }
        foreach ($domain->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if ($domain->isNew()) {
            $dom_id = $this->db->genId('domain_dom_id_seq');
            $sql = sprintf("INSERT INTO %s (dom_id, dom_pid, dom_modid, dom_catid, dom_name, dom_title, dom_value, dom_desc, dom_formtype, dom_valuetype, dom_order) VALUES (%u, %u, %u, %u, %s, %s, %s, %s, %s, %s, %u)", $this->db->prefix('domain'), $dom_id, $dom_pid, $dom_modid, $dom_catid, $this->db->quoteString($dom_name), $this->db->quoteString($dom_title), $this->db->quoteString($dom_value), $this->db->quoteString($dom_desc), $this->db->quoteString($dom_formtype), $this->db->quoteString($dom_valuetype), $dom_order);
        } else {
            $sql = sprintf("UPDATE %s SET dom_pid = %u, dom_modid = %u, dom_catid = %u, dom_name = %s, dom_title = %s, dom_value = %s, dom_desc = %s, dom_formtype = %s, dom_valuetype = %s, dom_order = %u WHERE dom_id = %u", $this->db->prefix('domain'), $dom_pid, $dom_modid, $dom_catid, $this->db->quoteString($dom_name), $this->db->quoteString($dom_title), $this->db->quoteString($dom_value), $this->db->quoteString($dom_desc), $this->db->quoteString($dom_formtype), $this->db->quoteString($dom_valuetype), $dom_order, $dom_id);
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        if (empty($dom_id)) {
            $dom_id = $this->db->getInsertId();
        }
        $domain->assignVar('dom_id', $dom_id);
        return true;
    }

    /**
     * Delete a domain from the database
     *
     * @param	object  &$domain    Config to delete
     * @return	bool    Successful?
     */
    function delete(&$domain)
    {
        /**
        * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
        */
        if (!is_a($domain, 'MultisiteDomainitem')) {
            return false;
        }
        $sql = sprintf("DELETE FROM %s WHERE dom_id = %u", $this->db->prefix('domain'), $domain->getVar('dom_id'));
        if (!$result = $this->db->queryF($sql)) {
            return false;
        }
        return true;
    }

    /**
     * Get domains from the database
     *
     * @param	object  $criteria   {@link CriteriaElement}
     * @param	bool    $id_as_key  return the domain's id as key?
     * @return	array   Array of {@link MultisiteDomainitem} objects
     */
    function getObjects($criteria = null, $id_as_key = false)
    {
        $ret = array();
        $limit = $start = 0;
        $sql = 'SELECT * FROM '.$this->db->prefix('domain');
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' '.$criteria->renderWhere();
            $sql .= ' ORDER BY dom_order ASC';
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return false;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $domain = new MultisiteDomainitem();
            $domain->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] =& $domain;
            } else {
                $ret[$myrow['dom_id']] =& $domain;
            }
            unset($domain);
        }
        return $ret;
    }

    /**
     * Count domains
     *
     * @param	object  $criteria   {@link CriteriaElement}
     * @return	int     Count of domains matching $criteria
     */
    function getCount($criteria = null)
    {
        $ret = array();
        $limit = $start = 0;
        $sql = 'SELECT count(*) FROM '.$this->db->prefix('domain');
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' '.$criteria->renderWhere();
        }
        $result =& $this->db->query($sql);
		if (!$result) {
            return false;
        }
        list($count) = $this->db->fetchRow($result);
        return $count;
    }
}

?>