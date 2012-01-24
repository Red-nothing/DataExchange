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


class DataExchangeBackend extends Backend
{

	public function exportTable(DataContainer $dc)
	{
		$exportID = $dc->id;
		
		if ($this->Input->get('return'))
		{
			$exportID = $this->Input->get('id');
		}
		
		
		$objConfig = $this->Database->prepare("SELECT * FROM tl_dataexchange_config WHERE id=?")
								 	->limit(1)
									->execute($exportID);

		if ($objConfig->numRows < 1)
		{
			return;
		}

		$objDataExchangeFields = $this->Database->prepare("SELECT * FROM tl_dataexchange_fields WHERE pid=? AND enabled=1 AND dcaTableName=? ORDER BY sorting")
								   ->execute($dc->id,$objConfig->tableName);

		$arrFields = array();
		while ($objDataExchangeFields->next())
		{
			$arrFields[] = $objDataExchangeFields->dcaField;
		}	
		
		$objData = $this->Database->prepare("SELECT " . implode(',', $arrFields)." FROM " . $objConfig->tableName)->execute();

		$objExportFile = new CsvWriter();
		$arrData = array();
		
		
		$this->loadDataContainer($objConfig->tableName);
		
		while ($objData->next())
		{	
			$arrFieldData = $objData->row();
			
			if (strlen($objConfig->exportRAW)==0)
			{	
				foreach ($arrFields as $field)
				{	
					$arrDataItem = $GLOBALS['TL_DCA'][$objConfig->tableName]['fields'][$field];
					
					
					$strClass = $GLOBALS['TL_FFL'][$arrDataItem['inputType']];
		
					if (!$this->classFileExists($strClass))
					{
						continue;
					}
		
					$arrDataItem['eval']['required'] = $arrDataItem['eval']['mandatory'];
		
					$arrDataItem['default'] = $arrFieldData[$field];
					
					$arrWidget = $this->prepareForWidget($arrDataItem, $field, $arrDataItem['default']);
					$objWidget = new $strClass($arrWidget);
					$objParsedWidget = $objWidget->parse();
					
					if ((is_array($arrWidget['options'])) && (count($arrWidget['options'])>0))
					{
						$arrFieldOptions = array();
						
						foreach ($arrWidget['options'] as $widgetField)
						{
							$arrFieldOptions[$widgetField['value']] = $widgetField['label'];
						
						}
						
						if (!is_array($objWidget->value))
						{
							$arrFieldData[$field]=$arrFieldOptions[$objWidget->value];
						}
						else 
						{
							$arrFieldData[$field]=$objWidget->value;	
						}
						
			
					} 
					else 
					{
						$arrFieldData[$field]=$objWidget->value;	
					}
				}	
				
			}
			
			$arrData[] = $arrFieldData;
		}
		
		
		if ($objConfig->includeHeader)
		{
			$objExportFile->headerFields = $arrFields;
		}
		
		$objExportFile->seperator = $objConfig->exportCSVSeparator;
		$objExportFile->excel = $objConfig->exportCSVExcel;

		
		$objExportFile->content = $arrData;
		
		if ($objConfig->exportToFile)
		{
			$strStoreDir = $objConfig->storeDir;
		
			if ($strStoreDir == '')
			{
				$strStoreDir = $GLOBALS['TL_CONFIG']['uploadPath'];
			}
			
			$objExportFile->saveToFile(sprintf('%s/%s%s.csv',$strStoreDir,
							$this->replaceInsertTags($objConfig->prependString),
							$objConfig->tableName));
		}
		else
		{
			$objExportFile->saveToBrowser();
		}
		
		
		if ($this->Input->get('return'))
		{
			$this->redirect('contao/main.php?do='.$this->Input->get('return'));
		}
		else
		{
			$this->redirect('contao/main.php?do=dataexchange_config');
		}
	}


	public function loadDataContainerHook($strName)
	{
		$arrOperations = array();
		$objDBExport = $this->Database->prepare("SELECT * FROM tl_dataexchange_config WHERE tableName=? AND addExportInDCA='1'")->execute($strName);
		
		while ($objDBExport->next())
		{
			$arrOperations['export_'.$objDBExport->id] = array
			(
				'label'		=> $objDBExport->name,
				'href'		=> 'do=dataexchange_config&amp;key=export&amp;id='.$objDBExport->id.'&amp;return='.$this->Input->get('do'),				
				'class'		=> 'dataexchange header_dataexchange_' . $objDBExport->exportType . ' dataexchange_'.standardize($objDBExport->name),
			);
		}
		
		if (!empty($arrOperations))
		{
			array_insert($GLOBALS['TL_DCA'][$objDBExport->tableName]['list']['global_operations'], 0, $arrOperations);
			$GLOBALS['TL_CSS'][] = 'system/modules/DataExchange/html/style.css';
		}
	}
}
