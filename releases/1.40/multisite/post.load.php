<?php

	global $xoopsConfig;
	$module_handler =& xoops_gethandler('module');
	$critera = new CriteriaCompo(new Criteria('dirname', "multisite"));
	$installed = $module_handler->getCount($critera);

	if ($installed!=0)
	{
		$module =& $module_handler->getByDirname('multisite');
		if ($module->getVar('isactive')==true)
		{

		}			
	}
?>