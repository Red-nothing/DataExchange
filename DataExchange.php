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


class DataExchange extends Backend
{

	public function exportTable(DataContainer $dc)
	{
		$objConfig = $this->Database->prepare("SELECT * FROM tl_dataexchange_config WHERE id=?")
								 	->limit(1)
									->execute(($this->Input->get('return') == '' ? $dc->id : $this->Input->get('id')));

		if ($objConfig->numRows < 1)
		{
			$this->redirect('contao/main.php?act=error');
		}
		
		$this->loadDataContainer($objConfig->tableName);

		$objCSV = new CsvWriter();
		$arrData = array();
		$arrResult = $this->getFieldResults($objConfig);
		
		foreach ($arrResult as $arrRow)
		{
			$arrFieldData = array();

			foreach ($arrRow as $arrField)
			{
				if ($arrField['dcaField'] != '')
				{
					$arrFieldData[] = $this->formatValue($objConfig->tableName, $arrField['dcaField'], $arrField['value']);
				}
				else
				{
					$arrFieldData[] = $arrField['value'];
				}
			}
			
			$arrData[] = $arrFieldData;
		}
		
		
		// Add header fields
		if ($objConfig->includeHeader)
		{
			$this->loadLanguageFile($objConfig->tableName);

			$arrHeader = array();
			
			foreach( $arrResult[0] as $id => $arrField )
			{
				if ($arrField['label'] != '')
				{
					$arrHeader[] = $arrField['label'];
				}
				elseif ($arrField['dcaField'] != '')
				{
					$arrHeader[] = $this->formatLabel($objConfig->tableName, $arrField['dcaField']);
				}
				else
				{
					$arrHeader[] = $id;
				}
			}
			
			$objCSV->headerFields = $arrHeader;
		}

        $objCSV->seperator = $objConfig->exportCSVSeparator == "tab" ? "\t" : $objConfig->exportCSVSeparator;
		$objCSV->excel = $objConfig->exportCSVExcel;
		$objCSV->content = $arrData;
		
		if ($objConfig->exportToFile)
		{
			$strStoreDir = $objConfig->storeDir;
		
			if ($strStoreDir == '')
			{
				$strStoreDir = $GLOBALS['TL_CONFIG']['uploadPath'];
			}
			
			$objCSV->saveToFile(sprintf('%s/%s%s.csv',$strStoreDir,
								$this->replaceInsertTags($objConfig->prependString),
								$objConfig->tableName));
		}
		else
		{
			$objCSV->saveToBrowser();
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
	
	
	/**
	 * Get a result set with field config and value
	 * @param Database_Result
	 * @return array
	 */
	protected function getFieldResults(Database_Result $objConfig)
	{
		$arrQuery = array();
		$arrWhere = array();
		$arrValues = array();
		$arrFields = array();
		
		$session = $this->Session->getData();
		$filter = ($GLOBALS['TL_DCA'][$objConfig->tableName]['list']['sorting']['mode'] == 4) ? $objConfig->tableName.'_'.CURRENT_ID : $objConfig->tableName;
		
		if ($objConfig->sqlWhere != '')
		{
			$arrWhere[] = $objConfig->sqlWhere;
		}
		
		$objFields = $this->Database->prepare("SELECT * FROM tl_dataexchange_fields WHERE pid=? AND enabled=1 ORDER BY sorting")
									->execute($objConfig->id);

		while( $objFields->next() )
		{
			$arrFields[$objFields->id] = $objFields->row();
			$arrQuery[] = ($objFields->fieldQuery == '' ? $objFields->dcaField : $objFields->fieldQuery) . ' AS `' . $objFields->id . '`';
			
			if ($objFields->useFilter && $session['filter'][$objConfig->tableName][$objFields->dcaField] != '')
			{
				$field = $objFields->dcaField;

				// Sort by day
				if (in_array($GLOBALS['TL_DCA'][$objConfig->tableName]['fields'][$field]['flag'], array(5, 6)))
				{
					$objDate = new Date($session['filter'][$filter][$field]);
					$arrWhere[] = $field . ' BETWEEN ? AND ?';
					$arrValues[] = $objDate->dayBegin;
					$arrValues[] = $objDate->dayEnd;
				}

				// Sort by month
				elseif (in_array($GLOBALS['TL_DCA'][$objConfig->tableName]['fields'][$field]['flag'], array(7, 8)))
				{
					$objDate = new Date($session['filter'][$filter][$field]);
					$arrWhere[] = $field . ' BETWEEN ? AND ?';
					$arrValues[] = $objDate->monthBegin;
					$arrValues[] = $objDate->monthEnd;
				}

				// Sort by year
				elseif (in_array($GLOBALS['TL_DCA'][$objConfig->tableName]['fields'][$field]['flag'], array(9, 10)))
				{
					$objDate = new Date($session['filter'][$filter][$field]);
					$arrWhere[] = $field . ' BETWEEN ? AND ?';
					$arrValues[] = $objDate->yearBegin;
					$arrValues[] = $objDate->yearEnd;
				}

				// Manual filter
				elseif ($GLOBALS['TL_DCA'][$objConfig->tableName]['fields'][$field]['eval']['multiple'])
				{
					$arrWhere[] = $field . ' LIKE ?';
					$arrValues[] = '%"' . $session['filter'][$filter][$field] . '"%';
				}

				// Other sort algorithm
				else
				{
					$arrWhere[] = $field . '=?';
					$arrValues[] = $session['filter'][$filter][$field];
				}
			}
		}

		$arrResult = array();
		$objResult = $this->Database->prepare("SELECT " . implode(', ', $arrQuery) . " FROM " . $objConfig->tableName . (empty($arrWhere) ? '' : ' WHERE ' . implode(' AND ', $arrWhere)))->execute($arrValues);
		
		
		while( $objResult->next() )
		{
			$arrRow = array();
			
			foreach( $objResult->row() as $id => $value )
			{
				$arrRow[$id] = $arrFields[$id];
				$arrRow[$id]['value'] = $value;
			}
			
			$arrResult[] = $arrRow;
		}
		
		return $arrResult;
	}
	
	
	/**
	 * Format value (based on DC_Table::show(), Contao 2.9.0)
	 * @param string
	 * @param string
	 * @param mixed
	 * @return string
	 */
	public function formatValue($strTable, $strField, $varValue)
	{
		$varValue = deserialize($varValue);
		
		// Decrypt the value
		if ($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['eval']['encrypt'])
		{
			$this->import('Encryption');
			$varValue = $this->Encryption->decrypt($varValue);
		}

		// Get field value
		if (strlen($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['foreignKey']))
		{
			$chunks = explode('.', $GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['foreignKey']);
			$varValue = empty($varValue) ? array(0) : $varValue;
			$objKey = $this->Database->execute("SELECT " . $chunks[1] . " AS value FROM " . $chunks[0] . " WHERE id IN (" . implode(',', array_map('intval', (array)$varValue)) . ")");

			return implode(', ', $objKey->fetchEach('value'));
		}

		elseif (is_array($varValue))
		{
			foreach ($varValue as $kk => $vv)
			{
				$varValue[$kk] = $this->formatValue($strTable, $strField, $vv);
			}

			return implode(', ', $varValue);
		}

		elseif ($varValue != '' && $GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['eval']['rgxp'] == 'date')
		{
			return $this->parseDate($GLOBALS['TL_CONFIG']['dateFormat'], $varValue);
		}

		elseif ($varValue != '' && $GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['eval']['rgxp'] == 'time')
		{
			return $this->parseDate($GLOBALS['TL_CONFIG']['timeFormat'], $varValue);
		}

		elseif ($varValue != '' && ($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['eval']['rgxp'] == 'datim' || in_array($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['flag'], array(5, 6, 7, 8, 9, 10)) || $strField == 'tstamp'))
		{
			return $this->parseDate($GLOBALS['TL_CONFIG']['datimFormat'], $varValue);
		}

		elseif ($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['inputType'] == 'checkbox' && !$GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['eval']['multiple'])
		{
			return strlen($varValue) ? $GLOBALS['TL_LANG']['MSC']['yes'] : $GLOBALS['TL_LANG']['MSC']['no'];
		}

		elseif ($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['inputType'] == 'textarea' && ($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['eval']['allowHtml'] || $GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['eval']['preserveTags']))
		{
			return specialchars($varValue);
		}

		elseif (is_array($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['reference']))
		{
			return isset($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['reference'][$varValue]) ? ((is_array($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['reference'][$varValue])) ? $GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['reference'][$varValue][0] : $GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['reference'][$varValue]) : $varValue;
		}

		return $varValue;
	}


	/**
	 * Format label (based on DC_Table::show(), Contao 2.9.0)
	 * @param string
	 * @param string
	 * @return string
	 */
	public function formatLabel($strTable, $strField)
	{
		// Label
		if (count($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['label']))
		{
			$strLabel = is_array($GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['label']) ? $GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['label'][0] : $GLOBALS['TL_DCA'][$strTable]['fields'][$strField]['label'];
		}

		else
		{
			$strLabel = is_array($GLOBALS['TL_LANG']['MSC'][$strField]) ? $GLOBALS['TL_LANG']['MSC'][$strField][0] : $GLOBALS['TL_LANG']['MSC'][$strField];
		}

		if (!strlen($strLabel))
		{
			$strLabel = $strField;
		}

		return $strLabel;
	}


	/**
	 * Dynamically inject global operation for DataExchange configuration
	 * @param string
	 * @link http://www.contao.org/hooks.html#loadDataContainer
	 */
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

