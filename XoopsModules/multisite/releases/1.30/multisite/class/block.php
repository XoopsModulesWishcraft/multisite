<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * XOOPS Block management
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package     kernel
 * @since       2.0
 * @author      Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/
 * @author      Skalpa Keo <skalpa@xoops.org>
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @version     $Id: block.php 2772 2009-02-08 11:13:47Z phppp $
 * @package     class
 */

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}
require_once XOOPS_ROOT_PATH."/kernel/object.php";
require_once XOOPS_ROOT_PATH."/class/xoopsblock.php";

class MultisiteBlock extends XoopsBlock
{
    var $db;

    function MultisiteBlock($id = null)
    {
        $this->db = $GLOBALS['xoopsDB'];
        $this->initVar('bid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('mid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('func_num', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('options', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('name', XOBJ_DTYPE_TXTBOX, null, true, 150);
        //$this->initVar('position', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, false, 150);
        $this->initVar('content', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('side', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('weight', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('visible', XOBJ_DTYPE_INT, 0, false);
        // The block_type is in a mess, let's say:
        // S - generated by system module
        // M - generated by a non-system module
        // C - Custom block
        // D - cloned system/module block
        // E - cloned custom block, DON'T use it
        $this->initVar('block_type', XOBJ_DTYPE_OTHER, null, false);
        $this->initVar('c_type', XOBJ_DTYPE_OTHER, null, false);
        $this->initVar('isactive', XOBJ_DTYPE_INT, null, false);

        $this->initVar('dirname', XOBJ_DTYPE_TXTBOX, null, false, 50);
        $this->initVar('func_file', XOBJ_DTYPE_TXTBOX, null, false, 50);
        $this->initVar('show_func', XOBJ_DTYPE_TXTBOX, null, false, 50);
        $this->initVar('edit_func', XOBJ_DTYPE_TXTBOX, null, false, 50);

        $this->initVar('template', XOBJ_DTYPE_OTHER, null, false);
        $this->initVar('bcachetime', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('last_modified', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('domains', XOBJ_DTYPE_TXTAREA, null, false);
		
        if ( !empty($id) ) {
            if ( is_array($id) ) {
                $this->assignVars($id);
            } else {
                $this->load(intval($id));
            }
        }
    }

    function store()
    {
        if ( !$this->cleanVars() ) {
            return false;
        }
        foreach ( $this->cleanVars as $k=>$v ) {
            ${$k} = $v;
        }
        if ( empty($bid) ) {
            $bid = $this->db->genId($this->db->prefix("newblocks")."_bid_seq");
            $sql = sprintf("INSERT INTO %s (bid, mid, func_num, options, name, title, content, side, weight, visible, block_type, c_type, isactive, dirname, func_file, show_func, edit_func, template, bcachetime, last_modified, domains) VALUES (%u, %u, %u, %s, %s, %s, %s, %u, %u, %u, %s, %s, %u, %s, %s, %s, %s, %s, %u, %u, %s)", $this->db->prefix('newblocks'), $bid, $mid, $func_num, $this->db->quoteString($options), $this->db->quoteString($name), $this->db->quoteString($title), $this->db->quoteString($content), $side, $weight, $visible, $this->db->quoteString($block_type), $this->db->quoteString($c_type), 1, $this->db->quoteString($dirname), $this->db->quoteString($func_file), $this->db->quoteString($show_func), $this->db->quoteString($edit_func), $this->db->quoteString($template), $bcachetime, time(), $this->db->quoteString($domains));
        } else {
            $sql = "UPDATE ".$this->db->prefix("newblocks")." SET options=".$this->db->quoteString($options);
            // a custom block needs its own name
            if ( $this->isCustom() /* in_array( $block_type , array( 'C' , 'E' ) ) */) {
                $sql .= ", name=".$this->db->quoteString($name);
            }
            $sql .= ", isactive=".$isactive.", title=".$this->db->quoteString($title).", content=".$this->db->quoteString($content).", side=".$side.", weight=".$weight.", visible=".$visible.", c_type=".$this->db->quoteString($c_type).", template=".$this->db->quoteString($template).", bcachetime=".$bcachetime.", last_modified=".time().", domains=".$this->db->quoteString($domains)." WHERE bid=".$bid;
        }
        if ( !$this->db->query($sql) ) {
            $this->setErrors("Could not save block data into database");
            return false;
        }
        if ( empty($bid) ) {
            $bid = $this->db->getInsertId();
        }
        return $bid;
    }

    /**
    * get all the blocks that match the supplied parameters
    * @param $side   0: sideblock - left
    *        1: sideblock - right
    *        2: sideblock - left and right
    *        3: centerblock - left
    *        4: centerblock - right
    *        5: centerblock - center
    *        6: centerblock - left, right, center
    * @param $groupid   groupid (can be an array)
    * @param $visible   0: not visible 1: visible
    * @param $orderby   order of the blocks
    * @returns array of block objects
    */
    function getAllBlocksByGroup($groupid, $asobject = true, $side = null, $visible = null, $orderby = "b.weight,b.bid", $isactive = 1, $multimode = 1)
    {
        $db = $GLOBALS['xoopsDB'];
        $ret = array();
        if ( !$asobject ) {
            $sql = "SELECT b.bid ";
        } else {
            $sql = "SELECT b.* ";
        }
        $sql .= "FROM ".$db->prefix("newblocks")." b LEFT JOIN ".$db->prefix("group_permission")." l ON l.gperm_itemid=b.bid WHERE gperm_name = 'block_read' AND gperm_modid = 1";
        if ( is_array($groupid) ) {
            $sql .= " AND (l.gperm_groupid=".$groupid[0]."";
            $size = count($groupid);
            if ( $size  > 1 ) {
                for ( $i = 1; $i < $size; $i++ ) {
                    $sql .= " OR l.gperm_groupid=".$groupid[$i]."";
                }
            }
            $sql .= ")";
        } else {
            $sql .= " AND l.gperm_groupid=".$groupid."";
        }

		if( isset($_SERVER['HTTP_HOST']) && $multimode==1)
	        $sql .= " AND (b.domains like '|all%' or b.domains like '%|".str_replace('www.','',strtolower($_SERVER['HTTP_HOST']))."%')";
			
        $sql .= " AND b.isactive=".$isactive;
        if ( isset($side) ) {
            // get both sides in sidebox? (some themes need this)
            if ( $side == XOOPS_SIDEBLOCK_BOTH ) {
                $side = "(b.side=0 OR b.side=1)";
            } elseif ( $side == XOOPS_CENTERBLOCK_ALL ) {
                $side = "(b.side=3 OR b.side=4 OR b.side=5 OR b.side=7 OR b.side=8 OR b.side=9 )";
            } else {
                $side = "b.side=".$side;
            }
            $sql .= " AND ".$side;
        }
        if ( isset($visible) ) {
            $sql .= " AND b.visible=$visible";
        }
        $sql .= " ORDER BY $orderby";
        $result = $db->query($sql);
        $added = array();
        while ( $myrow = $db->fetchArray($result) ) {
            if ( !in_array($myrow['bid'], $added) ) {
                if (!$asobject) {
                    $ret[] = $myrow['bid'];
                } else {
                    $ret[] = new MultisiteBlock($myrow);
                }
                array_push($added, $myrow['bid']);
            }
        }
        //echo $sql;
        return $ret;
    }

    function getAllByGroupModule($groupid, $module_id = 0, $toponlyblock = false, $visible = null, $orderby = 'b.weight, m.block_id', $isactive = 1, $multimode = 1)
    {
        $isactive = intval($isactive);
        $db = $GLOBALS['xoopsDB'];
        $ret = array();
        if (isset($groupid)) {
            $sql = "SELECT DISTINCT gperm_itemid FROM ".$db->prefix('group_permission')." WHERE gperm_name = 'block_read' AND gperm_modid = 1";
            if ( is_array($groupid) ) {
                $sql .= ' AND gperm_groupid IN ('.implode(',', $groupid).')';
            } else {
                if (intval($groupid) > 0) {
                    $sql .= ' AND gperm_groupid='.intval($groupid);
                }
            }

            $result = $db->query($sql);
            $blockids = array();
            while ( $myrow = $db->fetchArray($result) ) {
                $blockids[] = $myrow['gperm_itemid'];
            }
            if (empty($blockids)) {
                return $blockids;
            }
        }
        $sql = 'SELECT b.* FROM '.$db->prefix('newblocks').' b, '.$db->prefix('block_module_link').' m WHERE m.block_id=b.bid';
        $sql .= ' AND b.isactive='.$isactive;
        if (isset($visible)) {
            $sql .= ' AND b.visible='.intval($visible);
        }
		if( isset($_SERVER['HTTP_HOST']) && $multimode == 1 )
			$sql .= " AND (b.domains like '|all%' or b.domains like '%|".str_replace('www.','',strtolower($_SERVER['HTTP_HOST']))."%')";

        if (!isset($module_id)) {
        } elseif (!empty($module_id)) {
            $sql .= ' AND m.module_id IN (0,'. intval($module_id);
            if ($toponlyblock) {
                $sql .= ',-1';
            }
            $sql .= ')';
        } else {
            if ($toponlyblock) {
                $sql .= ' AND m.module_id IN (0,-1)';
            } else {
                $sql .= ' AND m.module_id=0';
            }
        }
        if (!empty($blockids)) {
            $sql .= ' AND b.bid IN ('.implode(',', $blockids).')';
        }
        $sql .= ' ORDER BY '.$orderby;
        $result = $db->query($sql);
        while ( $myrow = $db->fetchArray($result) ) {
            $block =& new MultisiteBlock($myrow);
            $ret[$myrow['bid']] =& $block;
            unset($block);
        }
        return $ret;
    }

    function getNonGroupedBlocks($module_id = 0, $toponlyblock = false, $visible = null, $orderby = 'b.weight, m.block_id', $isactive = 1, $multimode = 1)
    {
        $db = $GLOBALS['xoopsDB'];
        $ret = array();
        $bids = array();
        $sql = "SELECT DISTINCT(bid) from ".$db->prefix('newblocks');
        if ($result = $db->query($sql)) {
            while ( $myrow = $db->fetchArray($result) ) {
                $bids[] = $myrow['bid'];
            }
        }
        $sql = "SELECT DISTINCT(p.gperm_itemid) from ".$db->prefix('group_permission')." p, ".$db->prefix('groups')." g WHERE g.groupid=p.gperm_groupid AND p.gperm_name='block_read'";
        $grouped = array();
        if ($result = $db->query($sql)) {
            while ( $myrow = $db->fetchArray($result) ) {
                $grouped[] = $myrow['gperm_itemid'];
            }
        }
        $non_grouped = array_diff($bids, $grouped);
        if (!empty($non_grouped)) {
            $sql = 'SELECT b.* FROM '.$db->prefix('newblocks').' b, '.$db->prefix('block_module_link').' m WHERE m.block_id=b.bid';
            $sql .= ' AND b.isactive='.intval($isactive);
            if (isset($visible)) {
                $sql .= ' AND b.visible='.intval($visible);
            }

			if( isset($_SERVER['HTTP_HOST']) && $multimode == 1 )
				$sql .= " AND (b.domains like '|all%' or b.domains like '%|".str_replace('www.','',strtolower($_SERVER['HTTP_HOST']))."%')";

            if (!isset($module_id)) {
            } elseif (!empty($module_id)) {
                $sql .= ' AND m.module_id IN (0,'. intval($module_id);
                if ($toponlyblock) {
                    $sql .= ',-1';
                }
                $sql .= ')';
            } else {
                if ($toponlyblock) {
                    $sql .= ' AND m.module_id IN (0,-1)';
                } else {
                    $sql .= ' AND m.module_id=0';
                }
            }
            $sql .= ' AND b.bid IN ('.implode(',', $non_grouped).')';
            $sql .= ' ORDER BY '.$orderby;
            $result = $db->query($sql);
            while ( $myrow = $db->fetchArray($result) ) {
                $block =& new MultisiteBlock($myrow);
                $ret[$myrow['bid']] =& $block;
                unset($block);
            }
        }
        return $ret;
    }

}
?>