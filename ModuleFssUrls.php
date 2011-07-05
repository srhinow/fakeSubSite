<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
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
 */


/**
 * Class ModuleVouchersLatest
 *
 * @copyright  sr-tag 2011 
 * @author     Sven Rhinow <support@sr-tag.de>
 * @package    KampagnenLayer 
 */
class ModuleFssUrls extends Module
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_fss_urls';


	/**
	 * Target pages
	 * @var array
	 */
	protected $arrTargets = array();

        /**
        * show files and layer
        * @var bool
        */
        protected $show = false;
        
                
	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### FSS-URLs ###';

			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'typolight/main.php?do=modules&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}
                // Fallback template
		#if (strlen($this->cnt_template)) $this->strTemplate = $this->cnt_template;

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
	        $resultLines = '';

                //Page-ID holen
		$fssPageObj = $this->Database->prepare("SELECT `name`,`page` FROM `tl_fakesubsites` WHERE `id`=?")
					  ->limit(1)
					  ->execute($this->fss_name);	        
                //FSS-Items holen
		$resultObj = $this->Database->prepare("SELECT `alias` FROM `tl_fss_items` WHERE `pid`=?")
					    ->execute($this->fss_name);
		if ($resultObj->numRows < 1)
		{			
			return $resultLines;			
		}
		
                while ($resultObj->next())
		{
		    $resultLines .=  $this->Environment->url.'/'.$this->urlEncode($this->createURL('id',$fssPageObj->page,'alias='.$resultObj->alias,'',true,true))."\n";
		    #$resultLines .= 'http://www.schuesselschlussverkauf.de/kleinevereine/'.$resultObj->alias.'.html';
		}
		
		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=".standardize($fssPageObj->name).".csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $resultLines;
		exit();
		
		$this->Template->liste = $resultLines;

	   	  	   
	}
	
	public function getSeoFromRequest($request,$checkkey)
	{
	     	$queryVar="";
	     	$getvar=$this->Input->get('Kategorie');
		$requestUri = $this->Environment->requestUri;
		
		if(!empty($getvar)) $queryVar = $getvar;		   		    
		
		if(!$queryVar)
		{
		    if(!empty($requestUri))
		    {	
		        $requestUri=urldecode($requestUri);
		        
			preg_match('/.*\/([A-Za-z0-9-_ ]*)\.html/', $requestUri, $array);
			if(count($array[1])>0) $queryVar = $array[1];
		    }	
		}
		return $queryVar;
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

?>