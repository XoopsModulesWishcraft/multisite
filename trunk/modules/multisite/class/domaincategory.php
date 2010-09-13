<?php
// $Id: domaincategory.php 1217 2008-01-01 17:04:41Z phppp $
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
 *
 *
 * @package     kernel
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */


/**
 * A category of domains
 *
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 *
 * @package     kernel
 */
class MultisiteDomaincategory extends XoopsObject
{
    /**
     * Constructor
     *
     */
    function MultisiteDomaincategory()
    {
        $this->XoopsObject();
        $this->initVar('domcat_id', XOBJ_DTYPE_INT, null);
        $this->initVar('domcat_name', XOBJ_DTYPE_OTHER, null);
        $this->initVar('domcat_order', XOBJ_DTYPE_INT, 0);
    }
}


/**
 * XOOPS domainuration category handler class.
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS domainuration category class objects.
 *
 * @author  Kazumi Ono <onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 *
 * @package     kernel
 * @subpackage  domain
 */
class MultisiteDomaincategoryHandler extends XoopsObjectHandler
{

    /**
     * Create a new category
     *
     * @param	bool    $isNew  Flag the new object as "new"?
     *
     * @return	object  New {@link MultisiteDomaincategory}
     */
    function &create($isNew = true)
    {
        $domcat = new MultisiteDomaincategory();
        if ($isNew) {
            $domcat->setNew();
        }
        return $domcat;
    }

    /**
     * Retrieve a {@link MultisiteDomaincategory}
     *
     * @param	int $id ID
     *
     * @return	object  {@link MultisiteDomaincategory}, FALSE on fail
     */
    function &get($id) {
        $domcat = false;
    	$id = intval($id);
        if ($id > 0) {
            $sql = 'SELECT * FROM '.$this->db->prefix('domaincategory').' WHERE domcat_id='.$id;
            if (!$result = $this->db->query($sql)) {
                return $domcat;
            }
            $numrows = $this->db->getRowsNum($result);
            if ($numrows == 1) {
                $domcat = new MultisiteDomaincategory();
                $domcat->assignVars($this->db->fetchArray($result), false);
            }
        }
        return $domcat;
    }
	
	
    /**
     * Retrieve a {@link MultisiteDomaincategory}
     *
     * @param	int $id ID
     *
     * @return	object  {@link MultisiteDomaincategory}, FALSE on fail
     */
    function &getByName($name) {
        $domcat = false;
        if (!empty($name)) {
            $sql = 'SELECT * FROM '.$this->db->prefix('domaincategory').' WHERE domcat_name=\''.$name.'\'';
            if (!$result = $this->db->query($sql)) {
                return $domcat;
            }
            $numrows = $this->db->getRowsNum($result);
            if ($numrows == 1) {
                $domcat = new MultisiteDomaincategory();
                $domcat->assignVars($this->db->fetchArray($result), false);
            }
        }
        return $domcat;
    }

    /**
     * Store a {@link MultisiteDomaincategory}
     *
     * @param	object   &$domcat  {@link MultisiteDomaincategory}
     *
     * @return	bool    TRUE on success
     */
    function insert(&$domcat)
    {
        /**
        * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
        */
	
        if (!is_a($domcat, 'MultisiteDomaincategory')) {
            return false;
        }
        if (!$domcat->isDirty()) {
            return true;
        }
        if (!$domcat->cleanVars()) {
            return false;
        }
        foreach ($domcat->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if ($domcat->isNew()) {
            $domcat_id = $this->db->genId('domaincategory_domcat_id_seq');
            $sql = sprintf("INSERT INTO %s (domcat_id, domcat_name, domcat_order) VALUES (%u, %s, %u)", $this->db->prefix('domaincategory'), $domcat_id, $this->db->quoteString(addslashes($domcat_name)), $domcat_order);
        } else {
            $sql = sprintf("UPDATE %s SET domcat_name = %s, domcat_order = %u WHERE domcat_id = %u", $this->db->prefix('domaincategory'), $this->db->quoteString(addslashes($domcat_name)), $domcat_order, $domcat_id);
        }

        if (!$result = $this->db->queryF($sql)) {
            return false;
        }
        if (empty($domcat_id)) {
            $domcat_id = $this->db->getInsertId();
        }
        $domcat->assignVar('domcat_id', $domcat_id);
        return $domcat_id;
    }

    /**
     * Delelete a {@link MultisiteDomaincategory}
     *
     * @param	object  &$domcat   {@link MultisiteDomaincategory}
     *
     * @return	bool    TRUE on success
     */
    function delete(&$domcat)
    {
        /**
        * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
        */
        if (!is_a($domcat, 'MultisiteDomaincategory')) {
            return false;
        }

        $sql = sprintf("DELETE FROM %s WHERE domcat_id = %u", $this->db->prefix('domaincategory'), $domaincategory->getVar('domcat_id'));
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        return true;
    }

    /**
     * Get some {@link MultisiteDomaincategory}s
     *
     * @param	object  $criteria   {@link CriteriaElement}
     * @param	bool    $id_as_key  Use the IDs as keys to the array?
     *
     * @return	array   Array of {@link MultisiteDomaincategory}s
     */
    function getObjects($criteria = null, $id_as_key = false)
    {
        $ret = array();
        $limit = $start = 0;
        $sql = 'SELECT * FROM '.$this->db->prefix('domaincategory');
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' '.$criteria->renderWhere();
            $sort = !in_array($criteria->getSort(), array('domcat_id', 'domcat_name', 'domcat_order')) ? 'domcat_order' : $criteria->getSort();
            $sql .= ' ORDER BY '.$sort.' '.$criteria->getOrder();
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
		 if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $domcat = new MultisiteDomaincategory();
            $domcat->assignVars($myrow, false);
            if (!$id_as_key) {
                $ret[] =& $domcat;
            } else {
                $ret[$myrow['domcat_id']] =& $domcat;
            }
            unset($domcat);
        }
        return $ret;
    }
	
    function getCount($criteria = null, $id_as_key = false)
    {
        $ret = array();
        $limit = $start = 0;
        $sql = 'SELECT count(*) as rc FROM '.$this->db->prefix('domaincategory');
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' '.$criteria->renderWhere();
            $sort = !in_array($criteria->getSort(), array('domcat_id', 'domcat_name', 'domcat_order')) ? 'domcat_order' : $criteria->getSort();
            $sql .= ' ORDER BY '.$sort.' '.$criteria->getOrder();
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return false;;
        }
		$ret = $this->db->fetchArray($result);
        return $ret['rc'];
    }
}
?>