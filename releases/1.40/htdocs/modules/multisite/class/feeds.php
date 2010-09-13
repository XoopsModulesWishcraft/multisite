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
 * Class for Feeds
 * @author Simon Roberts (simon@chronolabs.org.au)
 * @copyright copyright (c) 2000-2009 XOOPS.org
 * @package kernel
 */
class MultisiteFeeds extends XoopsObject
{

    function MultisiteFeeds($fid = null)
    {
        $this->initVar('fid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('feed_type', XOBJ_DTYPE_OTHER, null, false);
        $this->initVar('feed_name', XOBJ_DTYPE_TXTBOX, null, true, 64);
        $this->initVar('mid', XOBJ_DTYPE_INT, null, true);
        $this->initVar('func_feed', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('func_file', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('xml_buffer_updated', XOBJ_DTYPE_INT, null, false, 255);
        $this->initVar('xml_buffer', XOBJ_DTYPE_OTHER, null, false);
    }


    function fid()
    {
        return $this->getVar("fid");
    }

    function feed_type($format="S")
    {
        return $this->getVar("feed_type", $format);
    }

    function feed_name($format="S")
    {
        return $this->getVar("feed_name", $format);
    }

    function mid()
    {
        return $this->getVar("mid");
    }

    function func_feed()
    {
        return $this->getVar("func_feed");
    }

    function func_file()
    {
        return $this->getVar("func_file");
    }

    function xml_buffer_updated()
    {
        return $this->getVar("xml_buffer_updated");
    }
    function xml_buffer()
    {
        return $this->getVar("xml_buffer");
    }

}


/**
* XOOPS Feeds handler class.
* This class is responsible for providing data access mechanisms to the data source
* of XOOPS user class objects.
*
* @author  Simon Roberts <simon@chronolabs.org.au>
* @package kernel
*/
class MultisiteFeedsHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "newfeeds", 'MultisiteFeeds', "fid", "name");
    }
	
}
?>