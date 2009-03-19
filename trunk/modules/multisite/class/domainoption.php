<?php
// $Id: Domainoption.php 1217 2008-01-01 17:04:41Z phppp $
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
 * A Config-Option
 *
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 *
 * @package     kernel
 */
class MultisiteDomainoption extends XoopsObject
{
    /**
     * Constructor
     */
    function MultisiteDomainoption()
    {
        $this->XoopsObject();
        $this->initVar('domop_id', XOBJ_DTYPE_INT, null);
        $this->initVar('domop_name', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('domop_value', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('dom_id', XOBJ_DTYPE_INT, 0);
    }
}

/**
 * XOOPS domainuration option handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS domainuration option class objects.
 *
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 * @author  Kazumi Ono <onokazu@xoops.org>
 *
 * @package     kernel
 * @subpackage  domain
*/
class MultisiteDomainoptionHandler extends XoopsObjectHandler
{

    /**
     * Create a new option
     *
     * @param	bool    $isNew  Flag the option as "new"?
     *
     * @return	object  {@link MultisiteDomainoption}
     */
    function &create($isNew = true)
    {
        $domoption = new MultisiteDomainoption();
        if ($isNew) {
            $domoption->setNew();
        }
        return $domoption;
    }

    /**
     * Get an option from the database
     *
     * @param	int $id ID of the option
     *
     * @return	object  reference to the {@link MultisiteDomainoption}, FALSE on fail
     */
    function &get($id)
    {
        $domoption = false;
    	$id = intval($id);
        if ($id > 0) {
            $sql = 'SELECT * FROM '.$this->db->prefix('domainoption').' WHERE domop_id='.$id;
            if (!$result = $this->db->query($sql)) {
                return $domoption;
            }
            $numrows = $this->db->getRowsNum($result);
            if ($numrows == 1) {
                $domoption = new MultisiteDomainoption();
                $domoption->assignVars($this->db->fetchArray($result));
            }
        }
        return $domoption;
    }

    /**
     * Insert a new option in the database
     *
     * @param	object  &$domoption    reference to a {@link MultisiteDomainoption}
     * @return	bool    TRUE if successfull.
     */
    function insert(&$domoption)
    {
        /**
        * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
        */
        if (!is_a($domoption, 'MultisiteDomainoption')) {
            return false;
        }
        if (!$domoption->isDirty()) {
            return true;
        }
        if (!$domoption->cleanVars()) {
            return false;
        }
        foreach ($domoption->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if ($domoption->isNew()) {
            $domop_id = $this->db->genId('Domainoption_domop_id_seq');
            $sql = sprintf("INSERT INTO %s (domop_id, domop_name, domop_value, dom_id) VALUES (%u, %s, %s, %u)", $this->db->prefix('domainoption'), $domop_id, $this->db->quoteString($domop_name), $this->db->quoteString($domop_value), $dom_id);
        } else {
            $sql = sprintf("UPDATE %s SET domop_name = %s, domop_value = %s WHERE domop_id = %u", $this->db->prefix('domainoption'), $this->db->quoteString($domop_name), $this->db->quoteString($domop_value), $domop_id);
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        if (empty($domop_id)) {
            $domop_id = $this->db->getInsertId();
        }
        $domoption->assignVar('domop_id', $domop_id);
        return $domop_id;
    }

    /**
     * Delete an option
     *
     * @param	object  &$domoption    reference to a {@link MultisiteDomainoption}
     * @return	bool    TRUE if successful
     */
    function delete(&$domoption)
    {
        /**
        * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
        */
        if (!is_a($domoption, 'MultisiteDomainoption')) {
            return false;
        }
        $sql = sprintf("DELETE FROM %s WHERE domop_id = %u", $this->db->prefix('domainoption'), $domoption->getVar('domop_id'));
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        return true;
    }

    /**
     * Get some {@link MultisiteDomainoption}s
     *
     * @param	object  $criteria   {@link CriteriaElement}
     * @param	bool    $id_as_key  Use the IDs as array-keys?
     *
     * @return	array   Array of {@link MultisiteDomainoption}s
     */
    function getObjects($criteria = null, $id_as_key = false)
    {
        $ret = array();
        $limit = $start = 0;
        $sql = 'SELECT * FROM '.$this->db->prefix('domainoption');
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' '.$criteria->renderWhere().' ORDER BY domop_id '.$criteria->getOrder();
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $domoption = new MultisiteDomainoption();
            $domoption->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] =& $domoption;
            } else {
                $ret[$myrow['domop_id']] =& $domoption;
            }
            unset($domoption);
        }
        return $ret;
    }
}
?>