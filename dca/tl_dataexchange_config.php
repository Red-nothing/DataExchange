<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

$GLOBALS['TL_DCA']['tl_dataexchange_config'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_dataexchange_fields'),
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'onload_callback' => array
		(
		),
		'onsubmit_callback' => array
		(
			array('tl_dataexchange_config','onSubmitCallback'),
		
		)
	),

	
	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('name'),
			'flag'                    => 1,
			'panelLayout'             => 'filter;search,limit'
		),
		'label' => array
		(
			'fields'                  => array('name','tableName'),
			'format'                  => '<strong>%s</strong> (%s)<br>',
			'label_callback'		=> array('tl_dataexchange_config','getRowLabel')
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_dataexchange_config']['edit'],
				'href'                => 'table=tl_dataexchange_fields',
				'icon'                => 'edit.gif',
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_dataexchange_config']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.gif',
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_dataexchange_config']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif',
			),
			'export' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_dataexchange_config']['export'],
				'href'                => 'key=export',
				'icon'                => 'theme_export.gif',
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_dataexchange_config']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_dataexchange_config']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),


	'palettes'	=> array
	(
		'__selector__' 	=> array('exportType'),
		'default' => '{areaDefault_legend},name,tableName,exportType;{areaExport_legend},prependString,storeDir',
		'csv' => '{areaDefault_legend},name,tableName,exportType,exportRAW;{areaExport_CSV_legend},includeHeader,exportCSVSeparator;{areaExport_legend},prependString,storeDir'
	),
	
	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_dataexchange_config']['name'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255,'tl_class'=>'w50')
		),
		'tableName' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_dataexchange_config']['tableName'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'				=> $this->Database->listTables(),
			'eval'                    => array('tl_class'=>'w50')
		),
		'exportType' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_dataexchange_config']['exportType'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'default'				=> 'csv',
			'options'				=> array_keys($GLOBALS['DataExchangeProvider']['export']),
			'reference'				=> &$GLOBALS['TL_LANG']['tl_dataexchange_config']['DataExchangeProvider']['export'],
			'eval'                    => array('submitOnChange'=>true)
		),
		'includeHeader' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_dataexchange_config']['includeHeader'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array()
		),
		'exportRAW' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_dataexchange_config']['exportRAW'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array()
		),
		'exportCSVSeparator' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_dataexchange_config']['exportCSVSeparator'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true)
		),
		'prependString' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_dataexchange_config']['prependString'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255)
		),
		'storeDir' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_dataexchange_config']['storeDir'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'tl_class'=>'clr')
		),
	)
);


class tl_dataexchange_config extends Backend
{
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
		$this->import("Database");
	}

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
			
			$arrHideFields = array('id','pid');
			$sorting = 0;
			foreach ($arrTableFields as $tableField)
			{
				$objFieldExists = $this->Database->prepare("SELECT * FROM tl_dataexchange_fields WHERE pid=? AND dcaField=?")->execute($dc->activeRecord->id,$tableField['name']);
		
				if ($objFieldExists->numRows == 0)
				{
					$arrInsertData = array(
						'pid' => $dc->activeRecord->id,
						'dcaTableName'	=> $dc->activeRecord->tableName,
						'dcaField' =>$tableField['name'],
						'isRealField' =>1,
						'enabled' => !in_array($tableField['name'],$arrHideFields),
						'sorting'=>$sorting++
					);
					
					$this->Database->prepare("INSERT INTO tl_dataexchange_fields %s")->set($arrInsertData)->execute();
				}
				
			}
		}
	
	}
	
}

?>