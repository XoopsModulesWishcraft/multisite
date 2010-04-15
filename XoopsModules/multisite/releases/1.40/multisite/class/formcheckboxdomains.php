<?php

/*

 You may not change or alter any portion of this comment or credits

 of supporting developers from this source code or any supporting source code 

 which is considered copyrighted (c) material of the original comment or credit authors.

 

 This program is distributed in the hope that it will be useful,

 but WITHOUT ANY WARRANTY; without even the implied warranty of

 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

*/



/**

 *  Xoops Form Class Elements

 *

 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/

 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license

 * @package         kernel

 * @subpackage      Xoop Forms class

 * @since           2.0.0

 * @author          Kazumi Ono <onokazu@xoops.org>

 * @author          Skalpa Keo <skalpa@xoops.org>

 * @author          Taiwen Jiang <phppp@users.sourceforge.net>

 * @author          John Neill <catzwolf@xoops.org>

 * @version         $Id: formcheckbox.php 3174 2009-04-18 15:00:48Z catzwolf $

 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');



if (! class_exists('XoopsFormElement')) {

    xoops_load('xoopsformelement');

}



/**

 * XoopsFormCheckBoxDomains

 * 

 * @author Kazumi Ono <onokazu@xoops.org>

 * @author Skalpa Keo <skalpa@xoops.org>

 * @author Taiwen Jiang <phppp@users.sourceforge.net>

 * @author John Neill <catzwolf@xoops.org>

 * @copyright copyright (c) 2000-2003 XOOPS.org

 * @package kernel

 * @subpackage form

 * @access public

 */

class XoopsFormCheckBoxDomains extends XoopsFormElement

{

    /**

     * Availlable options

     *

     * @var array

     * @access private

     */

    var $_options = array();

    

    /**

     * pre-selected values in array

     *

     * @var array

     * @access private

     */

    var $_value = array();

    

    /**

     * HTML to seperate the elements

     *

     * @var string

     * @access private

     */

    var $_delimeter;

    

    /**

     * Column number for rendering

     *

     * @var int

     * @access public

     */

    var $columns;

    

    /**

     * Constructor

     *

     * @param string $caption

     * @param string $name

     * @param mixed $value Either one value as a string or an array of them.

     */

    function XoopsFormCheckBoxDomains($caption, $name, $value = null, $delimeter = '&nbsp;', $alldomains = true, $justaddr = false, $https = false)

