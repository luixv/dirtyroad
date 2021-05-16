<?php
/**
* Community Builder (TM)
* @version $Id: $
* @package CommunityBuilder
* @copyright (C) 2004-2021 www.joomlapolis.com / Lightning MultiCom SA - and its licensors, all rights reserved
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

use CBLib\Application\Application;
use CBLib\Language\CBTxt;
use CBLib\Registry\GetterInterface;

if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

class CBProfileView_html_default extends cbProfileView
{

	/**
	 * Renders by ECHO the profile view
	 *
	 * @return void
	 */
	protected function _renderProfile( )
	{
		$return							=	null;

		if ( isset( $this->userViewTabs['cb_head'] ) && $this->userViewTabs['cb_head'] ) {
			$return						.=	'<div class="cbPosHead">'
										.		$this->userViewTabs['cb_head']
										.	'</div>';
		}

		$canvasMenu						=	( isset( $this->userViewTabs['canvas_menu'] )				? $this->userViewTabs['canvas_menu']				: null );

		$canvasBackground				=	( isset( $this->userViewTabs['canvas_background'] )			? $this->userViewTabs['canvas_background']			: null );
		$canvasPhoto					=	( isset( $this->userViewTabs['canvas_photo'] )				? $this->userViewTabs['canvas_photo']				: null );
		$canvasInfo						=	( isset( $this->userViewTabs['canvas_info'] )				? $this->userViewTabs['canvas_info']				: null );

		$canvasTitle					=	( isset( $this->userViewTabs['canvas_title'] )				? $this->userViewTabs['canvas_title']				: null );
		$canvasTitle					.=	( isset( $this->userViewTabs['canvas_title_top'] )			? $this->userViewTabs['canvas_title_top']			: null ); // For B/C
		$canvasTitle					.=	( isset( $this->userViewTabs['canvas_title_middle'] )		? $this->userViewTabs['canvas_title_middle']		: null ); // For B/C
		$canvasTitle					.=	( isset( $this->userViewTabs['canvas_title_bottom'] )		? $this->userViewTabs['canvas_title_bottom']		: null ); // For B/C

		$canvasStats					=	( isset( $this->userViewTabs['canvas_stats'] )				? $this->userViewTabs['canvas_stats']				: null );
		$canvasStats					.=	( isset( $this->userViewTabs['canvas_stats_top'] )			? $this->userViewTabs['canvas_stats_top']			: null ); // For B/C
		$canvasStats					.=	( isset( $this->userViewTabs['canvas_stats_middle'] )		? $this->userViewTabs['canvas_stats_middle']		: null ); // For B/C
		$canvasStats					.=	( isset( $this->userViewTabs['canvas_stats_bottom'] )		? $this->userViewTabs['canvas_stats_bottom']		: null ); // For B/C

		$canvasHeader					=	( $canvasBackground || $canvasStats || $canvasPhoto || $canvasTitle );
		$canvasMain						=	( isset( $this->userViewTabs['canvas_main_middle'] )		? $this->userViewTabs['canvas_main_middle']			: null );

		if ( $canvasMenu || $canvasHeader || $canvasMain ) {
			if ( $canvasMenu ) {
				$return					.=	'<div class="cbPosCanvasMenu cbCanvasLayoutMenu">'
										.		$canvasMenu
										.	'</div>';
			}

			if ( $canvasHeader ) {
				$return					.=	'<div class="position-relative no-overflow border' . ( $canvasMenu ? ' border-top-0' : null ) . ( $canvasMain ? ' border-bottom-0' : null ) . ' cbPosCanvas cbCanvasLayout">';

				if ( $canvasBackground ) {
					$return				.=		'<div class="position-relative bg-light row no-gutters align-items-lg-end cbPosCanvasTop cbCanvasLayoutTop">'
										.			'<div class="position-absolute col-12 cbPosCanvasBackground cbCanvasLayoutBackground">'
										.				$canvasBackground
										.			'</div>'
										.		'</div>';
				}

				if ( $canvasPhoto || $canvasTitle || $canvasStats || $canvasInfo ) {
					$return				.=		'<div class="position-relative row no-gutters align-items-end bg-white' . ( $canvasBackground ? ' border-top' : null ) . ( ! $canvasMain ? ' border-bottom' : null ) . ( ! $canvasPhoto ? ' p-2' : null ) . ' cbPosCanvasBottom cbCanvasLayoutBottom">';

					if ( $canvasPhoto ) {
						$return			.=			'<div class="' . ( ! $canvasBackground ? 'col-12 col-sm-3 mh-none' : 'col-4 col-sm-3' ) . '">'
										.				'<div class="' . ( $canvasBackground ? 'position-absolute' : 'p-2' ) . ' cbPosCanvasPhoto cbCanvasLayoutPhoto">'
										.					$canvasPhoto
										.				'</div>'
										.			'</div>'
										.			'<div class="' . ( ! $canvasBackground ? 'col-12 col-sm-9 align-self-end' : 'col-8 col-sm-9' ) . '">'
										.				'<div class="p-2">';
					}

					if ( $canvasTitle || $canvasInfo ) {
						$return			.=					'<div class="row no-gutters">';

						if ( $canvasTitle ) {
							$return		.=						'<div class="order-0 col text-primary text-large font-weight-bold cbPosCanvasTitle cbCanvasLayoutTitle">'
										.							$canvasTitle
										.						'</div>';
						}

						if ( $canvasInfo ) {
							$return		.=						'<div class="ml-0 ml-sm-1 mt-2 mt-sm-0 order-last order-sm-1 col-12 col-sm-auto cbPosCanvasInfo cbCanvasLayoutInfo">'
										.							$canvasInfo
										.						'</div>';
						}

						$return			.=					'</div>';
					}

					if ( $canvasStats ) {
						$return			.=					'<div class="mt-2 mt-sm-1 text-muted text-overflow text-small cbPosCanvasStats cbCanvasLayoutCounters">'
										.						$canvasStats
										.					'</div>';
					}

					if ( $canvasPhoto ) {
						$return			.=				'</div>'
										.			'</div>';
					}

					$return				.=		'</div>';
				}

				$return					.=	'</div>';
			}

			if ( $canvasMain ) {
				$return					.=	'<div class="cbPosCanvasMain cbCanvasLayoutMain">'
										.		$canvasMain
										.	'</div>';
			}
		}

		$mainLeft						=	( isset( $this->userViewTabs['cb_left'] ) ? $this->userViewTabs['cb_left'] : null );
		$mainMiddle						=	( isset( $this->userViewTabs['cb_middle'] ) ? $this->userViewTabs['cb_middle'] : null );
		$mainRight						=	( isset( $this->userViewTabs['cb_right'] ) ? $this->userViewTabs['cb_right'] : null );

 		if ( $mainLeft || $mainMiddle || $mainRight ) {
			if ( $return ) {
				$return					.=	'<div class="pt-2 pb-2 cbPosSeparator"></div>';
			}

			$return						.=	'<div class="row no-gutters cbPosTop">';

			if ( $mainLeft ) {
				$leftSize				=	Application::Config()->get( 'mainLayoutLeftSize', 3, GetterInterface::INT );

				$return					.=		'<div class="col-sm' . ( $leftSize ? '-' . $leftSize : null ) . ( $mainMiddle || $mainRight ? ' pr-sm-2' : null ) . ' cbPosLeft">'
										.			$mainLeft
										.		'</div>';
			}

			if ( $mainMiddle ) {
				$middleSize				=	Application::Config()->get( 'mainLayoutMiddleSize', 0, GetterInterface::INT );

				$return					.=		'<div class="col-sm' . ( $middleSize ? '-' . $middleSize : null ) . ' cbPosMiddle">'
										.			$mainMiddle
										.		'</div>';
			}

			if ( $mainRight ) {
				$rightSize				=	Application::Config()->get( 'mainLayoutRightSize', 3, GetterInterface::INT );

				$return					.=		'<div class="col-sm' . ( $rightSize ? '-' . $rightSize : null ) . ( $mainMiddle || $mainLeft ? ' pl-sm-2' : null ) . ' cbPosRight">'
										.			$mainRight
										.		'</div>';
			}

			$return						.=	'</div>';
		}

		if ( isset( $this->userViewTabs['cb_tabmain'] ) ) {
			if ( $return ) {
				$return					.=	'<div class="pt-2 pb-2 cbPosSeparator"></div>';
			}

			$return						.=	'<div class="cbPosTabMain">'
										.		$this->userViewTabs['cb_tabmain']
										.	'</div>';
		}

		if ( isset( $this->userViewTabs['cb_underall'] ) ) {
			if ( $return ) {
				$return					.=	'<div class="pt-2 pb-2 cbPosSeparator"></div>';
			}

			$return						.=	'<div class="cbPosUnderAll">'
										.		$this->userViewTabs['cb_underall']
										.	'</div>';
		}

		$line							=	null;
		$indexes						=	array_keys( $this->userViewTabs );

		if ( $indexes ) foreach ( $indexes as $k => $v ) {
			if ( $v && $v[0] == 'L' ) {
				$L						=	$v[1];

				if ( $line === null ) {
					$line				=	$k;
				}

				if ( ! ( isset( $indexes[$k + 1] ) && ( $indexes[$k + 1][1] == $L ) ) ) {
					if ( $return ) {
						$return			.=	'<div class="pt-2 pb-2 cbPosSeparator"></div>';
					}

					$return				.=	'<div class="row no-gutters cbPosLine cbPosLine' . $L . '">';

					for ( $i = $line ; $i <= $k ; $i++ ) {
						$C				=	$indexes[$i][3];

						$return			.=		'<div class="col-sm cbPosLineCol cbPosLineCol' . $C . '">'
										.			$this->userViewTabs[$indexes[$i]]
										.		'</div>';
					}

					$return				.=	'</div>';

					$line				=	null;
				}
			}
		}

		echo $return;
	}

	/**
	 * Renders by ECHO the profile edit view
	 *
	 * @return void
	 */
	protected function _renderEdit( )
	{
		$return			=	null;

		if ( $this->topIcons ) {
			$return		.=	'<div class="' . ( $this->tabContent ?  ' mb-3' : null ) . ' cbIconsTop">'
						.		$this->topIcons
						.	'</div>';
		}

		$return			.=	$this->tabContent
						.	'<div class="row no-gutters' . ( $this->bottomIcons ?  ' mb-3' : null ) . ' cbProfileEditButtons">'
						.		'<div class="offset-sm-3 col-sm-9">'
						.			'<input class="btn btn-primary btn-sm-block cbProfileEditSubmit" type="submit" id="cbbtneditsubmit" value="' . $this->submitValue . '"' . cbValidator::getSubmitBtnHtmlAttributes() . ' />'
						.			' <input class="btn btn-secondary btn-sm-block cbProfileEditCancel" type="button" id="cbbtncancel" name="btncancel" value="' . $this->cancelValue . '" />'
						.		'</div>'
						.	'</div>';

		if ( $this->bottomIcons ) {
			$return		.=	'<div class="cbIconsBottom">'
						.		$this->bottomIcons
						.	'</div>';
		}

		echo $return;
	}
}

