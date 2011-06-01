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


/**
 * Table tl_fakesubsites
 */
$GLOBALS['TL_DCA']['tl_fakesubsites'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'					=> 'Table',
		'ctable'                      			=> array('tl_fss_items'),		
		'enableVersioning'				=> true,
		'switchToEdit'                			=> true,
		'label'						=> &$GLOBALS['TL_LANG']['MOD']['fakesubsites'][0],

	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'				=> 1,
			'fields'			=> array('name'),
			'flag'				=> 1,
			'panelLayout'			=> 'filter;search,limit',
			'paste_button_callback'		=> array('tl_fakesubsites', 'pasteTag'),
            		'icon'				=> 'system/modules/fakeSubSites/html/icon.png',

		),
		'label' => array
		(
			'fields'				=> array('name','tag'),
			'format'				=> '%s   {{insert_fss::%s}}',
// 			'label_callback'			=> array('tl_fakesubsites', 'labelCallback'),
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'				=> 'act=select',
				'class'				=> 'header_edit_all',
				'attributes'			=> 'onclick="Backend.getScrollOffset();"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'		=> &$GLOBALS['TL_LANG']['tl_fakesubsites']['edit'],
				'href'          => 'table=tl_fss_items',
				'icon'		=> 'edit.gif'
			),
			'copy' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['tl_fakesubsites']['copy'],
				'href'				=> 'act=paste&mode=copy',
				'icon'				=> 'copy.gif',
				'attributes'			=> 'onclick="Backend.getScrollOffset();"',
			),
			'cut' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['tl_fakesubsites']['cut'],
				'href'				=> 'act=paste&mode=cut',
				'icon'				=> 'cut.gif',
				'attributes'			=> 'onclick="Backend.getScrollOffset();"',
			),
// 			'import' => array
// 			(
// 				'label'					=> &$GLOBALS['TL_LANG']['tl_fakesubsites']['import'],
// 				'href'					=> 'do=fake_items',				
// 				'icon'					=> 'editor.gif',
// 			),
			'delete' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['tl_fakesubsites']['delete'],
				'href'				=> 'act=delete',
				'icon'				=> 'delete.gif',
				'attributes'			=> 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'					=> &$GLOBALS['TL_LANG']['tl_fakesubsites']['show'],
				'href'					=> 'act=show',
				'icon'					=> 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'						=> '{tag_legend},name,page,tag,active',
	),


	// Fields
	'fields' => array
	(
		
		'name' => array
		(
			'label'						=> &$GLOBALS['TL_LANG']['tl_fakesubsites']['name'],
			'inputType'					=> 'text',
			'exclude'					=> false,
			'filter'					=> true,
			'eval'						=> array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'clr')
		),		
		'page' => array
		(
			'label'						=> &$GLOBALS['TL_LANG']['tl_fakesubsites']['page'],
			'inputType'					=> 'pageTree',
			'exclude'					=> true,
			'eval'                    			=> array('fieldType'=>'radio','tl_class'=>'clr'),
		),
		'tag' => array
		(
			'label'						=> &$GLOBALS['TL_LANG']['tl_fakesubsites']['tag'],
			'inputType'					=> 'text',
			'exclude'					=> true,
			'filter'					=> true,
			'eval'						=> array('mandatory'=>true, 'maxlength'=>255, 'nospace'=>true)
		),
		'replacement' => array
		(
			'label'						=> &$GLOBALS['TL_LANG']['tl_fakesubsites']['replacement'],
			'inputType'					=> 'fssTagWizard',
			'exclude'					=> true,
// 			'search'					=> true,
// 			'eval'						=> array('rte'=>'tinyMCE', 'allowHtml'=>true, 'tl_class'=>'clr'),
		),
		'active' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_fakesubsites']['active'],
			'exclude'                 => true,
			'filter'                  => true,			
			'flag'                    => 2,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true)
		)		

	)
);


class tl_fakesubsites extends Backend
{
	

	
	
	/**
	 * Return the paste button
	 * @param object
	 * @param array
	 * @param string
	 * @param boolean
	 * @param array
	 * @return string
	 */
	public function pasteTag(DataContainer $dc, $row, $table, $cr, $arrClipboard=false)
	{
		$this->import('BackendUser', 'User');

		$imagePasteAfter = $this->generateImage('pasteafter.gif', sprintf($GLOBALS['TL_LANG'][$dc->table]['pasteafter'][1], $row['id']));
		$imagePasteInto = $this->generateImage('pasteinto.gif', sprintf($GLOBALS['TL_LANG'][$dc->table]['pasteinto'][1], $row['id']));

		if ($row['id'] == 0)
		{
			return $cr ? $this->generateImage('pasteinto_.gif').' ' : '<a href="'.$this->addToUrl('act='.$arrClipboard['mode'].'&mode=2&pid='.$row['id'].'&id='.$arrClipboard['id']).'" title="'.specialchars(sprintf($GLOBALS['TL_LANG'][$dc->table]['pasteinto'][1], $row['id'])).'" onclick="Backend.getScrollOffset();">'.$imagePasteInto.'</a> ';
		}

		return (($arrClipboard['mode'] == 'cut' && $arrClipboard['id'] == $row['id']) || $cr) ? $this->generateImage('pasteafter_.gif').' ' : '<a href="'.$this->addToUrl('act='.$arrClipboard['mode'].'&mode=1&pid='.$row['id'].'&id='.$arrClipboard['id']).'" title="'.specialchars(sprintf($GLOBALS['TL_LANG'][$dc->table]['pasteafter'][1], $row['id'])).'" onclick="Backend.getScrollOffset();">'.$imagePasteAfter.'</a> ';
	}

}

                      