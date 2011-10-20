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
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


/**
 * Compressor settings
 */
$GLOBALS['TL_COMPRESSOR']    = array_merge
(
	array
	(
		'none'  => 'NoneCompressor',
		'gzip'  => 'GzipCompressor',
		'bzip2' => 'Bzip2Compressor'
	),
	isset($GLOBALS['TL_COMPRESSOR']) && is_array($GLOBALS['TL_COMPRESSOR']) ? $GLOBALS['TL_COMPRESSOR'] : array()
);
$GLOBALS['TL_JS_MINIMIZER']  = array_merge
(
	array
	(
		'none' => 'NoneMinimizer'
	),
	isset($GLOBALS['TL_JS_MINIMIZER']) && is_array($GLOBALS['TL_JS_MINIMIZER']) ? $GLOBALS['TL_JS_MINIMIZER'] : array()
);
$GLOBALS['TL_CSS_MINIMIZER'] = array_merge
(
	array
	(
		'none' => 'NoneMinimizer'
	),
	isset($GLOBALS['TL_CSS_MINIMIZER']) && is_array($GLOBALS['TL_CSS_MINIMIZER']) ? $GLOBALS['TL_CSS_MINIMIZER'] : array()
);

/**
 * HOOKs
 */
$GLOBALS['TL_HOOKS']['compressFile'] = array();
$GLOBALS['TL_HOOKS']['compressData'] = array();
$GLOBALS['TL_HOOKS']['minimizeCss']  = array();
$GLOBALS['TL_HOOKS']['minimizeJs']   = array();

?>
