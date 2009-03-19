<?php
/**
 * select form element
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code 
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         kernel
 * @subpackage      form
 * @since           2.0.0
 * @author          Kazumi Ono <onokazu@xoops.org>
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: formselect.php 2084 2008-09-14 15:35:57Z phppp $
 */
 
if (!defined('XOOPS_ROOT_PATH')) {
    die("XOOPS root path not defined");
}

xoops_load('xoopsformelement');

/**
 * A select field
 * 
 * @package     kernel
 * @subpackage  form
 * 
 * @author        Kazumi Ono    <onokazu@xoops.org>
 * @copyright    copyright (c) 2000-2003 XOOPS.org
 */
class XoopsFormSelectDomains extends XoopsFormElement
{

    /**
     * Options
     * @var array   
     * @access    private
     */
    var $_options = array();

    /**
     * Allow multiple selections?
     * @var    bool    
     * @access    private
     */
    var $_multiple = false;

    /**
     * Number of rows. "1" makes a dropdown list.
     * @var    int 
     * @access    private
     */
    var $_size;

    /**
     * Pre-selcted values
     * @var    array   
     * @access    private
     */
    var $_value = array();

    /**
     * Constructor
     * 
     * @param    string    $caption    Caption
     * @param    string    $name       "name" attribute
     * @param    mixed    $value        Pre-selected value (or array of them).
     * @param    int        $size        Number or rows. "1" makes a drop-down-list
     * @param    bool    $multiple   Allow multiple selections?
     */
    function XoopsFormSelectDomains($caption, $name, $value = null, $size = 1, $multiple = false, $alldomains = false, $justaddr = false, $https = false)
    {
        $this->setCaption($caption);
        $this->setName($name);
		$this->addOptionDomains($alldomains, $justaddr, $https);
        $this->_multiple = $multiple;
        $this->_size = intval($size);
        if (isset($value)) {
            $this->setValue($value);
        }
    }

    /**
     * Are multiple selections allowed?
     * 
     * @return    bool
     */
    function isMultiple()
    {
        return $this->_multiple;
    }

    /**
     * Get the size
     * 
     * @return    int
     */
    function getSize()
    {
        return $this->_size;
    }

    /**
     * Get an array of pre-selected values
     *
     * @param    bool    $encode To sanitizer the text?
     * @return    array
     */
    function getValue($encode = false)
    {
        if (!$encode) {
            return $this->_value;
        }
        $value = array();
        foreach ($this->_value as $val) {
            $value[] = $val ? htmlspecialchars($val, ENT_QUOTES) : $val;
        }
        return $value;
    }

    /**
     * Set pre-selected values
     * 
     * @param    $value    mixed
     */
    function setValue($value)
    {
        if (is_array($value)) {
            foreach ($value as $v) {
                $this->_value[] = $v;
            }
        } elseif (isset($value)) {
            $this->_value[] = $value;
        }
    }

    /**
     * Add an option
     * 
     * @param    string  $value  "value" attribute
     * @param    string  $name   "name" attribute
     */
    function addOption($value, $name = "")
    {
        if ( $name != "" ) {
            $this->_options[$value] = $name;
        } else {
            $this->_options[$value] = $value;
        }
    }

    /**
     * Add multiple options
     * 
     * @param    array   $options    Associative array of value->name pairs
     */
    function addOptionArray($options)
    {
        if ( is_array($options) ) {
            foreach ( $options as $k=>$v ) {
                $this->addOption($k, $v);
            }
        }
    }

