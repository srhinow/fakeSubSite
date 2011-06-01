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
 * @copyright  sr-tag.de 2011
 * @author     Sven Rhinow 
 * @package    fakeSubSites
 * @license    LGPL 
 * @filesource
 */

$GLOBALS['BE_FFL']['fssTagWizard'] = 'fssTagWizard';

/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['content']['fakesubsites'] = array
(
	'tables'	=> array('tl_fakesubsites','tl_fss_items'),
	'icon'		=> 'system/modules/fakeSubSites/html/icon.png',
	'importFssItems'=> array('fss', 'importFssItems'),
);

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('fakeSubSites', 'fssReplaceInsertTags');
$GLOBALS['TL_HOOKS']['getSearchablePages'][] = array('fakeSubSites', 'fssGetSearchabelPages');


?>