    {

        $this->setCaption($caption);

        $this->setName($name);

		$this->addOptionDomains($alldomains, $justaddr, $https);

        if (isset($value)) {

            $this->setValue($value);

        }

        $this->_delimeter = $delimeter;

        $this->setFormType('checkbox');

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

					$domain_list[urlencode(sprintf($sprint ,"http://",$domain->getVar('dom_value')))] = sprintf($sprint ,"http://",$domain->getVar('dom_value'));

					if ($https==true)

						$domain_list[urlencode(sprintf($sprint ,"https://",$domain->getVar('dom_value')))] = sprintf($sprint ,"https://",$domain->getVar('dom_value'));

				} else {

					$domain_list[urlencode(sprintf($sprint ,"http://",$domain->getVar('dom_value')))] = "".$domains_y[0]->getVar('dom_value');				

					if ($https==true)

						$domain_list[urlencode(sprintf($sprint ,"https://",$domain->getVar('dom_value')))] = "(secure) - ".$domains_y[0]->getVar('dom_value');

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

     * Get the "value"

     *

     * @param bool $encode To sanitizer the text?

     * @return array

     */

    function getValue($encode = false)

    {

        if (! $encode) {

            return $this->_value;

        }

        $value = array();

        foreach($this->_value as $val) {

            $value[] = $val ? htmlspecialchars($val, ENT_QUOTES) : $val;

        }

        return $value;

    }

    

    /**

     * Set the "value"

     *

     * @param array $

     */

    function setValue($value)

    {

        $this->_value = array();

        if (is_array($value)) {

            foreach($value as $v) {

                $this->_value[] = $v;

            }

        } else {

            $this->_value[] = $value;

        }

    }

    

    /**

     * Add an option

     *

     * @param string $value

     * @param string $name

     */

    function addOption($value, $name = '')

    {

        if ($name != '') {

            $this->_options[$value] = $name;

        } else {

            $this->_options[$value] = $value;

        }

    }

    

    /**

     * Add multiple Options at once

     *

     * @param array $options Associative array of value->name pairs

     */

    function addOptionArray($options)

    {

        if (is_array($options)) {

            foreach($options as $k => $v) {

                $this->addOption($k, $v);

            }

        }

    }

    

    /**

     * Get an array with all the options

     *

     * @param int $encode To sanitizer the text? potential values: 0 - skip; 1 - only for value; 2 - for both value and name

     * @return array Associative array of value->name pairs

     */

    function getOptions($encode = false)

    {

        if (! $encode) {

            return $this->_options;

        }

        $value = array();

        foreach($this->_options as $val => $name) {

            $value[$encode ? htmlspecialchars($val, ENT_QUOTES) : $val] = ($encode > 1) ? htmlspecialchars($name, ENT_QUOTES) : $name;

        }

        return $value;

    }

    

    /**

     * Get the delimiter of this group

     *

     * @param bool $encode To sanitizer the text?

     * @return string The delimiter

     */

    function getDelimeter($encode = false)

    {

        return $encode ? htmlspecialchars(str_replace('&nbsp;', ' ', $this->_delimeter)) : $this->_delimeter;

    }

    

    /**

     * prepare HTML for output

     *

     * @return string

     */

    function render()

    {

        $ele_name = $this->getName();

        $ele_id = $ele_name;

        $ele_value = $this->getValue();

        $ele_options = $this->getOptions();

        $ele_extra = $this->getExtra();

        $ele_delimeter = empty($this->columns) ? $this->getDelimeter() : '';

        

        if (count($ele_options) > 1 && substr($ele_name, - 2, 2) != '[]') {

            $ele_name = $ele_name . '[]';

            $this->setName($ele_name);

        }

        $ret = '';

        if (! empty($this->columns)) {

            $ret .= '<table><tr>';

        }

        $i = 0;

        $id_ele = 0;

        foreach($ele_options as $value => $name) {

            $id_ele ++;

            if (! empty($this->columns)) {

                if ($i % $this->columns == 0) {

                    $ret .= '<tr>';

                }

                $ret .= '<td>';

            }

            $ret .= "<input type='checkbox' name='{$ele_name}' id='{$ele_id}{$id_ele}' value='" . htmlspecialchars($value, ENT_QUOTES) . "'";

            if (count($ele_value) > 0 && in_array($value, $ele_value)) {

                $ret .= ' checked="checked"';

            }

            $ret .= $ele_extra . ' />' . $name . $ele_delimeter . NWLINE;

            if (! empty($this->columns)) {

                $ret .= '</td>';

                if (++ $i % $this->columns == 0) {

                    $ret .= '</tr>';

                }

            }

        }

        if (! empty($this->columns)) {

            if ($span = $i % $this->columns) {

                $ret .= '<td colspan="' . ($this->columns - $span) . '"></td></tr>';

            }

            $ret .= '</table>';

        }

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

        if (! empty($this->customValidationCode)) {

            return implode(NWLINE, $this->customValidationCode);

            // generate validation code if required

        } elseif ($this->isRequired()) {

            $eltname = $this->getName();

            $eltcaption = $this->getCaption();

            $eltmsg = empty($eltcaption) ? sprintf(_FORM_ENTER, $eltname) : sprintf(_FORM_ENTER, $eltcaption);

            $eltmsg = str_replace('"', '\"', stripslashes($eltmsg));

            return NWLINE . "var hasChecked = false; var checkBox = myform.elements['{$eltname}'];" . "for ( var i = 0; i < checkBox.length; i++ ) { if (checkBox[i].checked == true) { hasChecked = true; break; } }" . "if (!hasChecked) { window.alert(\"{$eltmsg}\"); checkBox[0].focus(); return false; }";

        }

        return '';

    }

}



?>