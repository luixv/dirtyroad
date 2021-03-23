<?php
/**
 * Community Builder (TM)
 * @version $Id: $
 * @package CommunityBuilder
 * @copyright (C) 2004-2018 www.joomlapolis.com / Lightning MultiCom SA - and its licensors, all rights reserved
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
 */

use CBLib\Language\CBTxt;

if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

JFormHelper::loadFieldClass( 'groupedlist' );

class JFormFieldCBfields extends JFormFieldGroupedList
{
	protected $type = 'cbfields';

	protected function getGroups()
	{
		global $_CB_database;

		static $CB_loaded			=	0;

		if ( ! $CB_loaded++ ) {
			if ( ( ! file_exists( JPATH_SITE . '/libraries/CBLib/CBLib/Core/CBLib.php' ) ) || ( ! file_exists( JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php' ) ) ) {
				return array();
			}

			include_once( JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php' );

			cbimport( 'cb.html' );
			cbimport( 'language.all' );

			if ( ! defined( 'CBLIB' ) ) {
				return array();
			}
		}

		$options					=	parent::getGroups();
		$key						=	$this->getAttribute( 'key' );

		if ( ! $key ) {
			$key					=	'fieldid';
		}

		$query						=	"SELECT f." . $_CB_database->NameQuote( $key ) . " AS value"
									.	", f." . $_CB_database->NameQuote( 'title' ) . " AS text"
									.	"\n FROM " . $_CB_database->NameQuote( '#__comprofiler_fields' ) . " AS f"
									.	"\n LEFT JOIN " . $_CB_database->NameQuote( '#__comprofiler_tabs' ) . " AS t"
									.	" ON t." . $_CB_database->NameQuote( 'tabid' ) . " = f." . $_CB_database->NameQuote( 'tabid' )
									.	"\n WHERE f." . $_CB_database->NameQuote( 'published' ) . " = 1"
									.	"\n AND f." . $_CB_database->NameQuote( 'name' ) . " != " . $_CB_database->Quote( 'NA' )
									.	"\n AND f." . $_CB_database->NameQuote( 'searchable' ) . " = 1"
									.	"\n ORDER BY t." . $_CB_database->NameQuote( 'position' ) . ", t." . $_CB_database->NameQuote( 'ordering' ) . ", f." . $_CB_database->NameQuote( 'ordering' );
		$_CB_database->setQuery( $query );
		$fields						=	$_CB_database->loadObjectList();

		if ( $fields && $options ) {
			$group					=	CBTxt::T( 'Fields' );
		} else {
			$group					=	0;
		}

		foreach ( $fields as $field ) {
			if ( ! isset( $options[$group] ) ) {
				$options[$group]	=	array();
			}

			$options[$group][]		=	JHtml::_( 'select.option', $field->value, CBTxt::T( $field->text ) );
		}

		return $options;
	}
}