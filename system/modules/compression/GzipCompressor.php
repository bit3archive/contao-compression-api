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
 * Class GzipCompressor
 *
 * 
 * @copyright  InfinitySoft 2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Compression API
 */
class GzipCompressor extends AbstractCompressor
{
	public function __construct()
	{
		parent::__construct();
		$this->configure(array
		(
			'level' => -1,
			'mode' => ''
		));
	}

	
	/**
	 * Get the gzopen mode.
	 * 
	 * @return string
	 */
	private function getMode()
	{
		$strMode = 'wb';
		if (isset($this->arrConfig['level']) && is_numeric($this->arrConfig['level']))
		{
			$intLevel = intval($this->arrConfig['level']);
			if ($intLevel >= 0 && $intLevel <= 9)
			{
				$strMode .= $intLevel;
			}
		}
		if (isset($this->arrConfig['mode']))
		{
			switch ($this->arrConfig['mode'])
			{
			case 'filtered':
				$strMode .= 'f';
				break;
				
			case 'huffman':
				$strMode .= 'h';
				break;
			}
		}
		return $strMode;
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see Compressor::compress()
	 */
	public function compress($strSource, $strTarget)
	{
		if (($src = fopen(TL_ROOT . '/' . $strSource, 'rb')) === false)
		{
			return false;
		}
		if (($target = gzopen(TL_ROOT . '/' . $strTarget, $this->getMode())) === false)
		{
			fclose ($src);
			return false;
		}
		while (!feof($src))
		{
			gzwrite($target, fread($src, 1024));
		}
		gzclose($target);
		fclose($src);
		return true;
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see Compressor::compressFromFile()
	 */
	public function compressFromFile($strSource)
	{
		$objFile = new File($strSource);
		$varData = $objFile->getContent();
		$objFile->close();
		
		return $this->compressData($varData);
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see Compressor::compressToFile()
	 */
	public function compressToFile($strFile, $varData)
	{
		if (($f = gzopen(TL_ROOT . '/' . $strFile, $this->getMode())) !== false)
		{
			if (gzwrite($f, $varData) !== false)
			{
				gzclose($f);
				return true;
			}
			gzclose($f);
		}
		return false;
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see Compressor::compressData()
	 */
	public function compressData($varData)
	{
		$intLevel = -1;
		if (isset($this->arrConfig['level']) && is_numeric($this->arrConfig['level']))
		{
			$_intLevel = intval($this->arrConfig['level']);
			if ($_intLevel >= 0 && $_intLevel <= 9)
			{
				$intLevel = $_intLevel;
			}
		}
		return gzencode($varData, $intLevel);
	}
}
?>