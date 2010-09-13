<?php

	$module_handler = xoops_gethandler('module');
	$module = $module_handler->getByDirname('multisite');		

	$domain_handler = xoops_getmodulehandler('domain', 'multisite');
	$purl = parse_url(XOOPS_URL);
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('dom_name', 'domain'));
	$criteria->add(new Criteria('dom_value', $purl['host']));
	if(!$domain_handler->getDomainCount($criteria))
	{

	} else {
		$obj_domain = $domain_handler->getDomains($criteria);
		if (is_array($obj_domain))
			$obj_domain = $obj_domain[0];
		
		
		$domaincat_handler = xoops_getmodulehandler('domaincategory', 'multisite');
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('domcat_name', 'XOOPS_DEFINE'));
		if (!$domaincat_handler->getCount($criteria))
		{
			$domcat = $domaincat_handler->create();
			$domcat->setVar('domcat_name', 'XOOPS_DEFINE');
			$domaincat_handler->insert($domcat);
		} else {
			$domcat = $domaincat_handler->getObjects($criteria);
			if (is_array($domcat))
				$domcat = $domcat[0];
		}	

		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('dom_name', 'define')) ;
		$criteria->add(new Criteria('dom_pid', $obj_domain->getVar('dom_id'))) ;
		$criteria->add(new Criteria('dom_catid', $domcat->getVar('domcat_id'))) ;		
		$criteria->add(new Criteria('dom_modid', $module->mid())) ;
		$defines = $domain_handler->getDomains($criteria, true);		
		$definescount = count($defines);
		
		if ($definescount)
		foreach ($defines as $key => $define)
		{
			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('dom_name', 'define_var')) ;
			$criteria->add(new Criteria('dom_catid', $domcat->getVar('domcat_id'))) ;
			$criteria->add(new Criteria('dom_pid', $define->getVar('dom_id'))) ;
			$criteria->add(new Criteria('dom_modid', $module->mid())) ;

			if(!$domain_handler->getDomainCount($criteria))
			{

			} else {
				$domain_out = $domain_handler->getDomains($criteria);
				if (is_array($domain_out))
					$domain_out = $domain_out[0];
	
				if (!defined($define->getConfValueForOutput()))
					define($define->getConfValueForOutput(), $domain_out->getConfValueForOutput());
					
			}
		}
	}


?>