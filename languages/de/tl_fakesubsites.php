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
 * Back end modules
 */
$GLOBALS['TL_LANG']['tl_fakesubsites']['fakesubsites'] = array('Fake-Unterseiten', 'Mit diesem Modul erstellen Sie Suchmaschienen-optimierte Unterseiten, die je nach GET-Übergabe mit den Hinterlegten Ersetzungstext für eine bestimmte Seite alle Platzhalter ersetzt.');
 
/**
* Buttons
*/
$GLOBALS['TL_LANG']['tl_fakesubsites']['new']    = array('Neuer Eintrag', 'Einen neuen Eintrag anlegen');
$GLOBALS['TL_LANG']['tl_fakesubsites']['edit']   = array('Eintrag bearbeiten', 'Eintrag ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_fakesubsites']['copy']   = array('Eintrag duplizieren', 'Eintrag ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_fakesubsites']['delete'] = array('Eintrag löschen', 'Eintrag ID %s löschen');
$GLOBALS['TL_LANG']['tl_fakesubsites']['show']   = array('Eintragdetails', 'Details vom Eintrag ID %s anzeigen');

/**
* Fields
*/
$GLOBALS['TL_LANG']['tl_fakesubsites']['name']  = array('Name (nur fürs Backend)', 'Bitte geben Sie den Namen für die Backend-Übersicht ein.');
$GLOBALS['TL_LANG']['tl_fakesubsites']['page']  = array('Seite', 'die Ersetzungen sind nur für die Inhaltselemente dieser Seite bestimmt.');
$GLOBALS['TL_LANG']['tl_fakesubsites']['tag']  = array('Tag-Name', 'dieser Name wird mit den gefundenen Inserttags verglichen und wenn vorhanden komplett ersetzt. z.B. {{insert_fss::[tag-name]}}'); 
$GLOBALS['TL_LANG']['tl_fakesubsites']['replacement']  = array('Ersetzungen', 'der "GET-Name" wird in der Url  übergeben z.B. zur existierenden Seite so: http://example.com/umzug/berlin.html. Die "Ersetzung wird statt den Platzhaltern im Text geschrieben."');
$GLOBALS['TL_LANG']['tl_fakesubsites']['active']  = array('aktiv', '');

/**
* Widget-Bezeichnungen
*/
$GLOBALS['TL_LANG']['tl_fakesubsites']['opName']  = 'GET-Name';
$GLOBALS['TL_LANG']['tl_fakesubsites']['opValue']  = 'Ersetzung';
