<?php
// $Id: module.php 2000 2008-08-30 11:03:05Z phppp $
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
 * A Module
 *
 * @package		kernel
 *
 * @author		Kazumi Ono 	<onokazu@xoops.org>
 * @copyright	(c) 2000-2003 The Xoops Project - www.xoops.org
 */
class MultisiteModule extends XoopsModule
{
    /**
     * @var string
     */
    var $modinfo;
    /**
     * @var string
     */
    var $adminmenu;

    /**
     * Constructor
     */
    function MultisiteModule()
    {
        $this->XoopsObject();
        $this->initVar('mid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, true, 150);
        $this->initVar('version', XOBJ_DTYPE_INT, 100, false);
        $this->initVar('last_update', XOBJ_DTYPE_INT, null, false);
        $this->initVar('weight', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('isactive', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('dirname', XOBJ_DTYPE_OTHER, null, true);
        $this->initVar('hasmain', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('hasadmin', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('hassearch', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('hasconfig', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('hascomments', XOBJ_DTYPE_INT, 0, false);
		// RMV-NOTIFY
		$this->initVar('hasnotification', XOBJ_DTYPE_INT, 0, false);
		// Feeds
        $this->initVar('hasrss', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('hasatom', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('hassitemap', XOBJ_DTYPE_INT, 0, false);
		//Domains
		$this->initVar('domains', XOBJ_DTYPE_OTHER, '|all|', false);
    }

     /**
     * Search contents within a module
     *
     * @param   integer $items
     * @param   integer $userid
	 * @param   string  $sort (ASC, DESC)
     * @return  mixed   Search result.
     **/
    function atom($items, $userid, $sort)
    {
        if ($this->getVar('hasatom') != 1) {
            return false;
        }
        $atom =& $this->getInfo('atom');
        if ($this->getVar('hasatom') != 1 || ( !isset($atom['atom_func']) && !is_array($atom['atom_func']) ) || ( !isset($atom['atom_file']) && !is_array($atom['atom_file']) )) {
            return false;
        }
		$xoops_atom_output = array();
 		foreach($atom['atom_func'] as $key => $func) {
			if (file_exists(XOOPS_ROOT_PATH."/modules/".$this->getVar('dirname').'/'.$atom['atom_file'][$key])) {
				include_once XOOPS_ROOT_PATH.'/modules/'.$this->getVar('dirname').'/'.$atom['atom_file'][$key];
			} else {
				return false;
			}
			if (function_exists($func)) {				
				$xoops_atom_output = array_merge($xoops_atom_output,$func($items, $userid, $sort));
			}
		}
		if (count($xoops_atom_output)<1)
	        return false;
		else
			return $xoops_atom_output;
    }

    /**
     * Search contents within a module
     *
     * @param   integer $items
     * @param   integer $userid
	 * @param   string  $sort (ASC, DESC)
     * @return  mixed   Search result.
     **/
    function rss($items, $userid, $sort)
    {
        if ($this->getVar('hasrss') != 1) {
            return false;
        }
        $rss =& $this->getInfo('rss');
        if ($this->getVar('hasrss') != 1 || ( !isset($rss['rss_func']) && !is_array($rss['rss_func']) ) || ( !isset($rss['rss_file']) && !is_array($rss['rss_file']) )) {
            return false;
        }
		$xoops_rss_output = array();
 		foreach($rss['rss_func'] as $key => $func) {
			if (file_exists(XOOPS_ROOT_PATH."/modules/".$this->getVar('dirname').'/'.$rss['rss_file'][$key])) {
				include_once XOOPS_ROOT_PATH.'/modules/'.$this->getVar('dirname').'/'.$rss['rss_file'][$key];
			} else {
				return false;
			}
			if (function_exists($func)) {
				$xoops_rss_output = array_merge($xoops_rss_output,$func($items, $userid, $sort));
			}
		}
		if (count($xoops_rss_output)<1)
	        return false;
		else
			return $xoops_rss_output;
    }

    /**
     * Search contents within a module
     *
     * @param   integer $items
     * @param   integer $userid
	 * @param   string  $sort (ASC, DESC)
	 * @param   string  $agent
     * @return  mixed   Search result.
     **/
    function sitemap($items, $userid, $sort, $agent)
    {
        if ($this->getVar('hassitemap') != 1) {
            return false;
        }
        $sitemap =& $this->getInfo('sitemap');
        if ($this->getVar('hassitemap') != 1 || ( !isset($sitemap['sitemap_func']) && !is_array($sitemap['sitemap_func']) ) || ( !isset($sitemap['sitemap_file']) && !is_array($sitemap['sitemap_file']) )) {
            return false;
        }
		$xoops_sitemap_output = array();
 		foreach($sitemap['sitemap_func'] as $key => $func) {
			
			if (file_exists(XOOPS_ROOT_PATH."/modules/".$this->getVar('dirname').'/'.$sitemap['sitemap_file'][$key])) {
				include_once XOOPS_ROOT_PATH.'/modules/'.$this->getVar('dirname').'/'.$sitemap['sitemap_file'][$key];
				if (function_exists($func)) {
					$xoops_sitemap_output = array_merge($xoops_sitemap_output,$func($items, $userid, $sort, $agent));
				}
			}
		}
		if (count($xoops_sitemap_output)<2)
	        return false;
		else
			return $xoops_sitemap_output;
    }

}


/**
 * XOOPS module handler class.
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS module class objects.
 *
 * @package		kernel
 *
 * @author		Kazumi Ono 	<onokazu@xoops.org>
 * @copyright	(c) 2000-2003 The Xoops Project - www.xoops.org
 */
class MultisiteModuleHandler extends XoopsObjectHandler
{
	/**
	 * holds an array of cached module references, indexed by module id
	 *
	 * @var    array
	 * @access private
	 */
	var $_cachedModule_mid = array();

	/**
	 * holds an array of cached module references, indexed by module dirname
	 *
	 * @var    array
	 * @access private
	 */
	var $_cachedModule_dirname = array();

    /**
     * Create a new {@link XoopsModule} object
     *
     * @param   boolean     $isNew   Flag the new object as "new"
     * @return  object
     **/
    function &create($isNew = true)
    {
        $module = new MultisiteModule();
        if ($isNew) {
            $module->setNew();
        }
        return $module;
    }

    /**
     * Load a module from the database
     *
     * @param	int     $id     ID of the module
     *
     * @return	object  FALSE on fail
     */
    function &get($id)
    {
        static $_cachedModule_dirname;
        static $_cachedModule_mid;
        $id = intval($id);
		$module = false;
        if ($id > 0) {
			if (!empty($_cachedModule_mid[$id])) {
				return $_cachedModule_mid[$id];
			} else {
  	        	$sql = 'SELECT * FROM '.$this->db->prefix('modules').' WHERE mid = '.$id;
  	        	if (!$result = $this->db->query($sql)) {
  	            	return $module;
  	        	}
  	        	$numrows = $this->db->getRowsNum($result);
  	        	if ($numrows == 1) {
  	            	$module = new MultisiteModule();
  	            	$myrow = $this->db->fetchArray($result);
  	            	$module->assignVars($myrow);
					$_cachedModule_mid[$id] =& $module;
					$_cachedModule_dirname[$module->getVar('dirname')] =& $module;
  	            	return $module;
  	        	}
        	}
		}
        return $module;
    }

    /**
     * Load a module by its dirname
     *
     * @param	string  $dirname
     *
     * @return	object  FALSE on fail
     */
    function &getByDirname($dirname)
    {
        static $_cachedModule_mid;
        static $_cachedModule_dirname;
		if (!empty($_cachedModule_dirname[$dirname])) {
			return $_cachedModule_dirname[$dirname];
		} else {
			$module = false;
        	$sql = "SELECT * FROM ".$this->db->prefix('modules')." WHERE dirname = '".trim($dirname)."'";
        	if (!$result = $this->db->query($sql)) {
            	return $module;
        	}
        	$numrows = $this->db->getRowsNum($result);
        	if ($numrows == 1) {
            	$module = new MultisiteModule();
            	$myrow = $this->db->fetchArray($result);
            	$module->assignVars($myrow);
				$_cachedModule_dirname[$dirname] =& $module;
				$_cachedModule_mid[$module->getVar('mid')] =& $module;
        	}
        	return $module;
		}
    }

    /**
     * Write a module to the database
     *
     * @param   object  &$module reference to a {@link XoopsModule}
     * @return  bool
     **/
    function insert(&$module)
    {
        /**
        * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
        */
        if (!is_a($module, 'xoopsmodule')) {
            return false;
        }
        if (!$module->isDirty()) {
            return true;
        }
        if (!$module->cleanVars()) {
            return false;
        }
        foreach ($module->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if ($module->isNew()) {
            $mid = $this->db->genId('modules_mid_seq');
            $sql = sprintf("INSERT INTO %s (mid, name, version, last_update, weight, isactive, dirname, hasmain, hasadmin, hassearch, hasconfig, hascomments, hasnotification, hasrss, hassitemap, domains) VALUES (%u, %s, %u, %u, %u, %u, %s, %u, %u, %u, %u, %u, %u, %u, %u, %s)", $this->db->prefix('modules'), $mid, $this->db->quoteString($name), $version, time(), $weight, 1, $this->db->quoteString($dirname), $hasmain, $hasadmin, $hassearch, $hasconfig, $hascomments, $hasnotification, $hasrss, $hassitemap, $this->db->quoteString($domains));
        } else {
            $sql = sprintf("UPDATE %s SET name = %s, dirname = %s, version = %u, last_update = %u, weight = %u, isactive = %u, hasmain = %u, hasadmin = %u, hassearch = %u, hasconfig = %u, hascomments = %u, hasnotification = %u, hasrss = %u, hassitemap = %u, domains = %s WHERE mid = %u", $this->db->prefix('modules'), $this->db->quoteString($name), $this->db->quoteString($dirname), $version, time(), $weight, $isactive, $hasmain, $hasadmin, $hassearch, $hasconfig, $hascomments, $hasnotification, $hasrss, $hassitemap, $this->db->quoteString($domains), $mid);
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        if (empty($mid)) {
            $mid = $this->db->getInsertId();
        }
        $module->assignVar('mid', $mid);
		if (!empty($this->_cachedModule_dirname[$dirname])) {
			unset ($this->_cachedModule_dirname[$dirname]);
		}
		if (!empty($this->_cachedModule_mid[$mid])) {
			unset ($this->_cachedModule_mid[$mid]);
		}
        return true;
    }

    /**
     * Delete a module from the database
     *
     * @param   object  &$module
     * @return  bool
     **/
    function delete(&$module)
    {
        /**
        * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
        */
        if (!is_a($module, 'xoopsmodule')) {
            return false;
        }
        $sql = sprintf("DELETE FROM %s WHERE mid = %u", $this->db->prefix('modules'), $module->getVar('mid'));
        if ( !$result = $this->db->query($sql) ) {
            return false;
        }
		// delete admin permissions assigned for this module
		$sql = sprintf("DELETE FROM %s WHERE gperm_name = 'module_admin' AND gperm_itemid = %u", $this->db->prefix('group_permission'), $module->getVar('mid'));
		$this->db->query($sql);
		// delete read permissions assigned for this module
		$sql = sprintf("DELETE FROM %s WHERE gperm_name = 'module_read' AND gperm_itemid = %u", $this->db->prefix('group_permission'), $module->getVar('mid'));
		$this->db->query($sql);

		// delete feeds assigned for this module
		$sql = sprintf("DELETE FROM %s WHERE mid = %u", $this->db->prefix('newfeeds'), $module->getVar('mid'));
		$this->db->query($sql);

        $sql = sprintf("SELECT block_id FROM %s WHERE module_id = %u", $this->db->prefix('block_module_link'), $module->getVar('mid'));
        if ($result = $this->db->query($sql)) {
        	$block_id_arr = array();
            while ($myrow = $this->db->fetchArray($result)) {
				array_push($block_id_arr, $myrow['block_id']);
            }
        }
		// loop through block_id_arr
        if (isset($block_id_arr)) {
    		foreach ($block_id_arr as $i) {
                $sql = sprintf("SELECT block_id FROM %s WHERE module_id != %u AND block_id = %u", $this->db->prefix('block_module_link'), $module->getVar('mid'), $i);
	        	if ($result2 = $this->db->query($sql)) {
                    if (0 < $this->db->getRowsNum($result2)) {
					// this block has other entries, so delete the entry for this module
                        $sql = sprintf("DELETE FROM %s WHERE (module_id = %u) AND (block_id = %u)", $this->db->prefix('block_module_link'), $module->getVar('mid'), $i);
                        $this->db->query($sql);
                    } else {
					// this block doesnt have other entries, so disable the block and let it show on top page only. otherwise, this block will not display anymore on block admin page!
        				$sql = sprintf("UPDATE %s SET visible = 0 WHERE bid = %u", $this->db->prefix('newblocks'), $i);
        				$this->db->query($sql);
        				$sql = sprintf("UPDATE %s SET module_id = -1 WHERE module_id = %u", $this->db->prefix('block_module_link'), $module->getVar('mid'));
        				$this->db->query($sql);
                    }
				}
            }
        }

		if (!empty($this->_cachedModule_dirname[$module->getVar('dirname')])) {
			unset ($this->_cachedModule_dirname[$module->getVar('dirname')]);
		}
		if (!empty($this->_cachedModule_mid[$module->getVar('mid')])) {
			unset ($this->_cachedModule_mid[$module->getVar('mid')]);
		}
        return true;
    }

    /**
     * Load some modules
     *
     * @param   object  $criteria   {@link CriteriaElement}
     * @param   boolean $id_as_key  Use the ID as key into the array
     * @return  array
     **/
    function getObjects($criteria = null, $id_as_key = false)
    {
        $ret = array();
        $limit = $start = 0;
        $sql = 'SELECT * FROM '.$this->db->prefix('modules');
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' '.$criteria->renderWhere();
            $sql .= ' ORDER BY weight '.$criteria->getOrder().', mid ASC';
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $module = new MultisiteModule();
            $module->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] =& $module;
            } else {
                $ret[$myrow['mid']] =& $module;
            }
            unset($module);
        }
        return $ret;
    }

    /**
     * Count some modules
     *
     * @param   object  $criteria   {@link CriteriaElement}
     * @return  int
     **/
    function getCount($criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('modules');
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' '.$criteria->renderWhere();
        }
        if (!$result = $this->db->query($sql)) {
            return 0;
        }
        list($count) = $this->db->fetchRow($result);
        return $count;
    }

    /**
     * returns an array of module names
     *
     * @param   bool    $criteria
     * @param   boolean $dirname_as_key
     *      if true, array keys will be module directory names
     *      if false, array keys will be module id
     * @return  array
     **/
    function getList($criteria = null, $dirname_as_key = false)
    {
        $ret = array();
        $modules = $this->getObjects($criteria, true);
        foreach (array_keys($modules) as $i) {
            if (!$dirname_as_key) {
                $ret[$i] = $modules[$i]->getVar('name');
            } else {
                $ret[$modules[$i]->getVar('dirname')] = $modules[$i]->getVar('name');
            }
        }
        return $ret;
    }
}
?>