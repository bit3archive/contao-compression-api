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
 * Class AbstractMinimizer
 *
 * 
 * @copyright  InfinitySoft 2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Compression API
 */
abstract class AbstractCssMinimizer extends System implements Minimizer
{
	/**
	 * The current minimizer configuration.
	 * 
	 * @var array
	 */
	protected $arrConfig = array();
	
	
	/**
	 * (non-PHPdoc)
	 * @see Minimizer::configure()
	 */
	public function configure($arrConfig)
	{
		$this->arrConfig = array_merge_recursive($this->arrConfig, $arrConfig);
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see Minimizer::getConfig()
	 */
	public function getConfig()
	{
		return $this->arrConfig;
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see Minimizer::minimize($strSource, $strTarget)
	 */
	public function minimize($strSource, $strTarget)
	{
		$strCode = $this->minimizeFile($strSource);
		if ($strCode !== false)
		{
			$objFile = new File($strTarget);
			$objFile->write($strCode);
			$objFile->close();
			return true;
		}
		return false;
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see Minimizer::minimizeFromFile($strFile)
	 */
	public function minimizeFromFile($strFile)
	{
		if (file_exists(TL_ROOT . '/' . $strFile))
		{
			$objFile = new File($strFile);
			$strCode = $objFile->getContent();
			$objFile->close();
			return $this->minimizeCode($strCode);
		}
		return false;
	}
	

	/**
	 * (non-PHPdoc)
	 * @see Minimizer::minimizeToFile($strFile, $strCode)
	 */
	public function minimizeFromFile($strFile, $strCode)
	{
		$objFile = new File($strFile);
		if ($objFile->write($this->minimizeCode($strCode)))
		{
			$objFile->close();
			return true;
		}
		$objFile->close();
		return false;
	}
}
?>