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
 * @copyright  Stefan Lindecke 2012
 * @author     Stefan Lindecke <stefan@chektrion.de>
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


$GLOBALS['TL_DCA']['tl_dataexchange_config'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'				=> 'Table',
		'ctable'					=> array('tl_dataexchange_fields'),
		'switchToEdit'				=> true,
		'enableVersioning'			=> true,
		'onsubmit_callback' => array
		(
			array('tl_dataexchange_config', 'onSubmitCallback'),
		)
	),

	
	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'					=> 1,
			'fields'				=> array('name'),
			'flag'					=> 1,
			'panelLayout'			=> 'filter;search,limit'
		),
		'label' => array
		(
			'fields'				=> array('name', 'tableName'),
			'format'				=> '<strong>%s</strong> (%s)<br>',
			'label_callback'		=> array('tl_dataexchange_config', 'getRowLabel')
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'				=> 'act=select',
				'class'				=> 'header_edit_all',
				'attributes'		=> 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['tl_dataexchange_config']['edit'],
				'href'				=> 'table=tl_dataexchange_fields',
				'icon'				=> 'edit.gif',
			),
			'editheader' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['tl_dataexchange_config']['editheader'],
				'href'				=> 'act=edit',
				'icon'				=> 'header.gif',
			),
			'copy' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['tl_dataexchange_config']['copy'],
				'href'				=> 'act=copy',
				'icon'				=> 'copy.gif',
			),
			'export' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['tl_dataexchange_config']['export'],
				'href'				=> 'key=export',
				'icon'				=> 'theme_export.gif',
			),
			'delete' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['tl_dataexchange_config']['delete'],
				'href'				=> 'act=delete',
				'icon'				=> 'delete.gif',
				'attributes'		=> 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
			),
			'show' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['tl_dataexchange_config']['show'],
				'href'				=> 'act=show',
				'icon'				=> 'show.gif'
			)
		)
	),

	// Palettes
	'palettes'	=> array
	(
		'__selector__'				=> array('exportType', 'exportToFile'),
		'default'					=> '{config_legend},name,tableName,exportType',
		'csv'						=> '{config_legend},name,addExportInDCA,tableName,exportType;{csv_legend},exportCSVSeparator,exportCSVExcel,includeHeader;{expert_legend:hide},sqlWhere,sqlOrderBy;{output_legend},prependString,exportToFile'
	),
	
	// Subpalettes
	'subpalettes'	=> array
	(
		'exportToFile'				=> 'storeDir'
	),
	
	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_dataexchange_config']['name'],
			'exclude'				=> true,
			'search'				=> true,
			'inputType'				=> 'text',
			'eval'					=> array('mandatory'=>true, 'maxlength'=>255,'tl_class'=>'w50')
		),
		'addExportInDCA' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_dataexchange_config']['addExportInDCA'],
			'exclude'				=> true,
			'inputType'				=> 'checkbox',
			'eval'					=> array('tl_class'=>'w50 m12')
		),
		'tableName' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_dataexchange_config']['tableName'],
			'exclude'				=> true,
			'inputType'				=> 'select',
			'options'				=> $this->Database->listTables(),
			'eval'					=> array('tl_class'=>'w50'),
		),
		'exportType' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_dataexchange_config']['exportType'],
			'exclude'				=> true,
			'inputType'				=> 'select',
			'default'				=> 'csv',
			'options'				=> array_keys($GLOBALS['DataExchangeProvider']['export']),
			'reference'				=> &$GLOBALS['TL_LANG']['tl_dataexchange_config']['DataExchangeProvider']['export'],
			'eval'					=> array('submitOnChange'=>true),
			'eval'					=> array('tl_class'=>'w50'),
		),
		'exportCSVSeparator' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_dataexchange_config']['exportCSVSeparator'],
			'exclude'				=> true,
			'default'				=> ',',
			'inputType'				=> 'select',
			'options'				=> array(','=>$GLOBALS['TL_LANG']['MSC']['comma'], ';'=>$GLOBALS['TL_LANG']['MSC']['semicolon'], 'tab'=>$GLOBALS['TL_LANG']['MSC']['tabulator']),
			'eval'					=> array('mandatory'=>true, 'tl_class'=>'w50'),
		),
		'exportCSVExcel' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_dataexchange_config']['exportCSVExcel'],
			'exclude'				=> true,
			'inputType'				=> 'checkbox',
			'eval'					=> array('tl_class'=>'w50 m12'),
		),
		'includeHeader' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_dataexchange_config']['includeHeader'],
			'exclude'				=> true,
			'inputType'				=> 'checkbox',
			'eval'					=> array('tl_class'=>'w50'),
		),
		'sqlWhere' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_dataexchange_config']['sqlWhere'],
			'exclude'				=> true,
			'inputType'				=> 'text',
			'eval'					=> array('maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>'long'),
		),
		'sqlOrderBy' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_dataexchange_config']['sqlOrderBy'],
			'exclude'				=> true,
			'inputType'				=> 'text',
			'eval'					=> array('maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>'long'),
		),		
		'prependString' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_dataexchange_config']['prependString'],
			'exclude'				=> true,
			'search'				=> true,
			'inputType'				=> 'text',
			'eval'					=> array('maxlength'=>255)
		),
		'exportToFile' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_dataexchange_config']['exportToFile'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true)
		),
		'storeDir' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_dataexchange_config']['storeDir'],
			'exclude'				=> true,
			'inputType'				=> 'fileTree',
			'eval'					=> array('fieldType'=>'radio', 'mandatory'=>true, 'tl_class'=>'clr')
		),
	)
);


class tl_dataexchange_config extends Backend
{

	public function getRowLabel($row, $label, $dc)
	{
		
		$objFields = $this->Database->prepare("SELECT dcaField FROM tl_dataexchange_fields WHERE pid=? AND enabled=1 ORDER BY sorting")->execute($row['id']);

		
		$arrFields = array();
		
		while ($objFields->next())
		{
			$arrFields[] = $objFields->dcaField;
		}
		  
		return '
<div class="limit_height' . (!$GLOBALS['TL_CONFIG']['doNotCollapse'] ? ' h32' : '') . ' block">
  		<span class="name">'.$label. implode(", ",$arrFields) . '</span>
</div>';
	}
	
	
	public function onSubmitCallback(DataContainer $dc)
	{
		$strTableName = $dc->activeRecord->tableName;
		
		if ($this->Database->tableExists($strTableName))
		{	
			$arrTableFields = $this->Database->listFields($strTableName);
			
			$arrHideFields = array('id', 'pid');
			$sorting = 0;
			
			foreach ($arrTableFields as $tableField)
			{
				$objFieldExists = $this->Database->prepare("SELECT * FROM tl_dataexchange_fields WHERE pid=? AND dcaField=?")->execute($dc->activeRecord->id,$tableField['name']);
		
				if ($objFieldExists->numRows == 0)
				{
					$arrInsertData = array
					(
						'pid' => $dc->activeRecord->id,
						'dcaField' =>$tableField['name'],
						'enabled' => !in_array($tableField['name'], $arrHideFields),
						'sorting'=>$sorting++
					);
					
					$this->Database->prepare("INSERT INTO tl_dataexchange_fields %s")->set($arrInsertData)->execute();
				}
			}
		}
	}
}