class CBRegisterFormView_html_default extends cbRegistrationView
{
	/** @var string  */
	public $regLayout	=	'flat';
	/** @var null|string  */
	public $regCanvas	=	null;

	/**
	 * CBRegisterFormView_html_default constructor.
	 */
	public function __construct()
	{
		global $_CB_framework, $ueConfig;

		$this->regLayout			=	( isset( $ueConfig['reg_layout'] ) ? $ueConfig['reg_layout'] : 'flat' );
		$titleCanvas				=	( isset( $ueConfig['reg_title_img'] ) ? $ueConfig['reg_title_img'] : 'none' );

		if ( $titleCanvas != 'none' ) {
			if ( in_array( $titleCanvas, array( 'general' ) ) ) {
				$this->regCanvas	=	selectTemplate() . 'images/title/' . $titleCanvas . '.jpg';
			} else {
				$this->regCanvas	=	$_CB_framework->getCfg( 'live_site' ) . '/images/' . $titleCanvas;
			}
		} else {
			$this->regCanvas		=	null;
		}
	}

	/**
	 * Renders the registration head part view
	 *
	 * @return string  HTML
	 */
	private function _renderRegistrationHead( )
	{
		global $_CB_framework;

		$return					=	null;

		if ( $this->moduleContent ) {
			$return				.=	'<div class="cbRegistrationContainer">'
								.		'<div class="cbRegistrationLogin">'
								.			$this->moduleContent
								.		'</div>';
		}

		$pageClass				=	$_CB_framework->getMenuPageClass();

		$return					.=	'<div class="cb_template cb_template_' . selectTemplate( 'dir' ) . ' cbRegistration ' . ( $this->regLayout == 'tabbed' ? 'cbRegistrationTabbed' : ( $this->regLayout == 'stepped' ? 'cbRegistrationStepped' : 'cbRegistrationFlat' ) ) . ( $this->regCanvas ? ' cbRegistrationCanvas' : null ) . ( $pageClass ? ' ' . htmlspecialchars( $pageClass ) : null ) . '">';

		if ( is_array( $this->triggerResults ) ) {
			$return				.=		implode( '', $this->triggerResults );
		}

		if ( $this->registerTitle || $this->introMessage ) {
			if ( $this->regCanvas ) {
				$return			.=		'<div class="position-relative no-overflow border rounded-top mb-3 cbCanvasLayout cbCanvasLayoutMd cbRegistrationHeader">'
								.			'<div class="position-relative bg-light row no-gutters align-items-end cbCanvasLayoutTop cbRegistrationHeaderInner">'
								.				'<div class="position-absolute col-12 cbCanvasLayoutBackground cbRegistrationHeaderBackground">'
								.					'<div class="cbCanvasLayoutBackgroundImage" style="background-image: url(' . $this->regCanvas . ')"></div>'
								.				'</div>'
								.				'<div class="p-2 col-12 cbCanvasLayoutInfo">';

				if ( $this->registerTitle ) {
					$return		.=					'<h3 class="cbRegistrationTitle">' . $this->registerTitle . '</h3>';
				}

				if ( $this->introMessage ) {
					$return		.=					'<div class="cbRegistrationIntro">'
								.						$this->introMessage
								.					'</div>';
				}

				$return			.=				'</div>'
								.			'</div>'
								.		'</div>';
			} else {
				if ( $this->registerTitle ) {
					$return		.=		'<div class="mb-3 border-bottom cb-page-header cbRegistrationHeader">';

					if ( $this->registerTitle ) {
						$return	.=			'<h3 class="m-0 p-0 mb-2 cb-page-header-title cbRegistrationTitle">' . $this->registerTitle . '</h3>';
					}

					if ( $this->introMessage ) {
						$return	.=			'<div class="mb-2 cb-page-header-description cbRegistrationIntro">'
								.				$this->introMessage
								.			'</div>';
					}

					$return		.=		'</div>';
				} elseif ( $this->introMessage ) {
					$return		.=		'<div class="mb-3 cb-page-header-description cbRegistrationIntro">'
								.			$this->introMessage
								.		'</div>';
				}
			}
		}

		if ( $this->topIcons ) {
			$return				.=		'<div class="mb-3 cbIconsTop">'
								.			$this->topIcons
								.		'</div>';
		}

		$return					.=		$this->regFormTag;

		return $return;
	}

