<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 *
 * The TYPOlight webCMS is an accessible web content management system that 
 * specializes in accessibility and generates W3C-compliant HTML code. It 
 * provides a wide range of functionality to develop professional websites 
 * including a built-in search engine, form generator, file and user manager, 
 * CSS engine, multi-language support and many more. For more information and 
 * additional TYPOlight applications like the TYPOlight MVC Framework please 
 * visit the project website http://www.typolight.org.
 *
 * This file modifies the data container array of table tl_module.
 *
 * @copyright  Sven Rhinow 2011
 * @author     Sven Rhinow <sven@sr-tag.de>
 * @package    KampagnenLayer
 * @license    LGPL
 * @filesource

 */
 
 
$GLOBALS['TL_DCA']['tl_module']['palettes']['fss_url']  = 'name,type,fss_name';

// Fields
$GLOBALS['TL_DCA']['tl_module']['fields']['fss_name'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['MOD']['fss_name'],
	'exclude'                 => true,
	'filter'                  => true,
	'sorting'                 => true,
	'inputType'               => 'select',
	'foreignKey'              => 'tl_fakesubsites.name',
	'eval'                    => array('mandatory'=>false, 'multiple'=>false, 'tl_class'=>'clr')
);
?>