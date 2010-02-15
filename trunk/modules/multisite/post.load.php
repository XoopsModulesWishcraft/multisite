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