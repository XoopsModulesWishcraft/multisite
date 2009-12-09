<?php
// $Id: xoops_version.php 2712 2009-01-22 10:06:01Z phppp $
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

$modversion['name'] = _MI_MULTISITE_NAME;
$modversion['version'] = 1.30;
$modversion['description'] = _MI_MULTISITE_DESC;
$modversion['author'] = "Wishcraft";
$modversion['credits'] = "The XOOPS Project";
$modversion['help'] = "multisite.html";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 1;
$modversion['image'] = "images/multisite_slogo.gif";
$modversion['dirname'] = "multisite";
$modversion['releasedate'] = "Wed: 09 December 2009";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin.php";
$modversion['adminmenu'] = "menu.php";

$modversion['onUpdate'] = "include/update.php";
$modversion['onInstall'] = "include/install.php";
$modversion['onUninstall'] = "include/uninstall.php";

// Sql file (must contain sql generated by phpMyAdmin or phpPgAdmin)
// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/multisite.sql";
// $modversion['sqlfile']['postgresql'] = "sql/pgsql.sql";
// Tables created by sql file (without prefix!)
$modversion['tables'][0] = "policies";
$modversion['tables'][1] = "domainoption";
$modversion['tables'][2] = "domaincategory";
$modversion['tables'][3] = "domain";
$modversion['tables'][4] = "newfeeds";

$modversion['blocks'][1]['file'] = "multisite_blocks.php";
$modversion['blocks'][1]['name'] = _MI_MULTISITE_BNAME1;
$modversion['blocks'][1]['description'] = "Shows the main navigation menu of the site";
$modversion['blocks'][1]['show_func'] = "b_multisite_main_show";
$modversion['blocks'][1]['template'] = 'multisite_block_mainmenu.html';

$modversion['templates'][1]['file'] = 'multisite_rss.html';
$modversion['templates'][1]['description'] = 'RSS Site Feed';
$modversion['templates'][2]['file'] = 'multisite_sitemap.html';
$modversion['templates'][2]['description'] = 'Sitemap XML Document';
$modversion['templates'][3]['file'] = 'multisite_atom.html';
$modversion['templates'][3]['description'] = 'ATOM Feed Document';
// Menu
$modversion['hasMain'] = 0;
?>
