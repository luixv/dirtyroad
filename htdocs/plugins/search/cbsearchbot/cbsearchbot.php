<?php
/**
 * Community Builder (TM)
 * @version $Id: $
 * @package CommunityBuilder
 * @copyright (C) 2004-2018 www.joomlapolis.com / Lightning MultiCom SA - and its licensors, all rights reserved
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
 */

use CBLib\Registry\Registry;
use CBLib\Language\CBTxt;
use CBLib\Application\Application;
use CB\Database\Table\UserTable;
use CBLib\Registry\GetterInterface;

if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

class plgSearchcbsearchbot extends JPlugin
{

	/**
	 * @param object $subject
	 * @param array  $config
	 */
	public function __construct( &$subject, $config )
	{
		global $_PLUGINS;

		parent::__construct( $subject, $config );

		static $CB_loaded	=	0;

		if ( ! $CB_loaded++ ) {
			if ( ( ! file_exists( JPATH_SITE . '/libraries/CBLib/CBLib/Core/CBLib.php' ) ) || ( ! file_exists( JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php' ) ) ) {
				echo 'CB not installed'; return;
			}

			include_once( JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php' );

			cbimport( 'cb.html' );
			cbimport( 'language.front' );
			cbimport( 'cb.lists' );

			$_PLUGINS->loadPluginGroup( 'user' );
		}
	}

	public function onContentSearchAreas()
	{
		static $areas	=	null;

		if ( ! isset( $areas ) ) {
			$area		=	$this->params->get( 'search_area', 'Users' );

			if ( ! $area ) {
				$area	=	'Users';
			}

			$areas		=	array( 'cb' => CBTxt::T( $area ) );
		}

		return $areas;
	}

	/**
	 * @param         $text
	 * @param  string $phrase
	 * @param  string $ordering
	 * @param  null   $areas
	 * @return array
	 */
	public function onContentSearch( $text, $phrase = '', $ordering = '', $areas = null )
	{
		global $_CB_framework, $_CB_database, $_PLUGINS;

		if ( is_array( $areas ) ) {
			if ( ! array_intersect( $areas, array_keys( $this->onContentSearchAreas() ) ) ) {
				return array();
			}
		}

		$text								=	trim( $text );

		if ( $text == '' ) {
			return array();
		}

		$searchFields						=	$this->params->get( 'search_fields', array( '41', '42', '46', '47', '48', '50' ) );

		if ( ! is_array( $searchFields ) ) {
			$searchFields					=	explode( '|*|', $searchFields );
		}

		if ( ! $searchFields ) {
			return array();
		}

		$resultTitle						=	$this->params->get( 'result_title', '[formatname]' );
		$resultText							=	$this->params->get( 'result_text', '[formatname]\'s profile page' );
		$resultsLimit						=	(int) $this->params->get( 'result_limit', 50 );
		$resultsLinks						=	(int) $this->params->get( 'result_link', 0 );
		$results							=	array();

		$cbUser								=	CBuser::getMyInstance();
		$user								=	$cbUser->getUserData();
		$tabs								=	$cbUser->_getCbTabs();
		$fields								=	$tabs->_getTabFieldsDb( null, $user, 'list' );

		$queryTables						=	array( 'u'	=> '#__users AS u' );
		$queryJoins							=	array( 'ue'	=> 'LEFT JOIN #__comprofiler AS ue ON u.id = ue.id' );
		$queryWhere							=	array( 'u.block = 0', 'ue.approved = 1', 'ue.confirmed = 1' );
		$queryOrdering						=	array();

		switch ( $ordering ) {
			case 'alpha':
				$queryOrdering[]			=	'u.' . $_CB_database->NameQuote( $this->params->get( 'ordering_alpha', 'name' ) ) . ' ASC';
				break;
			case 'popular':
				$queryOrdering[]			=	'ue.' . $_CB_database->NameQuote( 'hits' ) . ' DESC';
				break;
			case 'oldest':
				$queryOrdering[]			=	'u.' . $_CB_database->NameQuote( 'registerDate' ) . ' ASC';
				break;
			default:
				$queryOrdering[]			=	'u.' . $_CB_database->NameQuote( 'registerDate' ) . ' DESC';
				break;
		}

		$searchQuery						=	new cbSqlQueryPart();
		$searchQuery->tag					=	'where';
		$searchQuery->type					=	'sql:operator';
		$searchQuery->operator				=	'OR';

		if ( $phrase == 'all' ) {
			$searchMode						=	'all';
		} elseif ( $phrase == 'exact' ) {
			$searchMode						=	'is';
		} else {
			$searchMode						=	'any';
		}

		foreach ( $fields as $k => $field ) {
			$columns						=	$field->getTableColumns();

			if ( ( ! count( $columns ) ) || ( ! in_array( $field->get( 'fieldid' ), $searchFields ) ) ) {
				unset( $fields[$k] );
			} else {
				if ( ! ( $field->params instanceof Registry ) ) {
					$field->params			=	new Registry( $field->params );
				}

				$postdata					=	array();
				$searchVals					=	new stdClass();

				foreach ( $columns as $col ) {
					$postdata[$col]			=	$text;
					$searchVals->$col		=	$text;
				}

				$searchSqlQuery				=	new cbSqlQueryPart();
				$searchSqlQuery->tag		=	'where';
				$searchSqlQuery->type		=	'sql:operator';
				$searchSqlQuery->operator	=	'AND';
				$searchSql					=	array();

				$_PLUGINS->trigger( 'onBeforebindSearchCriteria', array( &$field, &$searchVals, &$postdata, 1, 'search' ) );

				foreach ( $columns as $col ) {
					$sql					=	new cbSqlQueryPart();
					$sql->tag				=	'column';
					$sql->name				=	$col;
					$sql->table				=	$field->get( 'table' );
					$sql->type				=	'sql:field';
					$sql->operator			=	'=';
					$sql->value				=	$text;
					$sql->valuetype			=	'const:string';
					$sql->searchmode		=	$searchMode;

					$searchSql[]			=	$sql;
				}

				$_PLUGINS->trigger( 'onAfterbindSearchCriteria', array( &$field, &$searchVals, &$postdata, 1, 'search', &$searchSql ) );

				if ( $searchSql ) {
					$searchSqlQuery->addChildren( $searchSql );

					$searchQuery->addChildren( array( $searchSqlQuery ) );
				}
			}
		}

		if ( ! $fields ) {
			return array();
		}

		$tables								=	array( '#__comprofiler' => 'ue', '#__users' => 'u' );
		$whereFields						=	$searchQuery->reduceSqlFormula( $tables, $queryJoins, true );

		if ( $whereFields ) {
			$queryWhere[]					=	'(' . $whereFields . ')';
		} else {
			return array();
		}

		if ( Application::MyUser()->isGlobalModerator() ) {
			if ( ! (int) $this->params->get( 'search_blocked', 0 ) ) {
				$queryWhere[]				=	'u.' . $_CB_database->NameQuote( 'block' ) . ' = 0';
			}

			if ( ! (int) $this->params->get( 'search_banned', 1 ) ) {
				$queryWhere[]				=	'ue.' . $_CB_database->NameQuote( 'banned' ) . ' = 0';
			}

			if ( ! (int) $this->params->get( 'search_unapproved', 0 ) ) {
				$queryWhere[]				=	'ue.' . $_CB_database->NameQuote( 'approved' ) . ' = 1';
			}

			if ( ! (int) $this->params->get( 'search_unconfirmed', 0 ) ) {
				$queryWhere[]				=	'ue.' . $_CB_database->NameQuote( 'confirmed' ) . ' = 1';
			}
		} else {
			$queryWhere[]					=	'u.' . $_CB_database->NameQuote( 'block' ) . ' = 0';
			$queryWhere[]					=	'ue.' . $_CB_database->NameQuote( 'banned' ) . ' = 0';
			$queryWhere[]					=	'ue.' . $_CB_database->NameQuote( 'approved' ) . ' = 1';
			$queryWhere[]					=	'ue.' . $_CB_database->NameQuote( 'confirmed' ) . ' = 1';
		}

		$searchExclude						=	$this->params->get( 'search_exclude', null );

		if ( $searchExclude ) {
			$queryWhere[]					=	'u.id NOT IN ' . $_CB_database->safeArrayOfIntegers( explode( ',', $searchExclude ) );
		}

		$query								=	'SELECT u.*'
											.	"\n FROM " . implode( ', ', $queryTables )
											.	( count( $queryJoins ) ? "\n " . implode( "\n ", $queryJoins ) : '' )
											.	( count( $queryWhere ) ? "\n WHERE " . implode( ' AND ', $queryWhere ) : '' )
											.	( count( $queryOrdering ) ? "\n ORDER BY " . implode( ', ', $queryOrdering ) : '' );
		if ( $resultsLimit ) {
			$_CB_database->setQuery( $query, 0, $resultsLimit );
		} else {
			$_CB_database->setQuery( $query );
		}
		$rows								=	$_CB_database->loadObjectList( null, '\CB\Database\Table\UserTable', array( $_CB_database ) );

		$_PLUGINS->trigger( 'search_onBeforeResults', array( &$rows, &$results, $fields, $text ) );

		/** @var $rows UserTable[] */
		foreach ( $rows as $row ) {
			$cbUserRow						=	CBuser::getInstance( $row->get( 'id', 0, GetterInterface::INT ), false );

			$result							=	new stdClass();
			$result->href					=	$_CB_framework->userProfileUrl( $row->get( 'id', 0, GetterInterface::INT ), false );
			$result->title					=	$cbUserRow->replaceUserVars( $resultTitle );
			$result->text					=	$cbUserRow->replaceUserVars( $resultText, false );
			$result->created				=	$row->get( 'registerDate', null, GetterInterface::STRING );
			$result->browsernav				=	$resultsLinks;
			$result->section				=	0;

			$_PLUGINS->trigger( 'search_onAfterResult', array( $row, &$result, &$results ) );

			if ( ! $result ) {
				continue;
			}

			$results[]						=	$result;
		}

		return $results;
	}
}