	/**
	 * Renders by ECHO the registration view (divs mode)
	 *
	 * @return void
	 */
	protected function _renderdivs( )
	{
		$return			=	$this->_renderRegistrationHead()
						.			'<div id="registrationTable" class="cbRegistrationDiv">'
						.				$this->tabContent
						.				'<div class="row no-gutters cbRegistrationButtons">'
						.					'<div class="offset-sm-3 col-sm-9">'
						.						'<input type="submit" value="' . $this->registerButton . '" class="btn btn-primary btn-sm-block cbRegistrationSubmit"' . cbValidator::getSubmitBtnHtmlAttributes() . ' />'
						.					'</div>'
						.				'</div>'
						.			'</div>'
						.	$this->_renderRegistrationFooter();

		echo $return;
	}

	/**
	 * Renders by ECHO the registration view (table trs mode)
	 *
	 * @return void
	 */
	protected function _render( )
	{
		$return			=	$this->_renderRegistrationHead()
						.			'<table id="registrationTable" class="table table-hover m-0 cbRegistrationTable">'
						.				'<tbody>'
						.					$this->tabContent
						.					'<tr class="cbRegistrationButtonRow">'
						.						'<td>&nbsp;</td>'
						.						'<td>'
						.							'<input type="submit" value="' . $this->registerButton . '" class="btn btn-primary btn-sm-block cbRegistrationSubmit"' . cbValidator::getSubmitBtnHtmlAttributes() . ' />'
						.						'</td>'
						.					'</tr>'
						.				'</tbody>'
						.			'</table>'
						.	$this->_renderRegistrationFooter();

		echo $return;
	}

