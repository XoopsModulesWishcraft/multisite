<?php
/**
 * @package     xortify
 * @subpackage  module
 * @description	Sector Network Security Drone
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.org.au>
 * @copyright	copyright (c) 2010-2013 XOOPS.org
 * @licence		docs/End User Licence.pdf
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class MultisiteCorePreload extends XoopsPreloadItem
{
	function eventCoreIncludeCommonEnd($args)
	{
		if (MultisiteCorePreload::isActive()) {
			include_once XOOPS_ROOT_PATH . ( '/modules/multisite/post.load.php' );
			include_once XOOPS_ROOT_PATH . ( '/modules/multisite/post.define.load.php' );			
		}
	}
	
	function eventCoreIncludeCommonStart($args)
	{
		include_once XOOPS_ROOT_PATH . ( '/modules/multisite/pre.load.php' );
	}


	function eventCoreIncludeCommonLanguage($args)
	{
		$config_handler =& xoops_gethandler('config');
		
		if (MultisiteCorePreload::isActive()) {
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
			$criteria = new CriteriaCompo(); 
			$criteria->add(new Criteria('dom_name', 'domain')); 
			$criteria->add(new Criteria('dom_value', strtolower($_SERVER['HTTP_HOST']))); 
			$domain = $domain_handler->getDomains($criteria); 
			if ($domain_handler->getDomainCount($criteria)>0) 
			{ 
				$domain_handler->set_domain_id($domain[0]); 
				if (!defined("XOOPS_DOMAIN_ID")) 
					define("XOOPS_DOMAIN_ID", $domain_handler->get_domain_id()); 
		 
			}     
			 
			$configcategory_handler = &xoops_gethandler( 'configcategory' );         
			$configcategory = $configcategory_handler->get( intval(XOOPS_CONF) ); 
			if (is_object($configcategory)) { 
				$domaincategory_handler = &xoops_getmodulehandler( 'domaincategory', 'multisite' );             
				$domaincategory = $domaincategory_handler->getByName($configcategory->getVar('confcat_name')); 
				if (is_object($domaincategory)) 
					$GLOBALS['xoopsConfig'] = $domain_handler->getDomainsByCat( $domaincategory->getVar('domcat_id') ); 
			} 
		} else { 
			$GLOBALS['xooopsMultisite'] = new XoopsModule(); 
			$GLOBALS['xooopsMultisite']->loadInfoAsVar('multisite'); 
			$GLOBALS['xooopsMultisite']->setVar('isactive', false); 
		} 

		/** 
		 * Create Instantance XoopsLogger Object Xoops 
		 */ 
		if (empty($GLOBALS['xoopsConfig'])) 
			$GLOBALS['xoopsConfig'] = $config_handler->getConfigsByCat( XOOPS_CONF );
	}
	
	function eventCoreClassTheme_blocksRetrieveBlocks($args) {
		if (MultisiteCorePreload::isActive()) {	
			
			include_once (XOOPS_ROOT_PATH.'/modules/multisite/class/block.php');
					
	        global $xoopsConfig;
			$xoopsPreload =& XoopsPreload::getInstance();
	
			$startMod = ($xoopsConfig['startpage'] == '--') ? 'system' : $xoopsConfig['startpage'];
			if (isset($GLOBALS['xoopsModule']) && is_object($GLOBALS['xoopsModule'])) {
				list ($mid, $dirname) = array(
					$GLOBALS['xoopsModule']->getVar('mid') ,
					$GLOBALS['xoopsModule']->getVar('dirname'));
				$isStart = (substr($_SERVER['PHP_SELF'], - 9) == 'index.php' && $xoopsConfig['startpage'] == $dirname);
			} else {
				list ($mid, $dirname) = array(
					0 ,
					'system');
				$isStart = !empty($GLOBALS['xoopsOption']['show_cblock']);
			}
	
			$groups = (isset($GLOBALS['xoopsUser']) && is_object($GLOBALS['xoopsUser'])) ? $GLOBALS['xoopsUser']->getGroups() : array(
				XOOPS_GROUP_ANONYMOUS);
				
			$xoopsblock = new MultisiteBlock();
			$args[2] = array_merge($args[2], $xoopsblock->getAllByGroupModule($groups, $mid, $isStart, XOOPS_BLOCK_VISIBLE));
			array_unique($args[2]);
		}
	}
		
	function isActive()
	{
		$module_handler =& xoops_getHandler('module');
		$module = $module_handler->getByDirname('multisite');
		return ($module && $module->getVar('isactive')) ? true : false;
	}
}

?>