<?php


$GLOBALS['BE_MOD']['dataexchange'] = array
(
	'dataexchange_config' => array
	(
		'tables' => array('tl_dataexchange_config', 'tl_dataexchange_fields'),
		'export'	=> array('DataExchangeBackend','exportTable')
	)
);


$GLOBALS['DataExchangeProvider']['export']['csv'] = array('DataExchangeExportProvider_CSV','exportData');