	/**
	 * Renders the registration view footer
	 *
	 * @return string  HTML
	 */
	private function _renderRegistrationFooter( )
	{
		$return			=		'</form>';

		if ( $this->bottomIcons ) {
			$return		.=		'<div class="mt-3 cbIconsBottom">'
						.			$this->bottomIcons
						.		'</div>';
		}

		if ( $this->conclusionMessage ) {
			$return		.=		'<div class="mt-3' . ( $this->regCanvas ? ' p-2 bg-light border rounded-bottom' : null ) . ' cbRegistrationConclusion">'
						.			$this->conclusionMessage
						.		'</div>';
		}

		if ( $this->moduleContent ) {
			$return		.=	'</div>';
		}

		$return			.=	'</div>';

		return $return;
	}
}

class CBListView_html_default extends cbListView
{
	/**
	 * Renders by ECHO the list view head
	 *
	 * @return void
	 */
	protected function _renderHead( )
	{
		global $_CB_framework;

		$headerRightColumn			=	( ( ( count( $this->lists ) > 0 ) && $this->allowListSelector ) || ( $this->searchTabContent && ( ( ( ! $this->searchResultDisplaying ) || $this->searchCollapsed ) || ( $this->searchResultDisplaying && $this->allowListAll ) ) ) );

		$return						=	( $this->listTitleHtml ? '<div class="mb-3 border-bottom cb-page-header cbUserListTitle"><h3 class="m-0 p-0 mb-2 cb-page-header-title">' . $this->listTitleHtml . '</h3></div>' : null )
									.	'<div class="cbUserListHead">'
									.		'<div class="row no-gutters cbColumns">'
									.			'<div class="' . ( $headerRightColumn ? 'col-sm-9 pr-sm-3 cbColumn9' : 'col-sm-12 cbColumn12' ) . '">'
									.				( trim( $this->listDescription ) != '' ? '<div class="cbUserListDescription">' . $this->listDescription . '</div>' : null )
									.				'<div class="cbUserListResultCount">';

		if ( $this->totalIsAllUsers ) {
														// CBTxt::Th( 'SITENAME_HAS_TOTAL_REGISTERED_MEMBERS', '[SITENAME] has %%TOTAL%% registered member|[SITENAME] has %%TOTAL%% registered members', array( '[SITENAME]' => $_CB_framework->getCfg( 'sitename' ), '[title]' => $this->listTitleHtml, '%%TOTAL%%' => $this->total ) )
			$return					.=					CBTxt::Th( 'USERLIST_' . (int) $this->listId . '_TOTAL_REGISTERED_MEMBERS SITENAME_HAS_TOTAL_REGISTERED_MEMBERS', '[SITENAME] has %%TOTAL%% registered member|[SITENAME] has %%TOTAL%% registered members', array( '[SITENAME]' => $_CB_framework->getCfg( 'sitename' ), '[title]' => $this->listTitleHtml, '%%TOTAL%%' => $this->total ) );
		} else {
														// CBTxt::Th( 'USERS_COUNT_MEMBERS', '%%USERS_COUNT%% member|%%USERS_COUNT%% members', array( '[SITENAME]' => $_CB_framework->getCfg( 'sitename' ), '[title]' => $this->listTitleHtml, '%%USERS_COUNT%%' => $this->total ) )
			$return					.=					CBTxt::Th( 'USERLIST_' . (int) $this->listId . '_COUNT_MEMBERS USERS_COUNT_MEMBERS', '%%USERS_COUNT%% member|%%USERS_COUNT%% members', array( '[SITENAME]' => $_CB_framework->getCfg( 'sitename' ), '[title]' => $this->listTitleHtml, '%%USERS_COUNT%%' => $this->total ) );
		}

		$return						.=				'</div>'
									.			'</div>';

		if ( $headerRightColumn ) {
			$return					.=			'<div class="col-sm-3 cbColumn3">'
									.				'<div class="text-right cbUserListChanger">';

			if ( ( count( $this->lists ) > 0 ) && $this->allowListSelector ) foreach ( $this->lists as $keyName => $listName ) {
				$return				.=					'<div class="cbUserListChangeItem cbUserList' . $keyName . '">' . $listName . '</div>';
			}

			if ( $this->searchTabContent ) {
				if ( ( ! $this->searchResultDisplaying ) || $this->searchCollapsed ) {
					$return			.=					'<div class="' . ( ( count( $this->lists ) > 0 ) && $this->allowListSelector ? 'mt-2 ' : null ) . 'cbUserListSearchButtons cbUserListsSearchTrigger">'
																																					// CBTxt::Th( 'UE_SEARCH_USERS', 'Search Users' )
									.						'<button type="button" class="btn btn-secondary btn-block cbUserListsSearchButton">' . CBTxt::Th( 'USERLIST_' . (int) $this->listId . '_SEARCH_USERS UE_SEARCH_USERS', 'Search Users', array( '[title]' => $this->listTitleHtml ) ) . ' <span class="fa fa-caret-down"></span></button>'
									.					'</div>';
				}

				if ( $this->searchResultDisplaying && $this->allowListAll ) {
					$return			.=					'<div class="' . ( ( ( count( $this->lists ) > 0 ) && $this->allowListSelector ) || ( ( ! $this->searchResultDisplaying ) || $this->searchCollapsed ) ? 'mt-2 ' : null ) . 'cbUserListSearchButtons cbUserListListAll">'
									.						'<button type="button" class="btn btn-secondary btn-block cbUserListListAllButton" onclick="window.location=\'' . $this->ue_base_url . '\'; return false;">' . CBTxt::Th( 'UE_LIST_ALL', 'List all' ) . '</button>'
									.					'</div>';
				}
			}

			$return					.=				'</div>'
									.			'</div>';
		}

		$return						.=		'</div>'
									.	'</div>';

		if ( $this->searchTabContent ) {
			$return					.=	'<div class="mt-3 cbUserListSearch">'
									.		( $this->searchCriteriaTitleHtml ? '<div class="mb-3 border-bottom cb-page-header cbUserListSearchTitle"><h3 class="m-0 p-0 mb-2 cb-page-header-title">' . $this->searchCriteriaTitleHtml . '</h3></div>' : null )
									.		'<div class="cbUserListSearchFields">'
									.			$this->searchTabContent
									.			'<div class="row no-gutters cbUserListSearchButtons">'
									.				'<div class="offset-sm-3 col-sm-9">'
											 																					// CBTxt::Th( 'UE_FIND_USERS', 'Find Users' )
									.					'<input type="submit" class="btn btn-primary btn-sm-block cbUserlistSubmit" value="' . CBTxt::Th( 'USERLIST_' . (int) $this->listId . '_FIND_USERS UE_FIND_USERS', 'Find Users', array( '[title]' => $this->listTitleHtml ) ) . '"' . cbValidator::getSubmitBtnHtmlAttributes() . ' />';

			if ( $this->searchMode == 0 ) {
				$return				.=					' <input type="button" class="btn btn-secondary btn-sm-block cbUserlistCancel" value="' . htmlspecialchars( CBTxt::Th( 'UE_CANCEL', 'Cancel' ) ) . '" />';
			}

			$return					.=				'</div>'
									.			'</div>'
									.		'</div>';

			if ( $this->searchResultDisplaying && $this->searchResultsTitleHtml ) {
				$return				.=		( $this->searchCriteriaTitleHtml ? '<div class="mb-3 border-bottom cb-page-header searchCriteriaTitleHtml"><h3 class="m-0 p-0 mb-2 cb-page-header-title">' . $this->searchResultsTitleHtml . '</h3></div>' : null );
			}

			$return					.=	'</div>';
		}

		echo $return;
	}

