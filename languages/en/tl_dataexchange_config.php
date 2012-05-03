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


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_dataexchange_config']['name']				= array('Name', 'Please enter a name for this Data Exchange configuration.');
$GLOBALS['TL_LANG']['tl_dataexchange_config']['addExportInDCA']		= array('Add export button', 'Check here to generate a global export button in the selected DCA.');
$GLOBALS['TL_LANG']['tl_dataexchange_config']['tableName']			= array('Table name', 'Please select the DCA table name.');
$GLOBALS['TL_LANG']['tl_dataexchange_config']['exportType']			= array('Type of export', 'Please select your preferred export type.');
$GLOBALS['TL_LANG']['tl_dataexchange_config']['exportCSVSeparator']	= array('CSV separator', 'Select the separator for your export. Default is a comma.');
$GLOBALS['TL_LANG']['tl_dataexchange_config']['exportCSVExcel']		= array('Excel for Windows', 'Make the export encoding compatible with Excel for Windows.');
$GLOBALS['TL_LANG']['tl_dataexchange_config']['includeHeader']		= array('Include header', 'Should be a header added');
$GLOBALS['TL_LANG']['tl_dataexchange_config']['sqlWhere']			= array('SQL "WHERE" condition', 'You can filter the result set using your custom SQL command.');
$GLOBALS['TL_LANG']['tl_dataexchange_config']['sqlOrderBy']			= array('SQL "ORDER BY" condition', 'You can sort the result set using your custom SQL command.');
$GLOBALS['TL_LANG']['tl_dataexchange_config']['prependString']		= array('File name prefix', 'Prepend string for output file. InsertTags are replaced.');
$GLOBALS['TL_LANG']['tl_dataexchange_config']['exportToFile']		= array('Output to file', 'Check here to output to file instead of the browser.');
$GLOBALS['TL_LANG']['tl_dataexchange_config']['storeDir']			= array('Output directory', 'Select the path where you want to store the export data.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_dataexchange_config']['new']			= array('New configuration', 'Create a DataExchange configuration');
$GLOBALS['TL_LANG']['tl_dataexchange_config']['edit']			= array('Edit fields', 'Edit fields for configuration ID %s');
$GLOBALS['TL_LANG']['tl_dataexchange_config']['editheader']		= array('Edit configuration', 'Edit configuration ID %s');
$GLOBALS['TL_LANG']['tl_dataexchange_config']['copy']			= array('Duplicate configuration', 'Duplicate configuration ID %s');
$GLOBALS['TL_LANG']['tl_dataexchange_config']['delete']			= array('Delete configuration', 'Delete configuration ID %s');
$GLOBALS['TL_LANG']['tl_dataexchange_config']['show']			= array('Configuration details', 'Show details of configuration ID %s');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_dataexchange_config']['config_legend']	= 'Configuration';
$GLOBALS['TL_LANG']['tl_dataexchange_config']['csv_legend']		= 'CSV Export';
$GLOBALS['TL_LANG']['tl_dataexchange_config']['expert_legend']	= 'Expert settings';
$GLOBALS['TL_LANG']['tl_dataexchange_config']['output_legend']	= 'Storing options';

