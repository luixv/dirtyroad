<?php
/**
* Community Builder (TM)
* @version $Id: $
* @package CommunityBuilder
* @copyright (C) 2004-2021 www.joomlapolis.com / Lightning MultiCom SA - and its licensors, all rights reserved
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

use CBLib\Language\CBTxt;
use CB\Database\Table\TabTable;
use CB\Database\Table\PluginTable;
use CB\Database\Table\UserTable;

if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

/**
 * Class HTML_cbforumsTabFavs
 * CB Forum Favorites Tab Template
 */
class HTML_cbforumsTabFavs
{
	/**
	 * @param  stdClass[]   $rows       Rows to show
	 * @param  cbPageNav    $pageNav    Page Navigation
	 * @param  boolean      $searching  Are we searching currently ?
	 * @param  string[]     $input      Inputs to show
	 * @param  UserTable    $viewer     Viewing User
	 * @param  UserTable    $user       Viewed at User
	 * @param  TabTable     $tab        Current Tab
	 * @param  PluginTable  $plugin     Current Plugin
	 * @return string
	 */
	static public function showFavorites( $rows, $pageNav, $searching, $input, $viewer, $user, $tab, /** @noinspection PhpUnusedParameterInspection */ $plugin )
	{
		global $_CB_framework;

		$tabPaging			=	$tab->params->get( 'tab_favs_paging', 1 );
		$canSearch			=	( $tab->params->get( 'tab_favs_search', 1 ) && ( $searching || $pageNav->total ) );

		$return				=	'<div class="tab-content forumsFavsTab">'
							.		'<form action="' . $_CB_framework->userProfileUrl( $user->id, true, $tab->tabid ) . '" method="post" name="forumFavsForm" id="forumFavsForm" class="m-0 forumFavsForm">';

		if ( $canSearch ) {
			$return			.=			'<div class="row no-gutters mb-2 forumsHeader">'
							.				'<div class="col-12 offset-sm-8 col-sm-4 text-sm-right">'
							.					'<div class="input-group">'
							.						'<div class="input-group-prepend">'
							.							'<span class="input-group-text"><span class="fa fa-search"></span></span>'
							.						'</div>'
							.						$input['search']
							.					'</div>'
							.				'</div>'
							.			'</div>';
		}

		$return				.=			'<div class="table-responsive forumsContainer">'
							.				'<table class="table table-hover mb-0">'
							.					'<thead>'
							.						'<tr>'
							.							'<th style="width: 50%;">' . CBTxt::T( 'Subject' ) . '</th>'
							.							'<th style="width: 25%;" class="d-none d-sm-table-cell">' . CBTxt::T( 'Category' ) . '</th>'
							.							'<th style="width: 24%;" class="d-none d-sm-table-cell">' . CBTxt::T( 'Date' ) . '</th>'
							.							'<th style="width: 1%;" class="text-right">' . ( $rows ? '<a href="javascript: void(0);" onclick="cbjQuery.cbconfirm( \'' . addslashes( CBTxt::T( 'Are you sure you want to delete all favorites?' ) ) . '\' ).done( function() { window.location.href = \'' . addslashes( $_CB_framework->userProfileUrl( $user->id, true, $tab->tabid, 'html', 0, array( 'forums_unfav' => 'all' ) ) ) . '\'; })" title="' . htmlspecialchars( CBTxt::T( 'Delete All' ) ) . '"><span class="fa fa-trash-o"></span></a>' : '&nbsp;' ) . '</th>'
							.						'</tr>'
							.					'</thead>'
							.					'<tbody>';

		if ( $rows ) foreach ( $rows as $row ) {
			$return			.=						'<tr>'
							.							'<td style="width: 50%;"><a href="' . ( isset( $row->url ) ? $row->url : cbforumsModel::getForumURL( $row->category_id, $row->id ) ) . '">' . cbforumsClass::cleanPost( $row->subject ) . '</a></td>'
							.							'<td style="width: 25%;" class="d-none d-sm-table-cell"><a href="' . ( isset( $row->category_url ) ? $row->category_url : cbforumsModel::getForumURL( $row->category_id ) ) . '">' . cbforumsClass::cleanPost( $row->category_name ) . '</a></td>'
							.							'<td style="width: 24%;" class="d-none d-sm-table-cell">' . cbFormatDate( date( 'Y-m-d H:i:s', $row->date ) ) . '</td>'
							.							'<td style="width: 1%;" class="text-right"><a href="javascript: void(0);" onclick="cbjQuery.cbconfirm( \'' . addslashes( CBTxt::T( 'Are you sure you want to delete this favorite?' ) ) . '\' ).done( function() { window.location.href = \'' . addslashes( $_CB_framework->userProfileUrl( $user->id, true, $tab->tabid, 'html', 0, array( 'forums_unfav' => $row->id ) ) ) . '\'; })" title="' . htmlspecialchars( CBTxt::T( 'Delete' ) ) . '"><span class="fa fa-trash-o"></span></a></td>'
							.						'</tr>';
		} else {
			$return			.=						'<tr>'
							.							'<td colspan="4">';

			if ( $searching ) {
				$return		.=								CBTxt::T( 'No favorite search results found.' );
			} else {
				if ( $viewer->id == $user->id ) {
					$return	.=								CBTxt::T( 'You have no favorites.' );
				} else {
					$return	.=								CBTxt::T( 'This user has no favorites.' );
				}
			}

			$return			.=							'</td>'
							.						'</tr>';
		}

		$return				.=					'</tbody>';

		if ( $tabPaging && ( $pageNav->total > $pageNav->limit ) ) {
			$return			.=					'<tfoot>'
							.						'<tr>'
							.							'<td colspan="4" class="text-center">'
							.								$pageNav->getListLinks()
							.							'</td>'
							.						'</tr>'
							.					'</tfoot>';
		}

		$return				.=				'</table>'
							.			'</div>'
							.			$pageNav->getLimitBox( false )
							.		'</form>'
							.	'</div>';

		return $return;
	}
}
