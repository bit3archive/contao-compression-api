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
 * Class CssUrlRemapper
 *
 * 
 * @copyright  InfinitySoft 2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Compression API
 */
class CssUrlRemapper
{
	/**
	 * Remap local url's of the target file, relative to its source file.
	 * Using the target file as code source and write back the modified code.
	 * 
	 * @param string $strSource
	 * The source or original file.
	 * 
	 * @param string $strTarget
	 * The target file.
	 */
	public function remap($strSource, $strTarget, $blnAbsolutizeUrls = false, $objAbsolutizePage = null)
	{
		$strCode = $this->remapFile($strSource, $strTarget, $blnAbsolutizeUrls, $objAbsolutizePage);
		// write the modified content
		$objFile = new File($strTarget);
		$objFile->write($strCode);
		$objFile->close(); 
	}
	
	
	/**
	 * Remap local url's of the target file, relative to its source file.
	 * Using the target file as code source and return the modified code.
	 * 
	 * @param string $strSource
	 * The source or original file.
	 * 
	 * @param string $strTarget
	 * The target file.
	 */
	public function remapFromFile($strSource, $strTarget, $blnAbsolutizeUrls = false, $objAbsolutizePage = null)
	{
		// read the target file
		$objFile = new File($strTarget);
		$strCode = $objFile->getContent();
		$objFile->close();
		// remap with remapCode(..)
		return $this->remapCode($strCode, $strSource, $strTarget, $blnAbsolutizeUrls, $objAbsolutizePage);
	}
	
	
	/**
	 * Remap local url's of the target file, relative to its source file.
	 * Using the given code and write modified code to target.
	 * 
	 * @param string $strSource
	 * The source or original file.
	 * 
	 * @param string $strTarget
	 * The target file.
	 */
	public function remapToFile($strCode, $strSource, $strTarget, $blnAbsolutizeUrls = false, $objAbsolutizePage = null)
	{
		// read the target file
		$objFile = new File($strTarget);
		if ($objFile->write($this->remapCode($strCode, $strSource, $strTarget, $blnAbsolutizeUrls, $objAbsolutizePage)))
		{
			$objFile->close();
			return true;
		}
		$objFile->close();
		return false;
	}
	
	
	/**
	 * Remap local url's of the target file, relative to its source file.
	 * Using the given code and return the modified code.
	 * 
	 * @param string $strSource
	 * The source or original file.
	 * 
	 * @param string $strTarget
	 * The target file.
	 */
	public function remapCode($strCode, $strSource, $strTarget, $blnAbsolutizeUrls = false, $objAbsolutizePage = null)
	{
		// calculate the relative path between target and source
		if ($blnAbsolutizeUrls)
		{
			$strRemappingPath = dirname($strSource);
		}
		else
		{
			$strRemappingPath = $this->calculateRemappingPath($strSource, $strTarget);
		}
		// remap the url's
		$objUrlRemapper = new CssUrlRemapperHelper($strRemappingPath, $blnAbsolutizeUrls, $objAbsolutizePage);
		return preg_replace_callback('#url\((.*)\)|@import (.*);#U', array(&$objUrlRemapper, 'replace'), $strCode);
	}
	
	
	/**
	 * Calculate a remapped url prefix.
	 * 
	 * @param string $strSourceFile
	 * @param string $strTargetFile
	 * @return string
	 */
	public function calculateRemappingPath($strSourceFile, $strTargetFile)
	{
		$strSourceDir = dirname($strSourceFile);
		$strTargetDir = dirname($strTargetFile);
		$strRelativePath = '';
		while ($strTargetDir && $strTargetDir != '.' && strpos($strSourceDir . '/', $strTargetDir . '/') !== 0)
		{
			$strRelativePath .= '../';
			$strTargetDir = dirname($strTargetDir);
		}
		return $strRelativePath . ($strTargetDir != '.' ? substr($strSourceDir, strlen($strTargetDir) + 1) : $strSourceDir);
	}
	
}


/**
 * Class UrlRemapper
 * 
 * Callback class for preg_replace_callback.
 * 
 * @copyright  InfinitySoft 2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Compression API
 */
class CssUrlRemapperHelper extends Controller {
	private $strRelativePath;
	private $blnAbsolutizeUrls;
	private $objAbsolutizePage;
	
	public function __construct($strRelativePath, $blnAbsolutizeUrls = false, $objAbsolutizePage = null)
	{
		$this->import('DomainLink');
		$this->strRelativePath = $strRelativePath;
		$this->blnAbsolutizeUrls = $blnAbsolutizeUrls;
		$this->objAbsolutizePage = $objAbsolutizePage;
	}
	
	public function replace($arrMatch)
	{
		$strUrl = isset($arrMatch[2]) ? trim($arrMatch[2]) : trim($arrMatch[1]);
		if (preg_match('#^["\']#', $strUrl)) {
			$strUrl = substr($strUrl, 1);
		}
		if (preg_match('#["\']$#', $strUrl)) {
			$strUrl = substr($strUrl, 0, -1);
		}
		if (!preg_match('#^\w+://#', $strUrl) && $strUrl[0] != '/')
		{
			$strPath = $this->strRelativePath;
			while (preg_match('#^\.\./#', $strUrl))
			{
				$strPath = dirname($strPath);
				$strUrl = substr($strUrl, 3);
			}
			$strUrl = $strPath . '/' . $strUrl;
			if ($this->blnAbsolutizeUrls)
			{
				$strUrl = $this->DomainLink->absolutizeUrl($strUrl, $this->objAbsolutizePage);
			}
			
			return str_replace(isset($arrMatch[2]) ? $arrMatch[2] : $arrMatch[1], '"'.$strUrl.'"', $arrMatch[0]);
		}
		return $arrMatch[0];
	}
}
?>