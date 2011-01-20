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
 * Class Bzip2Compressor
 *
 * 
 * @copyright  InfinitySoft 2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Compression API
 */
class Bzip2Compressor extends AbstractCompressor
{
	public function __construct()
	{
		parent::__construct();
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
		if (($target = bzopen(TL_ROOT . '/' . $strTarget, 'w')) === false)
		{
			fclose($src);
			return false;
		}
		while (!feof($src))
		{
			bzwrite($target, fread($src, 1024));
		}
		bzclose($target);
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
		if (($f = bzopen(TL_ROOT . '/' . $strFile)) !== false)
		{
			if (bzwrite($f, $varData) !== false)
			{
				bzclose($f);
				return true;
			}
			bzclose($f);
		}
		return false;
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see Compressor::compressData()
	 */
	public function compressData($varData)
	{
		return bzcompress($varData);
	}
}
?>