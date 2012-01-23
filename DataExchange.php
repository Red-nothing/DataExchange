<?php

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

