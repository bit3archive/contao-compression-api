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
 * @package    Compression API
 * @license    LGPL
 * @filesource
 */

/**
 * Class Compression
 *
 * 
 * @copyright  InfinitySoft 2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Compression API
 */
class Compression extends Controller
{
	protected function getComponents($strComponent)
	{
		$this->loadLanguageFile('compression');
		$arrComponents = array();
		foreach ($GLOBALS[$strComponent] as $strKey=>$strClass)
		{
			$arrComponents[$strKey] = $GLOBALS['TL_LANG']['COMPRESSION'][$strKey];
		}
		uksort($arrComponents, array(&$this, 'sortComponents'));
		return $arrComponents;
	}
	
	
	public function getCompressors()
	{
		return $this->getComponents('TL_COMPRESSOR');
	}
	
	
	public function getCompressorClass($strKey)
	{
		if (isset($GLOBALS['TL_COMPRESSOR'][$strKey]))
		{
			return $GLOBALS['TL_COMPRESSOR'][$strKey];
		}
		return false;
	}
	
	
	public function getDefaultCompressor()
	{
		return $GLOBALS['TL_CONFIG']['default_compression'];
	}
	
	
	public function getDefaultCompressorClass()
	{
		return $this->getCompressorClass($GLOBALS['TL_CONFIG']['default_compression']);
	}
	
	
	public function getJsMinimizers()
	{
		return $this->getComponents('TL_JS_MINIMIZER');
	}
	
	
	public function getJsMinimizerClass($strKey)
	{
		if (isset($GLOBALS['TL_JS_MINIMIZER'][$strKey]))
		{
			return $GLOBALS['TL_JS_MINIMIZER'][$strKey];
		}
		return false;
	}
	
	
	public function getDefaultJsMinimizer()
	{
		return $GLOBALS['TL_CONFIG']['default_js_minimizer'];
	}
	
	
	public function getDefaultJsMinimizerClass()
	{
		return $this->getJsMinimizerClass($GLOBALS['TL_CONFIG']['default_js_minimizer']);
	}
	
	
	public function getCssMinimizers()
	{
		return $this->getComponents('TL_CSS_MINIMIZER');
	}
	
	
	public function getCssMinimizerClass($strKey)
	{
		if (isset($GLOBALS['TL_CSS_MINIMIZER'][$strKey]))
		{
			return $GLOBALS['TL_CSS_MINIMIZER'][$strKey];
		}
		return false;
	}
	
	
	public function getDefaultCssMinimizer()
	{
		return $GLOBALS['TL_CONFIG']['default_css_minimizer'];
	}
	
	
	public function getDefaultCssMinimizerClass()
	{
		return $this->getCssMinimizerClass($GLOBALS['TL_CONFIG']['default_css_minimizer']);
	}
	
	
	public function sortComponents($a, $b)
	{
		if ($a == 'none')
			return -1;
		if ($b == 'none')
			return 1;
		return strcasecmp($GLOBALS['TL_LANG']['COMPRESSION'][$a], $GLOBALS['TL_LANG']['COMPRESSION'][$b]);
	}
	
}
?>