	/**
	 * Renders by ECHO the list view body
	 *
	 * @return void
	 */
	protected function _renderBody( )
	{
		global $ueConfig;

		$formatting						=	( isset( $ueConfig['use_divs'] ) && ( ! $ueConfig['use_divs'] ) ? 'table' : 'divs' );
		$layout							=	( ( $formatting == 'divs' ) && ( $this->layout == 'grid' ) ? 'grid' : 'list' );
		$columnCount					=	count( $this->columns );
		$hasCanvas						=	false;

		if ( $columnCount && isset( $this->columns[0]->fields ) ) {
			foreach ( $this->columns[0]->fields as $field ) {
				if ( isset( $field['fieldid'] ) && ( (int) $field['fieldid'] == 17 ) ) {
					$hasCanvas			=	true;
				}
			}
		}

		if ( $formatting == 'divs' ) {
			$return						=	'<div id="cbUserTable" class="mt-3' . ( $layout == 'grid' ? ' row no-gutters' : null ) . ' cbUserListDiv ' . ( $layout == 'grid' ? 'cbUserListLayoutGrid' : 'cbUserListLayoutList' ) . ' cbUserListT_' . $this->listId . ( $hasCanvas ? ' cbUserListCanvas' : null ) . '" role="table">';
		} else {
			$return						=	'<table id="cbUserTable" class="table table-hover mt-3 cbUserListTable cbUserListLayoutList cbUserListT_' . $this->listId . ( $hasCanvas ? ' cbUserListCanvas' : null ) . '">'
										.		'<thead>';
		}

		if ( $columnCount && ( $layout != 'grid' ) ) {
			if ( $formatting == 'divs' ) {
				$return					.=			'<div class="row no-gutters cbColumns cbUserListHeader" role="row">';
			} else {
				$return					.=			'<tr class="sectiontableheader cbUserListHeader">';
			}

			foreach ( $this->columns as $index => $column ) {
				if ( $formatting == 'divs' ) {
					$return				.=				'<div class="col-sm' . ( $column->size ? '-' . $column->size : null ) . ' p-2 font-weight-bold cbColumn' . $column->size . ' cbUserListHeaderCol' . ( $index + 1 ) . ( $column->cssclass ? ' ' . $column->cssclass : null ) . '" role="columnheader">' . $column->titleRendered . '</div>';
				} else {
					$return				.=				'<th class="cbUserListHeaderCol' . ( $index + 1 ) . ( $column->cssclass ? ' ' . $column->cssclass : null ) . '">' . $column->titleRendered . '</th>';
				}
			}

			if ( $formatting == 'divs' ) {
				$return					.=			'</div>';
			} else {
				$return					.=			'</tr>';
			}
		}

		if ( $formatting != 'divs' ) {
			$return						.=		'</thead>'
										.		'<tbody>';
		}

		$gridSize						=	4;

		if ( ( $layout == 'grid' ) && isset( $this->columns[0]->size ) ) {
			$gridSize					=	(int) $this->columns[0]->size;
		}

		$gridClass						=	' col-12 col-sm-6 col-md-4';

		switch ( $gridSize ) {
			case 0:
				$gridClass				=	' col-auto';
				break;
			case 1:
				$gridClass				=	' col-12 col-sm-6 col-md-4 col-lg-1';
				break;
			case 2:
				$gridClass				=	' col-12 col-sm-6 col-md-4 col-lg-2';
				break;
			case 3:
				$gridClass				=	' col-12 col-sm-6 col-md-3';
				break;
			case 5:
				$gridClass				=	' col-12 col-sm-5';
				break;
			case 6:
				$gridClass				=	' col-12 col-sm-6';
				break;
			case 7:
				$gridClass				=	' col-12 col-sm-7';
				break;
			case 8:
				$gridClass				=	' col-12 col-sm-8';
				break;
			case 9:
				$gridClass				=	' col-12 col-sm-9';
				break;
			case 10:
				$gridClass				=	' col-12 col-sm-10';
				break;
			case 11:
				$gridClass				=	' col-12 col-sm-11';
				break;
			case 12:
				$gridClass				=	' col-12';
				break;
		}

		$i								=	0;
		$col							=	0;

		if ( is_array( $this->users ) && ( count( $this->users ) > 0 ) ) foreach ( $this->users as $userIndex => $user ) {
			$col++;

			$style						=	null;
			$attributes					=	null;

			if ( $this->allowProfileLink ) {
				$style					=	'cursor: hand; cursor: pointer;';
				$attributes				=	' data-id="' . (int) $user->id . '"' . ( $this->profileLinkTarget ? ' data-target="window"' : null );
			}

			$class						=	null;

			if ( $layout == 'grid' ) {
				$class					.=	( $gridSize > 0 ? 'pb-2' : null ) . $gridClass;

				// Add the responsive padding based off the grid size:
				switch ( $gridSize ) {
					case 1: // 12 columns
						$class			.=	( ( $i + 1 ) % 2 == 0 ? ' pl-sm-1' : ' pr-sm-1' ) . ( ( $i + 1 ) % 3 == 0 ? ' pr-md-0 pl-md-1' : ( ( $i + 1 ) % 3 == 2 ? ' pl-md-1 pr-md-1' : ' pl-md-0 pr-md-1' ) ) . ' pl-lg-0 pr-lg-0';
						break;
					case 2: // 6 columns
						$class			.=	( ( $i + 1 ) % 2 == 0 ? ' pl-sm-1' : ' pr-sm-1' ) . ( ( $i + 1 ) % 3 == 0 ? ' pr-md-0 pl-md-1' . ( ( $i + 1 ) % 6 == 3 ? ' pr-lg-1' : null ) : ( ( $i + 1 ) % 3 == 2 ? ' pl-md-1 pr-md-1' : ' pl-md-0 pr-md-1' . ( ( $i + 1 ) % 6 == 4 ? ' pl-lg-1' : null ) ) );
						break;
					case 3: // 4 columns
						$class			.=	( ( $i + 1 ) % 2 == 0 ? ' pl-sm-1' : ' pr-sm-1' ) . ( ( $i + 1 ) % 4 == 0 ? ' pr-md-0 pl-md-1' : ( ( $i + 1 ) % 4 == 2 ? ' pl-md-1 pr-md-1' : ( ( $i + 1 ) % 3 == 0 ? ' pl-md-1 pr-md-1' : ' pl-md-0 pr-md-1' ) ) );
						break;
					case 4: // 3 columns
						$class			.=	( ( $i + 1 ) % 2 == 0 ? ' pl-sm-1' : ' pr-sm-1' ) . ( ( $i + 1 ) % 3 == 0 ? ' pr-md-0 pl-md-1' : ( ( $i + 1 ) % 3 == 2 ? ' pl-md-1 pr-md-1' : ' pl-md-0 pr-md-1' ) );
						break;
					case 5: // 2 columns
					case 6: // 2 columns
						$class			.=	( $i % 2 ? ' pl-sm-1' : ' pr-sm-1' );
						break;
				}
			} else {
				if ( $formatting == 'divs' ) {
					$class				.=	'row no-gutters bg-light cbColumns';
				}

				$class					.=	( $class ? ' ' : null ) . 'sectiontableentry' . ( 1 + ( $i % 2 ) );
			}

			$class						.=	' cbUserListRow';

			if ( $user->banned ) {
				$class					.=	' cbUserListRowBanned';
			}

			if ( ! $user->confirmed ) {
				$class					.=	' cbUserListRowUnconfirmed';
			}

			if ( ! $user->approved ) {
				$class					.=	' cbUserListRowUnapproved';
			}

			if ( $user->block ) {
				$class					.=	' cbUserListRowBlocked';
			}

			if ( $columnCount ) {
				if ( $formatting == 'divs' ) {
					$return				.=			'<div class="' . trim( $class ) . '"' . ( $style ? ' style="' . $style . '"' : null ) . $attributes . ' role="row">';
				} else {
					$return				.=			'<tr class="' . trim( $class ) . '"' . ( $style ? ' style="' . $style . '"' : null ) . $attributes . '>';
				}

				$canvas					=	null;
				$avatar					=	null;
				$status					=	null;
				$name					=	null;
				$top					=	null;
				$bottom					=	null;
				$columns				=	null;

				if ( $layout == 'grid' ) {
					// Check for core fields that we need to reposition, but only check the first column:
					if ( $hasCanvas && isset( $this->tableContent[$userIndex][0] ) ) {
						foreach ( $this->tableContent[$userIndex][0] as $fieldIndex => $fieldView ) {
							if ( ( $fieldView->name == 'canvas' ) && ( ! $canvas ) ) {
								$canvas	=	$fieldView->value;

								unset( $this->tableContent[$userIndex][0][$fieldIndex] );
							} elseif ( ( $fieldView->name == 'avatar' ) && ( ! $avatar ) ) {
								$avatar	=	$fieldView->value;

								unset( $this->tableContent[$userIndex][0][$fieldIndex] );
							} elseif ( ( $fieldView->name == 'onlinestatus' ) && ( ! $status ) ) {
								$status	=	$fieldView->value;

								unset( $this->tableContent[$userIndex][0][$fieldIndex] );
							} elseif ( in_array( $fieldView->name, array( 'formatname', 'username', 'name' ) ) && ( ! $name ) ) {
								$name	=	$fieldView->value;

								unset( $this->tableContent[$userIndex][0][$fieldIndex] );
							}
						}
					}
				}

				foreach ( $this->columns as $columnIndex => $column ) {
					$cellContent		=	$this->_getUserListCell( $this->tableContent[$userIndex][$columnIndex] );

					if ( $formatting == 'divs' ) {
						if ( $layout == 'grid' ) {
							if ( ! $cellContent ) {
								continue;
							}

							if ( $column->position ) {
								// Skip column sizes for specific canvas positioning and first column then display top/bottom columns as inline:
								$gridColumn		=				'<div class="' . ( in_array( $column->position, array( 'canvas_top', 'canvas_bottom' ) ) ? 'd-inline-block ml-1 ' : null ) . 'cbUserListRowColumn cbUserListRowCol' . ( $columnIndex + 1 ) . ( $column->cssclass ? ' ' . $column->cssclass : null ) . '" role="gridcell">' . $cellContent . '</div>';
							} else {
								$gridColumn		=				'<div class="col-sm' . ( $columnIndex == 0 ? '-12' : ( $column->size ? '-' . $column->size : null ) ) . ' cbColumn' . ( $columnIndex == 0 ? 12 : $column->size ) . ' cbUserListRowColumn cbUserListRowCol' . ( $columnIndex + 1 ) . ( $column->cssclass ? ' ' . $column->cssclass : null ) . '" role="gridcell">' . $cellContent . '</div>';
							}

							switch ( $column->position ) {
								case 'canvas_background':
									$canvas		.=				$gridColumn;
									break;
								case 'canvas_avatar':
									$avatar		.=				$gridColumn;
									break;
								case 'canvas_name':
									$name		.=				$gridColumn;
									break;
								case 'canvas_top':
									$top		.=				$gridColumn;
									break;
								case 'canvas_bottom':
									$bottom		.=				$gridColumn;
									break;
								default:
									$columns	.=				$gridColumn;
									break;
							}
						} else {
							$columns	.=				'<div class="col-sm' . ( $column->size ? '-' . $column->size : null ) . ' border-top p-2 cbColumn' . $column->size . ' cbUserListRowColumn cbUserListRowCol' . ( $columnIndex + 1 ) . ( $column->cssclass ? ' ' . $column->cssclass : null ) . '" role="gridcell">' . $cellContent . '</div>';
						}
					} else {
						$columns		.=				'<td class="cbUserListRowColumn cbUserListRowCol' . ( $columnIndex + 1 ) . ( $column->cssclass ? ' ' . trim( $column->cssclass ) : null ) . '">' . $cellContent . '</td>';
					}
				}

				if ( $layout == 'grid' ) {
					$return				.=			'<div class="card' . ( $gridSize == 0 ? ' rounded-0' : null ) . ' no-overflow cbCanvasLayout cbCanvasLayoutSm">';

					if ( $canvas || $top || $bottom ) {
						$return			.=				'<div class="card-header p-0 position-relative cbCanvasLayoutTop">';

						if ( $canvas ) {
							$return		.=					'<div class="position-absolute cbCanvasLayoutBackground">'
										.						$canvas
										.					'</div>';
						}

						if ( $top ) {
							$return		.=					'<div class="position-absolute text-right p-1 cbCanvasLayoutActions">'
										.						$top
										.					'</div>';
						}

						if ( $bottom ) {
							$return		.=					'<div class="position-absolute text-right p-1 cbCanvasLayoutButtons">'
										.						$bottom
										.					'</div>';
						}

						$return			.=				'</div>';
					}

					if ( $avatar ) {
						$return			.=				'<div class="position-relative cbCanvasLayoutBottom">'
										.					'<div class="position-absolute cbCanvasLayoutPhoto">'
										.						$avatar
										.					'</div>'
										.				'</div>';
					}

					$return				.=				'<div class="card-body p-2 position-relative cbCanvasLayoutBody">';

					if ( $name ) {
						$return			.=					'<div class="text-truncate cbCanvasLayoutContent">'
										.						( $status ? '<span class="fa-only">' . $status . '</span> ' : null )
										.						$name
										.					'</div>';
					}

					$return				.=					'<div class="row no-gutters cbCanvasLayoutContent">'
										.						$columns
										.					'</div>'
										.				'</div>'
										.			'</div>';
				} else {
					$return				.=				$columns;
				}

				if ( $formatting == 'divs' ) {
					$return				.=			'</div>';
				} else {
					$return				.=			'</tr>';
				}
			}

			$i++;
		} else {
			if ( $formatting == 'divs' ) {
				if ( $layout != 'grid' ) {
					$return				.=			'<div class="cbUserListRow cbColumns clearfix">';
				}

				$return					.=			'<div class="' . ( $layout != 'grid' ? 'col-sm-12 cbColumn12 ' : null ) . 'sectiontableentry1">'
												// CBTxt::Th( 'UE_NO_USERS_IN_LIST', 'No users in this list' )
										.				CBTxt::Th( 'USERLIST_' . (int) $this->listId . '_NO_USERS_IN_LIST UE_NO_USERS_IN_LIST', 'No users in this list', array( '[title]' => $this->listTitleHtml ) )
										.			'</div>';

				if ( $layout != 'grid' ) {
					$return				.=			'</div>';
				}
			} else {
				$return					.=			'<tr class="sectiontableentry1">'
																						// CBTxt::Th( 'UE_NO_USERS_IN_LIST', 'No users in this list' )
										.				'<td colspan="' . $columnCount . '">' . CBTxt::Th( 'USERLIST_' . (int) $this->listId . '_NO_USERS_IN_LIST UE_NO_USERS_IN_LIST', 'No users in this list', array( '[title]' => $this->listTitleHtml ) ) . '</td>'
										.			'</tr>';
			}
		}

		if ( $layout == 'grid' ) {
			$return						.=			'<div class="clearfix"></div>';
		}

		if ( $formatting == 'divs' ) {
			$return						.=	'</div>';
		} else {
			$return						.=		'<tbody>'
										.	'</table>';
		}

		echo $return;
	}