    /**
     * Add multiple domains
     * 
     * @param    array   $options    Associative array of value->name pairs
     */
    function addOptionDomains($alldomains, $justaddr, $https)
    {
    
		$module_handler =& xoops_getmodulehandler('module','multisite');
		$domains_handler =& xoops_getmodulehandler('domain', 'multisite');
		
		$critera_z = new CriteriaCompo(new Criteria('dom_catid', XOOPS_DOMAIN));
		$critera_z->add(new Criteria('dom_name', 'domain')) ;
		$critera_z->setSort('dom_name');
		$domains = $domains_handler->getDomains($critera_z);
		$sprint = str_replace($_SERVER['HTTP_HOST'], '%s', strtolower(XOOPS_URL));
		$sprint = str_replace(array('http://','https://','HTTP://','HTTPS://'), '%s', $sprint);
		if($alldomains==true)
			$domain_list['all'] = _ALL_DOMAINS;

		foreach($domains as $domain)
		{	
			$critera_y = new CriteriaCompo();
			$critera_y->add(new Criteria('dom_pid', $domain->getVar('dom_id')));
			$critera_y->add(new Criteria('dom_name', 'sitename')) ;
			$critera_y->setSort('dom_name');
			$domains_y = $domains_handler->getDomains($critera_y);
			
			if ($justaddr==false)
			{
				if (!$domains_handler->getDomainCount($critera_y)){
					$domain_list[sprintf($sprint ,"http://",$domain->getVar('dom_value'))] = sprintf($sprint ,"http://",$domain->getVar('dom_value'));
					if ($https==true)
						$domain_list[sprintf($sprint ,"https://",$domain->getVar('dom_value'))] = sprintf($sprint ,"https://",$domain->getVar('dom_value'));
				} else {
					$domain_list[sprintf($sprint ,"http://",$domain->getVar('dom_value'))] = "".$domains_y[0]->getVar('dom_value');				
					if ($https==true)
						$domain_list[sprintf($sprint ,"https://",$domain->getVar('dom_value'))] = "(secure) - ".$domains_y[0]->getVar('dom_value');
				}
			} else {
				if (!$domains_handler->getDomainCount($critera_y)){
					$domain_list[$domain->getVar('dom_value')] = sprintf($sprint ,"http://",$domain->getVar('dom_value'));
					if ($https==true)
						$domain_list[$domain->getVar('dom_value')] = sprintf($sprint ,"https://",$domain->getVar('dom_value'));
				} else {
					$domain_list[$domain->getVar('dom_value')] = "".$domains_y[0]->getVar('dom_value');				
					if ($https==true)
						$domain_list[$domain->getVar('dom_value')] = "(secure) - ".$domains_y[0]->getVar('dom_value');
				}
			}
		}	
		
		 if ( is_array($domain_list) ) {
            foreach ( $domain_list as $k=>$v ) {
                $this->addOption($k, $v);
            }
        }
	}
	
    /**
     * Get an array with all the options
     *
     * Note: both name and value should be sanitized. However for backward compatibility, only value is sanitized for now.
     *
     * @param    int     $encode     To sanitizer the text? potential values: 0 - skip; 1 - only for value; 2 - for both value and name
     * @return    array   Associative array of value->name pairs
     */
    function getOptions($encode = false)
    {
        if (!$encode) {
            return $this->_options;
        }
        $value = array();
        foreach ($this->_options as $val => $name) {
            $value[ $encode ? htmlspecialchars($val, ENT_QUOTES) : $val ] = ($encode > 1) ? htmlspecialchars($name, ENT_QUOTES) : $name;
        }
        return $value;
    }

    /**
     * Prepare HTML for output
     * 
     * @return    string  HTML
     */
    function render()
    {
        $ele_name = $this->getName();
        $ele_value = $this->getValue();
        $ele_options = $this->getOptions();
        $ret = "<select size='" . $this->getSize() . "'" . $this->getExtra();
        if ($this->isMultiple() != false) {
            $ret .= " name='{$ele_name}[]' id='{$ele_name}' multiple='multiple'>\n";
        } else {
            $ret .= " name='{$ele_name}' id='{$ele_name}'>\n";
        }
        foreach ( $ele_options as $value => $name ) {
            $ret .= "<option value='" . htmlspecialchars($value, ENT_QUOTES) . "'";
            if (count($ele_value) > 0 && in_array($value, $ele_value)) {
                    $ret .= " selected='selected'";
            }
            $ret .= ">{$name}</option>\n";
        }
        $ret .= "</select>";
        return $ret;
    }

    /**
     * Render custom javascript validation code
     *
     * @seealso XoopsForm::renderValidationJS
    */
    function renderValidationJS() 
    {
        // render custom validation code if any
        if ( !empty( $this->customValidationCode ) ) {
            return implode( "\n", $this->customValidationCode );
        // generate validation code if required 
        } elseif ($this->isRequired()) {
            $eltname    = $this->getName();
            $eltcaption = $this->getCaption();
            $eltmsg = empty($eltcaption) ? sprintf( _FORM_ENTER, $eltname ) : sprintf( _FORM_ENTER, $eltcaption );
            $eltmsg = str_replace('"', '\"', stripslashes( $eltmsg ) );
            return "\nvar hasSelected = false; var selectBox = myform.{$eltname};" .
                "for (i = 0; i < selectBox.options.length; i++ ) { if (selectBox.options[i].selected == true) { hasSelected = true; break; } }" .
                "if (!hasSelected) { window.alert(\"{$eltmsg}\"); selectBox.focus(); return false; }";
        }
        return ''; 
    }
}
?>