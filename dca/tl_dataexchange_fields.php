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


$GLOBALS['TL_DCA']['tl_dataexchange_fields'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'				=> 'Table',
		'ptable'					=> 'tl_dataexchange_config',
		'enableVersioning'			=> true,
	),

	
	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'					=> 4,
			'fields'				=> array('sorting'),
			'panelLayout'			=> 'filter,search,limit',
			'headerFields'			=> array('name', 'tableName'),
			'child_record_callback'	=> array('tl_dataexchange_fields', 'listField')
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
				'label'				=> &$GLOBALS['TL_LANG']['tl_dataexchange_fields']['edit'],
				'href'				=> 'act=edit',
				'icon'				=> 'edit.gif'
			),
			'copy' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['tl_dataexchange_fields']['copy'],
				'href'				=> 'act=paste&amp;mode=copy',
				'icon'				=> 'copy.gif',
				'attributes'		=> 'onclick="Backend.getScrollOffset();"'
			),
			'cut' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['tl_dataexchange_fields']['cut'],
				'href'				=> 'act=paste&amp;mode=cut',
				'icon'				=> 'cut.gif',
				'attributes'		=> 'onclick="Backend.getScrollOffset();"'
			),
			'delete' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['tl_dataexchange_fields']['delete'],
				'href'				=> 'act=delete',
				'icon'				=> 'delete.gif',
				'attributes'		=> 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'toggle' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['tl_dataexchange_fields']['toggle'],
				'icon'				=> 'invisible.gif',
				'attributes'		=> 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback'	=> array('tl_dataexchange_fields', 'toggleIcon')
			),
			'show' => array
			(
				'label'				=> &$GLOBALS['TL_LANG']['tl_dataexchange_fields']['show'],
				'href'				=> 'act=show',
				'icon'				=> 'show.gif'
			)
		)
	),

	'palettes'   =>  array
	(
		'default' => '{field_legend},dcaField;{config_legend},enabled',
	),
	// Fields
	'fields' => array
	(
		'dcaField' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_dataexchange_fields']['dcaField'],
			'exclude'				=> true,
			'search'				=> true,
			'inputType'				=> 'text',
			'eval'					=> array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'long')
		),
		'enabled' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_dataexchange_fields']['enabled'],
			'exclude'				=> true,
			'inputType'				=> 'checkbox',
			'eval'					=> array('tl_class'=>'w50'),
		),
		),
);


class tl_dataexchange_fields extends Backend
{

	public function listField($arrRow)
	{
		$key = $arrRow['enabled'] ? 'unpublished' : 'published';

		return '
' . $arrRow['dcaField'] . '
' . "\n";
	}
	
	
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen($this->Input->get('tid')))
		{
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
			$this->redirect($this->getReferer());
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.$row['enabled'];

		if ($row['enabled'])
		{
			$icon = 'visible.gif';
		}		

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	/**
	 * Toggle the visibility of a form field
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		// Check permissions
		$this->Input->setGet('id', $intId);
		$this->Input->setGet('act', 'toggle');

		$this->createInitialVersion('tl_dataexchange_fields', $intId);

		$this->Database->prepare("UPDATE tl_dataexchange_fields SET tstamp=". time() .", enabled='" . ($blnVisible ? '1' : '') . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_dataexchange_fields', $intId);
	}
}

