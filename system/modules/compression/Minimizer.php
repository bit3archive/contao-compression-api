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
 * Interface Minimizer
 *
 * 
 * @copyright  InfinitySoft 2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Compression API
 */
interface Minimizer
{
	/**
	 * Configure the minimizer.
	 * 
	 * @param array $arrConfig
	 */
	public function configure($arrConfig);
	
	
	/**
	 * Get the current minimizer configuration.
	 * 
	 * @return array
	 */
	public function getConfig();
	
	
	/**
	 * Minimize a file from source to target.
	 * 
	 * @param string $strSource
	 * @param string $strTarget
	 * @return bool
	 * Return true if the minimisation success, otherwise false.
	 */
	public function minimize($strSource, $strTarget);
	
	
	/**
	 * Load a file, minimize the content and return it.
	 * 
	 * @param string $strSource
	 * @return mixed
	 * Return the minimized code if the minimisation success, otherwise false.
	 */
	public function minimizeFromFile($strFile);
	
	
	/**
	 * Minimize the code and write it into the file.
	 * 
	 * @param string $strSource
	 * @return mixed
	 * Return true if the minimisation success, otherwise false.
	 */
	public function minimizeToFile($strFile, $strCode);
	
	
	/**
	 * Minimize the given code and return it.
	 * 
	 * @param string $strCode
	 * @return mixed
	 * Return the minimized code if the minimisation success, otherwise false.
	 */
	public function minimizeCode($strCode);
}
?>