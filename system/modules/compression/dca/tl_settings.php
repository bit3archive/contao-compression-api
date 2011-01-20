<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  InfinitySoft 2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Layout Additional Sources
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


/**
 * System configuration
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{compression_api:hide},default_compression,default_css_minimizer,default_js_minimizer';
$GLOBALS['TL_DCA']['tl_settings']['fields']['default_compression'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['default_compression'],
	'default'                 => 'none',
	'inputType'               => 'select',
	'options_callback'        => array('tl_settings_compression_api', 'getCompressors'),
	'eval'                    => array('tl_class'=>'w50')
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['default_css_minimizer'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['default_css_minimizer'],
	'default'                 => 'none',
	'inputType'               => 'select',
	'options_callback'        => array('tl_settings_compression_api', 'getCssMinimizers'),
	'eval'                    => array('tl_class'=>'w50 clr')
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['default_js_minimizer'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['default_js_minimizer'],
	'default'                 => 'none',
	'inputType'               => 'select',
	'options_callback'        => array('tl_settings_compression_api', 'getJsMinimizers'),
	'eval'                    => array('tl_class'=>'w50')
);

class tl_settings_compression_api extends Backend
{
	public function __construct()
	{
		$this->import('Compression');
	}
	
	
	public function getCompressors()
	{
		return $this->Compression->getCompressors();
	}
	
	
	public function getCssMinimizers()
	{
		return $this->Compression->getCssMinimizers();
	}
	
	
	public function getJsMinimizers()
	{
		return $this->Compression->getJsMinimizers();
	}
}

?>
