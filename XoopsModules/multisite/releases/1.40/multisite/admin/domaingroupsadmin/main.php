<?php
// $Id: main.php 2711 2009-01-22 10:01:21Z phppp $
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
error_reporting(E_ALL);
include_once XOOPS_ROOT_PATH."/modules/multisite/admin/domaingroupsadmin/domaingroupsadmin.php";
$op = "list";
if ( isset($_REQUEST) ) {
    foreach ( $_REQUEST as $k => $v ) {
        ${$k} = $v;
    }
}

if ( $op == "list" ) {
    @xoops_domaingroup_list($op, $_GET['fct']);
    exit();
}

if ( $op == "adddomain")
{
	@create_new_domaingroup($op, $_GET['fct']);
	exit;
}

if ( $op == "editdomain")
{
	@edit_domaingroup_form($_GET['id'], $op, $_GET['fct']);
	exit;
}

if ( $op == "delete")
{
	@delete_domaingroup($op, $_GET['fct'], $_GET['id']);
	exit;
}

if ( $op == "edit")
{
	@edit_domaingroups($op, $_GET['fct']);
	exit;
}

?>