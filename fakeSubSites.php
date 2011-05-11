<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  de la Haye Kommunikationsdesign 2009 
 * @author     Christian de la Haye 
 * @package    CH Shop 
 * @license    LGPL 
 * @filesource
 */


/**
 * Class fr24shop 
 *
 * @copyright  de la Haye Kommunikationsdesign 2009 
 * @author     Christian de la Haye 
 * @package    CH Shop
 */

class fakeSubSites extends Frontend
{
  /**
  * is get-var then replace 
  * @var bool
  */
  protected $isGet = false;
   
  /**
  * last getvar
  * @var string
  */
  protected $getVar = '';
        
  /**
  * Hook-Method for replace Insert-tags was saved in this Module
  * @return string
  */
  public function fssReplaceInsertTags($strTag)
  {
       global $objPage;
              
       //get-Name ermitteln
       if(count($_GET)>0) 
       {
	   $this->isGet = true;
	   
	   foreach($_GET AS $k => $v) $this->getVar = $k;
       }
       //tag-elemente extrahieren
       $tagPieces = explode('::',$strTag);
       
       //wenn es leer ist oder nicht zu diesem Modul gehört, hier abbrechen
        if(count($tagPieces)<1 || $tagPieces[0]!='insert_fss' || !$this->isGet) return  $strTag;
       
        $dbObj = $this->Database->prepare('SELECT replacement FROM `tl_fakesubsites` WHERE `page` = ? AND `tag` = ? AND active=?')
			       ->execute($objPage->id, $tagPieces[1], 1);
	
	//wenn es keine passenden Datenbankeinträge gibt, hier abbrechen
	if($dbObj->numRows < 1) return $strTag;		       
       	
       	while($dbObj->next())
       	{
	    //array aller eingestellten Ersetzenwerte
	    $replacements = deserialize($dbObj->replacement);
	    
	    foreach($replacements as $replace)
	    {
	        if($this->getVar == $replace['name']) return $replace['value'];
	    }
	    
       	}
       	
       	return  $strTag.'_'.$objPage->id;
  }
  
  /**
  * Hook-Method for insert fake-site-url's
  */
  public function fssGetSearchabelPages($arrPages,$intRoot='')
  {
	$fssArr = array();
	
	//hole alle fss-Einträge um daraus die jeweiligen URls zu erstellen
	$dbObj = $this->Database->prepare('SELECT *  FROM `tl_fakesubsites` WHERE `active`= ?')
		      ->execute(1);
	                                                
	if($dbObj->numRows > 0) while($dbObj->next())
	{
	     //array aller eingestellten Ersetzenwerte
	    $replacements = deserialize($dbObj->replacement);
	    
	    foreach($replacements as $replace)
	    {
	        $fssArr[] =  $this->Environment->base.$this->createUrl('id',$dbObj->page,'k='.$replace['name'],'',true,true);
	    }
	    
	}                                                

	$allPages = array_merge($arrPages,$fssArr);
	return $allPages;
  }
  
  public function createURL($type='url',$strUrl='',$queryStr='',$extraQuery='',$newget=false,$seo=false)
  {	  

	if($type=='id')
	{
	    $objPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
		    ->limit(1)
		    ->execute($strUrl);
		    
	    $pageArr = ($objPage->numRows) ? $objPage->fetchAssoc() : array();
	    
	    $strUrl = $this->addQueryToUrl($pageArr,$queryStr,$newget,$seo);
	    
	}elseif($type=='url'){
	    $url = clone $this;
	    $strUrl = $url->addToUrl($queryStr);
	}
	if(!empty($extraQuery)) $strUrl .= (!strstr($strUrl,'?'))?'?'.$extraQuery:'&amp;'.$extraQuery; 
	return  $strUrl;
  }
        
  protected function addQueryToUrl($pageArr,$strRequest,$newget,$seo=false)
  {
	if($newget) $arrGet=array();
	else $arrGet = $_GET;
	if($strRequest!='')
	{
	    $arrFragments = preg_split('/&(amp;)?/i', $strRequest);
	    foreach ($arrFragments as $strFragment)
	    {
		    $arrParams = explode('=', $strFragment);
		    $arrGet[$arrParams[0]] = $arrParams[1];
	    }
	}
	$strParams = '';
	
	if(count($arrGet)>0)foreach ($arrGet as $k=>$v)
	{
		if($seo)  $strParams .= $GLOBALS['TL_CONFIG']['disableAlias'] ? '&amp;' . $k . '=' . $v  : '/' . $v;
		else  $strParams .= $GLOBALS['TL_CONFIG']['disableAlias'] ? '&amp;' . $k . '=' . $v  : '/' . $k . '/' . $v;
	}

	// Do not use aliases
	if ($GLOBALS['TL_CONFIG']['disableAlias'])
	{
		return 'index.php?' . preg_replace('/^&(amp;)?/i', '', $strParams);
	}

	$pageId = strlen($pageArr['alias']) ? $pageArr['alias'] : $pageArr['id'];

	// Get page ID from URL if not set
	if (empty($pageId))
	{
		$pageId = $this->getPageIdFromUrl();
	}

	return ($GLOBALS['TL_CONFIG']['rewriteURL'] ? '' : 'index.php/') . $pageId . $strParams . $GLOBALS['TL_CONFIG']['urlSuffix'];
    }
}