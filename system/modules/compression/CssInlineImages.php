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
 * Class CssInlineImages
 *
 *
 * @copyright  InfinitySoft 2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Compression API
 */
class CssInlineImages
{
	/**
	 * Embed images inline into the css code.
	 *
	 * @param File $objFile
	 * @return string The modified css code.
	 */
	public function embed(File $objFile, $intSize)
	{
		return $this->embedCode($objFile->getContent(), $objFile, $intSize);
	}


	/**
	 * Embed images inline into the css code.
	 *
	 * @param string $strFile
	 * @param mixed $varFile
	 * @return string The modified css code.
	 */
	public function embedCode($strCode, $varFile, $intSize)
	{
		if ($intSize > 0) {
			$objHelper = new CssInlineImagesHelper($varFile, $intSize);
			return preg_replace_callback('#url\((.*)\)#U', array($objHelper, 'replace'), $strCode);
		} else {
			return $strCode;
		}
	}
}

/**
 * Helper class.
 */
class CssInlineImagesHelper
{
	/**
	 * The file path.
	 * @var string
	 */
	protected $strFile;


	/**
	 * The max embed image size.
	 * @var int
	 */
	protected $intSize;


	/**
	 * Create new helper
	 * @param File $objFile
	 */
	public function __construct(File $varFile, $intSize)
	{
		$this->strFile = $varFile instanceof File ? $varFile->value : $varFile;
		$this->intSize = $intSize;
	}


	/**
	 * Replace callback
	 */
	public function replace($arrMatch)
	{
		$strUrl = trim($arrMatch[1]);
		if (preg_match('#^["\']#', $strUrl)) {
			$strUrl = substr($strUrl, 1);
		}
		if (preg_match('#["\']$#', $strUrl)) {
			$strUrl = substr($strUrl, 0, -1);
		}
		if (!preg_match('#^\w+://#', $strUrl) && $strUrl[0] != '/')
		{
			// add the css file path to the url
			$strUrl = dirname($this->strFile) . '/' . $strUrl;
			// check if file exists
			if (file_exists(TL_ROOT . '/' . $strUrl)) {
				// create an image object
				$objImage = new File($strUrl);
				// only embed, if it is an image and the size is small enough
				if (preg_match('#^image/#', $objImage->mime) && $objImage->size <= $this->intSize)
				{
					return 'url(data:' . $objImage->mime . ';base64,' . base64_encode($objImage->getContent()) . ')';
				}
			}
		}
		return $arrMatch[0];
	}
}