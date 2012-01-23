<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright © 2005-2011 Leo Feyer
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
 * @copyright  Stefan Lindecke 2012
 * @author     Stefan Lindecke <stefan@chektrion.de>
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


class DataExchange extends Controller
{
	protected $strDCA;
	protected $separator;
	protected $arrData = array();
	
	
	public function __construct($strDCA,$strName,$bGenerateBEConfig)
	{
		$this->import("Database");
		
		$this->strDCA = $strDCA;
		
		$this->__set("separator",";");
	}
	
	public function loadData()
	{
			
	
	}
	
	
	public function __set($strKey,$varValue)
	{
		$this->arrData[$strKey] = $varValue;
	}


	public function __get($strKey)
	{
		return $this->arrData[$strKey];
	}

	
	public function exportFile($strFile)
	{	
		$objData = $this->Database->prepare("SELECT * FROM ".$this->strDCA)->execute();
			
		$objExportFile = new CsvWriter();
		$arrData = array();
		
		while ($objData->next())
		{
			$arrData[] = $objData->row();
		
		}
		
		$objExportFile->content = $arrData;
		$objExportFile->saveToFile($strFile);
	}
	
	
	
}