	/**
	 * Renders a cell for the list view
	 *
	 * @param  stdClass[] $cellFields CB fields in cell
	 * @return string                 HTML
	 */
	private function _getUserListCell( $cellFields )
	{
		$return						=	null;

		foreach ( $cellFields as $fieldView ) {
			if ( $fieldView->value == '' ) {
				continue;
			}

			$return					.=	'<div class="cbUserListFieldLine cbUserListFL_' . $fieldView->name . '">';

			switch ( $fieldView->display ) {
				case 1:
					$return			.=		'<span class="cbUserListFieldTitle cbUserListFT_' . $fieldView->name . '">' . $fieldView->title . '</span> '
									.		'<span class="cbListFieldCont cbUserListFC_' . $fieldView->name . '">' . $fieldView->value . '</span>';
					break;
				case 2:
					$return			.=		'<div class="cbUserListFieldTitle cbUserListFT_' . $fieldView->name . '">' . $fieldView->title . '</div>'
									.		'<div class="cbListFieldCont cbUserListFC_' . $fieldView->name . '">' . $fieldView->value . '</div>';
					break;
				case 3:
					$return			.=		'<span class="cbUserListFieldTitle cbUserListFT_' . $fieldView->name . '"></span> '
									.		'<span class="cbListFieldCont cbUserListFC_' . $fieldView->name . '">' . $fieldView->value . '</span>';
					break;
				default:
					$return			.=		'<span class="cbListFieldCont cbUserListFC_' . $fieldView->name . '">' . $fieldView->value . '</span>';
					break;
			}

			$return					.=	'</div>';
		}

		return $return;
	}
}
