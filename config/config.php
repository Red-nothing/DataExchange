<?php


$GLOBALS['BE_MOD']['system'] = array
(
	'dataexchange_config' => array
	(
		'tables' => array('tl_dataexchange_config', 'tl_dataexchange_fields'),
		'export'	=> array('DataExchangeBackend','exportTable')
	)
);


$GLOBALS['DataExchangeProvider']['export']['csv'] = array('DataExchangeExportProvider_CSV','exportData');
$GLOBALS['TL_HOOKS']['loadDataContainer'][] = array('DataExchangeBackend','loadDataContainerHook');
