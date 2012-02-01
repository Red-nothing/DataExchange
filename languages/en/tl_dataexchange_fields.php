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
$GLOBALS['TL_LANG']['tl_dataexchange_fields']['dcaField']	= array('DCA Field', 'Select if this field should be retrieved from DCA config.');
$GLOBALS['TL_LANG']['tl_dataexchange_fields']['label']		= array('Label', 'Here you can set/override the field label');
$GLOBALS['TL_LANG']['tl_dataexchange_fields']['fieldQuery']	= array('Custom query', 'Enter an SQL query if you want to manually retrieve this field value.');
$GLOBALS['TL_LANG']['tl_dataexchange_fields']['enabled']	= array('Export', 'Check here to include this field in the export.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_dataexchange_fields']['edit'] = array('Edit');
$GLOBALS['TL_LANG']['tl_dataexchange_fields']['copy'] = array('Copy');
$GLOBALS['TL_LANG']['tl_dataexchange_fields']['cut'] = array('Cut');
$GLOBALS['TL_LANG']['tl_dataexchange_fields']['delete'] = array('Delete');
$GLOBALS['TL_LANG']['tl_dataexchange_fields']['toggle'] = array('Toggle visibility');
$GLOBALS['TL_LANG']['tl_dataexchange_fields']['show'] = array('Show');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_dataexchange_fields']['field_legend']	= 'Field settings';
$GLOBALS['TL_LANG']['tl_dataexchange_fields']['config_legend']	= 'Configuration';

