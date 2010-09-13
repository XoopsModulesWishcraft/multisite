<?php

	global $xoopsConfig;
	$module_handler =& xoops_gethandler('module');
	$critera = new CriteriaCompo(new Criteria('dirname', "multisite"));
	$installed = $module_handler->getCount($critera);

	if ($installed!=0)
	{
		$module =& $module_handler->getByDirname('multisite');
		error_reporting(E_ALL);
		if ($module->getVar('isactive')==true)
		{

		}			
	}
?>