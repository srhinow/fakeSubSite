<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Class Theme
 *
 * Provide methods to handle themes.
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Controller
 */
class fss extends Backend
{

	/**
	 * Import a theme
	 */
	public function importFssItems()
	{
		if ($this->Input->post('FORM_SUBMIT') == 'tl_fss_import')
		{
			$source = $this->Input->post('source', true);

			// Check the file names
			if (!$source || !is_array($source))
			{
				$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['ERR']['all_fields'];
				$this->reload();
			}

			$arrFiles = array();

			// Skip invalid entries
			foreach ($source as $strFile)
			{
				// Skip folders
				if (is_dir(TL_ROOT . '/' . $strFile))
				{
					$_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['ERR']['importFolder'], basename($strFile));
					continue;
				}

				$objFile = new File($strFile);

				// Skip anything but .cto files
				if ($objFile->extension != 'csv')
				{
					$_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $objFile->extension);
					continue;
				}

				$arrFiles[] = $strFile;
			}

			// Check wether there are any files left
			if (count($arrFiles) < 1)
			{
				$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['ERR']['all_fields'];
				$this->reload();
			}

			// Store the field names of the theme tables
			$arrDbFields = array
			(
				'tl_fss_items'       => $this->Database->getFieldNames('tl_fss_items')
			);


			return $this->extractEntryFiles($arrFiles, $arrDbFields);

		}

		$objTree = new FileTree($this->prepareForWidget($GLOBALS['TL_DCA']['tl_fss_items']['fields']['source'], 'source', null, 'source', 'tl_fss_items'));

		// Return the form
		return '
		    <div id="tl_buttons">
		    <a href="'.ampersand(str_replace('&key=importFssItems', '', $this->Environment->request)).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
		    </div>
		    
		    <h2 class="sub_headline">'.$GLOBALS['TL_LANG']['tl_fss_items']['importFssItems'][1].'</h2>'.$this->getMessages().'
		    
		    <form action="'.ampersand($this->Environment->request, true).'" id="tl_fss_import" class="tl_form" method="post">
		    <div class="tl_formbody_edit">
		    <input type="hidden" name="FORM_SUBMIT" value="tl_fss_import" />
		    
		    <div class="tl_tbox block">
		      <h3><label for="source">'.$GLOBALS['TL_LANG']['tl_fss_items']['source'][0].'</label> <a href="contao/files.php" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['fileManager']) . '" onclick="Backend.getScrollOffset(); Backend.openWindow(this, 750, 500); return false;">' . $this->generateImage('filemanager.gif', $GLOBALS['TL_LANG']['MSC']['fileManager'], 'style="vertical-align:text-bottom;"') . '</a></h3>'.$objTree->generate().(strlen($GLOBALS['TL_LANG']['tl_theme']['source'][1]) ? '
		      <p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['tl_fss_items']['source'][1].'</p>' : '').'
		    </div>
		    
		    </div>
		    
		    <div class="tl_formbody_submit">
		    
		    <div class="tl_submit_container">
		      <input type="submit" name="save" id="save" class="tl_submit" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['tl_fss_items']['importTheme'][0]).'" />
		    </div>
		    
		    </div>
		    </form>';
	}


	/**
	 * Extract the Entry files and write the data to the database
	 * @param array
	 * @param array
	 */
	protected function extractEntryFiles($arrFiles, $arrDbFields)
	{
		$this->import('Database');
		
		foreach ($arrFiles as $strFile)
		{
			$csv = null;
                        $this->import('Files');      			
			
			// Lock the tables
 			$arrLocks = array('tl_fss_items' => 'WRITE');
 			$this->Database->lockTables($arrLocks);
      			
      			$handle = $this->Files->fopen($strFile,'r');
      			while (($data = fgetcsv ($handle, 1000, ",")) !== FALSE ) {
      			      
      			      if(empty($data[0])) continue;
      			      
      			      $alias = $this->generateAlias($data[0]);
      			      //Falls ürtümlich vom System vorangestelltes id- entfernen
      			      if(strncmp($alias, 'id-', 3) === 0) $alias = substr($alias,3);
      			      
      			      $set = array(
				  'pid'  => $this->Input->get('id'),
				  'name' => $data[0],
				  'alias' => $alias,
				  'tstamp' => time()
      			      );
      			            			      
      			      // Update the datatbase
			      $this->Database->prepare("INSERT INTO `tl_fss_items` %s")->set($set)->execute();

      			}

			// Unlock the tables
 			$this->Database->unlockTables();


			// Notify the user
			$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['tl_fss_items']['FssItems_imported'], basename($strFile));
		}

		// Redirect
		setcookie('BE_PAGE_OFFSET', 0, 0, '/');
		$this->redirect(str_replace('&key=importFssItems', '', $this->Environment->request));
	}
	
	/**
	 * Autogenerate a event alias if it has not been set yet
	 * @param mixed
	 * @param object
	 * @return string
	 */
	public function generateAlias($name,$alias='')
	{
		$autoAlias = false;
                $this->import('Database');
                
		// Generate alias if there is none
		if (!strlen($alias))
		{
			$autoAlias = true;
			$varValue = standardize($name);
		}

		$objAlias = $this->Database->prepare("SELECT id FROM tl_fss_items WHERE alias=?")
								   ->execute($varValue);

		// Check whether the alias exists
		if ($objAlias->numRows > 1 && !$autoAlias)
		{
			throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
		}

		// Add ID to alias
		if ($objAlias->numRows && $autoAlias)
		{
			$varValue .= '-' . $objAlias->numRows+1;
		}

		return $varValue;
	}



}

?>