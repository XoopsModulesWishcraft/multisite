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
		
			/**#@-*/
			// Check Policies
			$policy_handler =& xoops_getmodulehandler('policy', 'multisite');
			$critera_p = new CriteriaCompo(new Criteria('domains', "%|".str_replace("www.","",strtolower($_SERVER['HTTP_HOST'])).'%', 'LIKE'), 'OR');
			$critera_p->add(new Criteria('domains', "%|all%", 'like')) ;
			$policies = $policy_handler->getObjects($critera_p);
			foreach ($policies as $policy)
				@$policy_handler->checkPolicy($policy);
			
			// ################# Load Config Settings ##############
			$domain_handler =& xoops_getmodulehandler('domain', 'multisite');
			$purl = parse_url(XOOPS_URL);
		//$domain_handler =& xoops_getmodulehandler('domain','multisite');
			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('dom_name', 'domain'));
			$criteria->add(new Criteria('dom_value', $purl['host']));
			$domain = $domain_handler->getDomains($criteria);
			if ($domain_handler->getDomainCount($criteria)>0)
			{
				$domain_handler->set_domain_id($domain[0]);
				if (!defined("XOOPS_DOMAIN_ID"))
					define("XOOPS_DOMAIN_ID", $domain_handler->get_domain_id());

			}

			$xoopsConfig = $domain_handler->getConfigByDomainCat(XOOPS_CONF, $xoopsConfig);
			
			// Disable gzip compression if PHP is run under CLI mode
			// To be refactored
			if (empty($_SERVER['SERVER_NAME']) || substr(PHP_SAPI, 0, 3) == 'cli') {
				$xoopsConfig['gzip_compression'] = 0;
			}
			if ( $xoopsConfig['gzip_compression'] == 1 && extension_loaded( 'zlib' ) && !ini_get( 'zlib.output_compression' ) ) {
				if ( @ini_get( 'zlib.output_compression_level' ) < 0 ) {
					ini_set( 'zlib.output_compression_level', 6 );
				}
			}
			
			// #################### Error reporting settings ##################
			if ( $xoopsConfig['debug_mode'] == 1 || $xoopsConfig['debug_mode'] == 2 ) {
				
				$xoopsLogger->enableRendering();
				$xoopsLogger->usePopup = ( $xoopsConfig['debug_mode'] == 2 );
			} else {
				error_reporting(0);
				$xoopsLogger->activated = false;
			}
			$xoopsSecurity->checkBadips();
	
			// #################### Include site-wide lang file ##################
			if ( !@include_once XOOPS_ROOT_PATH . "/language/" . $xoopsConfig['language'] . "/global.php" ) {
	
			}
			
			// include Smarty template engine and initialize it
			require_once XOOPS_ROOT_PATH . '/class/template.php';
			require_once XOOPS_ROOT_PATH . '/class/theme.php';
			require_once XOOPS_ROOT_PATH . '/class/theme_blocks.php';
		
			if ( @$xoopsOption['template_main'] ) {
				if ( false === strpos( $xoopsOption['template_main'], ':' ) ) {
					$xoopsOption['template_main'] = 'db:' . $xoopsOption['template_main'];
				}
			}
			
			global $xoopsThemeFactory, $xoTheme;
			
			$xoopsThemeFactory =& new xos_opal_ThemeFactory();
			$xoopsThemeFactory->allowedThemes = $xoopsConfig['theme_set_allowed'];
			$xoopsThemeFactory->defaultTheme = $xoopsConfig['theme_set'];
		
			/**
			 * @var xos_opal_Theme
			 */
			$xoTheme =& $xoopsThemeFactory->createInstance( array(
				'contentTemplate' => @$xoopsOption['template_main'],
			) );
			
			$config = $domain_handler->getConfigByDomainCat(XOOPS_CONF_METAFOOTER);
			foreach ( $config as $name => $value ) {
				if ( substr( $name, 0, 5 ) == 'meta_' ) {
					$xoTheme->addMeta( 'meta', substr( $name, 5 ), $value );
				} else {
					// prefix each tag with 'xoops_'
					$xoTheme->template->assign( "xoops_$name", $value );
				}
			}/**/
		}			
	}
?>