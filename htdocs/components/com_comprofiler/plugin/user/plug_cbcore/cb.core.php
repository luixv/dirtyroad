<?php
/**
* Community Builder (TM)
* @version $Id: $
* @package CommunityBuilder
* @copyright (C) 2004-2021 www.joomlapolis.com / Lightning MultiCom SA - and its licensors, all rights reserved
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

use CBLib\Application\Application;
use CBLib\Input\Get;
use CBLib\Language\CBTxt;
use CBLib\Registry\GetterInterface;
use CB\Database\Table\FieldTable;
use CB\Database\Table\TabTable;
use CB\Database\Table\UserTable;
use CBLib\Registry\Registry;
use CBLib\Image\Color;

/** ensure this file is being included by a parent file */
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

global $_PLUGINS;
$_PLUGINS->registerFunction( 'onBeforeDeleteUser', 'onBeforeDeleteUser', 'CBfield_image' );
$_PLUGINS->registerFunction( 'onBeforeDeleteUser', 'deleteFiles', 'CBfield_file' );
$_PLUGINS->registerUserFieldTypes( array( 	'checkbox'				=> 'CBfield_checkbox',
											'multicheckbox'			=> 'CBfield_select_multi_radio',
											'date'					=> 'CBfield_date',
											'time'					=> 'CBfield_date',
											'datetime'				=> 'CBfield_date',
											'select'				=> 'CBfield_select_multi_radio',
											'multiselect'			=> 'CBfield_select_multi_radio',
											'tag'					=> 'CBfield_select_multi_radio',
											'emailaddress'			=> 'CBfield_email',
											'primaryemailaddress'	=> 'CBfield_email',
											'editorta'				=> 'CBfield_editorta',
											'textarea'				=> 'CBfield_textarea',
											'text'					=> 'CBfield_text',
											'integer'				=> 'CBfield_integer',
											'float'					=> 'CBfield_integer',
											'radio'					=> 'CBfield_select_multi_radio',
											'webaddress'			=> 'CBfield_webaddress',
											'pm'					=> 'CBfield_pm',
											'image'					=> 'CBfield_image',
											'status'				=> 'CBfield_status',
											'formatname'			=> 'CBfield_formatname',
											'predefined'			=> 'CBfield_predefined',
											'counter'				=> 'CBfield_counter',
											'connections'			=> 'CBfield_connections',
											'password'				=> 'CBfield_password',
											'hidden'				=> 'CBfield_text',
											'delimiter'				=> 'CBfield_delimiter',
											'userparams'			=> 'CBfield_userparams',
											'file'					=> 'CBfield_file',
											'video'					=> 'CBfield_video',
											'audio'					=> 'CBfield_audio',
											'rating'				=> 'CBfield_rating',
											'points'				=> 'CBfield_points',
											'terms'					=> 'CBfield_terms',
											'color'					=> 'CBfield_color' ) );	// reserved, used now: 'other_types'
																								// future reserved: 'all_types'
$_PLUGINS->registerUserFieldParams();


/**
 * Commented CBT calls for language parser pickup
 * CBTxt::T( '_UE_ADDITIONAL_INFO_HEADER', 'Additional Information' )
 * CBTxt::T( '_UE_Website', 'Web site' )
 * CBTxt::T( '_UE_Location', 'Location' )
 * CBTxt::T( '_UE_Occupation', 'Occupation' )
 * CBTxt::T( '_UE_Interests', 'Interests' )
 * CBTxt::T( '_UE_Company', 'Company' )
 * CBTxt::T( '_UE_City', 'City' )
 * CBTxt::T( '_UE_State', 'State' )
 * CBTxt::T( '_UE_ZipCode', 'Zip Code' )
 * CBTxt::T( '_UE_Country', 'Country' )
 * CBTxt::T( '_UE_Address', 'Address' )
 * CBTxt::T( '_UE_PHONE', 'Phone #' )
 * CBTxt::T( '_UE_FAX', 'Fax #' )
 */


class CBfield_text extends cbFieldHandler {

	/**
	 * formats variable array into data attribute string
	 *
	 * @param  FieldTable   $field
	 * @param  UserTable    $user
	 * @param  string       $output
	 * @param  string       $reason
	 * @param  array        $attributeArray
	 * @return null|string
	 */
	protected function getDataAttributes( $field, $user, $output, $reason, $attributeArray = array() ) {
		$pregExp						=	$this->_getRegexp( $field );

		if ( $pregExp ) {
			$attributeArray[]			=	cbValidator::getRuleHtmlAttributes( 'pattern', $pregExp );
			$attributeArray[]			=	cbValidator::getMsgHtmlAttributes( $this->pregExpSuccessText( $field, $user, $reason ), $this->pregExpErrorText( $field, $user, $reason ) );
		}

		if ( ( $reason == 'register' ) && ( in_array( $field->type, array( 'emailaddress', 'primaryemailaddress', 'textarea', 'text', 'webaddress', 'predefined' ) ) ) ) {
			$defaultForbidden			=	'http:,https:,mailto:,//.[url],<a,</a>,&#';
		} else {
			$defaultForbidden			=	'';
		}

		$forbiddenContent				=	CBTxt::T( $field->params->get( 'fieldValidateForbiddenList_' . $reason, $defaultForbidden ) );

		if ( $forbiddenContent != '' ) {
			$forbiddenWords				=	array();

			foreach ( explode( ',', $forbiddenContent ) as $forbiddenWord ) {
				if ( $forbiddenWord === '' ) {
					$forbiddenWords[]	=	',';
				} else {
					$forbiddenWords[]	=	$forbiddenWord;
				}
			}

			$attributeArray[]			=	cbValidator::getRuleHtmlAttributes( 'forbiddenwords', $forbiddenWords, CBTxt::T( 'UE_INPUT_VALUE_NOT_ALLOWED', 'This input value is not authorized.' ) );
		}

		if ( $field->get( 'type', null, GetterInterface::STRING ) == 'text' ) {
			$inputMask					=	$field->params->get( 'fieldInputMask', null, GetterInterface::STRING );

			if ( $inputMask ) {
				$mask					=	'mask';
				$params					=	null;
				$direction				=	0;

				switch ( $inputMask ) {
					case 'validation':
						$mask			=	'pattern';
						$params			=	$pregExp;
						break;
					case 'phone':
						$params			=	array(	'mask'	=>	array(	'999-9999',
																		'(999) 999-9999',
																		'+ 9 9{1,3} 999 9999',
																		'+ 99 9{1,3} 999 9999',
																		'+ 999 9{1,3} 999 9999'
																	) );
						break;
					case 'ip':
					case 'mac':
					case 'vin':
						$mask			=	'alias';
						$params			=	$inputMask;
						break;
					case 'customstring':
						$params			=	CBTxt::T( $field->params->get( 'fieldInputMaskString', null, GetterInterface::STRING ) );
						$direction		=	$field->params->get( 'fieldInputMaskDir', 0, GetterInterface::INT );
						break;
					case 'customregex':
						$mask			=	'pattern';
						$params			=	CBTxt::T( $field->params->get( 'fieldInputMaskRegexp', null, GetterInterface::RAW ) );
						break;
				}

				if ( $params ) {
					$attributeArray[]	=	cbValidator::getMaskHtmlAttributes( $mask, $params, $direction );
				}
			}
		}

		return parent::getDataAttributes( $field, $user, $output, $reason, $attributeArray );
	}

	/**
	 * Validator:
	 * Validates $value for $field->required and other rules
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user        RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  string      $columnName  Column to validate
	 * @param  string      $value       (RETURNED:) Value to validate, Returned Modified if needed !
	 * @param  array       $postdata    Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason      'edit' for save profile edit, 'register' for registration, 'search' for searches
	 * @return boolean                  True if validate, $this->_setErrorMSG if False
	 */
	public function validate( &$field, &$user, $columnName, &$value, &$postdata, $reason ) {
		$validated						=	parent::validate( $field, $user, $columnName, $value, $postdata, $reason );
		if ( $validated && ( $value !== '' ) && ( $value !== null ) ) {		// empty values (e.g. non-mandatory) are treated in the parent validation.
			$pregExp					=	$this->_getRegexp( $field );
			if ( $pregExp ) {
				$validated				=	preg_match( $pregExp, $value );
				if ( ! $validated ) {
					$pregExpError		=	$this->pregExpErrorText( $field, $user, $reason );
					$this->_setValidationError( $field, $user, $reason, $pregExpError );
				}
			}
		}
		return $validated;
	}

	/**
	 * Gets the regular expression to validate
	 * @param  FieldTable  $field  Field
	 * @return string
	 */
	protected function _getRegexp( $field )
	{
		switch ( $field->params->get( 'fieldValidateExpression', null, GetterInterface::STRING ) ) {
			case 'singleword':
				return '/^[a-z]*$/iu';
				break;
			case 'multiplewords':
				return '/^([a-z]+ *)*$/iu';
				break;
			case 'singleaznum':
				return '/^[a-z]+[a-z0-9_]*$/iu';
				break;
			case 'atleastoneofeach':
				return '/^(?=.*\d)(?=.*(\W|_))(?=.*[a-z])(?=.*[A-Z]).{6,255}$/u';
				break;
			case 'phone':
				return '/^((?:\d{3}-\d{4})|(?:\(\d{3}\) \d{3}-\d{4})|(?:\+ ?\d{1,3} \d{1,3} \d{3} \d{4}))$/';
				break;
			case 'ip':
				return '/^((?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3})$/';
				break;
			case 'mac':
				return '/^([0-9A-Fa-f]{2}:[0-9A-Fa-f]{2}:[0-9A-Fa-f]{2}:[0-9A-Fa-f]{2}:[0-9A-Fa-f]{2}:[0-9A-Fa-f]{2})$/';
				break;
			case 'vin':
				return '/^([A-HJ-NPR-Z\d]{13}\d{4})$/';
				break;
			case 'customregex':
				return CBTxt::T( $field->params->get( 'pregexp', '/^.*$/', GetterInterface::RAW ) );
				break;
		}

		return null;
	}

	/**
	 * Returns a translated validation success message
	 *
	 * @param FieldTable $field
	 * @param UserTable  $user
	 * @param string     $reason
	 * @return string
	 */
	protected function pregExpSuccessText( $field, $user, $reason )
	{
		$pregExpSuccess		=	$field->params->get( 'pregexpsuccess', '', GetterInterface::HTML );

		if ( $pregExpSuccess ) {
			return CBTxt::T( $pregExpSuccess , null, array( '[FIELDNAME]' => $this->getFieldTitle( $field, $user, 'text', $reason ) ) );
		}

		return null;
	}

	/**
	 * Returns translated or generic 'Not a valid input' validation failed message
	 *
	 * @param FieldTable $field
	 * @param UserTable  $user
	 * @param string     $reason
	 * @return string
	 */
	protected function pregExpErrorText( $field, $user, $reason )
	{
		$pregExpError		=	$field->params->get( 'pregexperror', '', GetterInterface::HTML );

		if ( $pregExpError ) {
			return CBTxt::T( $pregExpError , null, array( '[FIELDNAME]' => $this->getFieldTitle( $field, $user, 'text', $reason ) ) );
		}

		return CBTxt::T( 'NOT_A_VALID_INPUT', 'Not a valid input', array( '[FIELDNAME]' => $this->getFieldTitle( $field, $user, 'text', $reason ) ) );
	}
}

class CBfield_textarea extends CBfield_text {
	/**
	 * Accessor:
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		switch ( $output ) {
			case 'html':
			case 'rss':
				return str_replace( "\n", '<br />', parent::getField( $field, $user, $output, $reason, $list_compare_types ) );
			default:
				return parent::getField( $field, $user, $output, $reason, $list_compare_types );
				break;
		}
	}
	/**
	 * converts to HTML
	 * Override to change the field type from textarea to text in case of searches.
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $reason      'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'search' for searches
	 * @param  string      $tag         <tag
	 * @param  string      $type        type="$type"
	 * @param  string      $value       value="$value"
	 * @param  string      $additional  'xxxx="xxx" yy="y"'  WARNING: No classes in here, use $classes
	 * @param  string      $allValues
	 * @param  boolean     $displayFieldIcons
	 * @param  array       $classes     CSS classes
	 * @param  boolean     $translate          specify if $allValues should be translated or not
	 * @return string                   HTML: <tag type="$type" value="$value" xxxx="xxx" yy="y" />
	 */
	protected function _fieldEditToHtml( &$field, &$user, $reason, $tag, $type, $value, $additional, $allValues = null, $displayFieldIcons = true, $classes = null, $translate = true ) {
		$rows					=	$field->rows;

		if ( $reason == 'search' ) {
			if ( $rows > 5 ) {
				$field->rows	=	5;
			}
		}

		$return					=	 parent::_fieldEditToHtml( $field, $user, $reason, $tag, $type, $value, $additional, $allValues, $displayFieldIcons, $classes );

		if ( $reason == 'search' ) {
			$field->rows		=	$rows;
		}

		return $return;
	}
}

class CBfield_predefined extends CBfield_text {

	/**
	 * formats variable array into data attribute string
	 *
	 * @param  FieldTable   $field
	 * @param  UserTable    $user
	 * @param  string       $output
	 * @param  string       $reason
	 * @param  array        $attributeArray
	 * @return null|string
	 */
	protected function getDataAttributes( $field, $user, $output, $reason, $attributeArray = array() ) {
		global $_CB_framework, $ueConfig;

		if ( $field->name == 'username' ) {
			$attributeArray[]		=	cbValidator::getRuleHtmlAttributes( 'cbusername' );

			if ( $ueConfig['reg_username_checker'] == 1 ) {
				$attributeArray[]	=	cbValidator::getRuleHtmlAttributes( 'cbfield', array( 'user' => (int) $user->id, 'field' => htmlspecialchars( $field->name ), 'reason' => htmlspecialchars( $reason ) ) );
			}
		} elseif ( $field->name == 'alias' ) {
			$aliasReg				=	'/^[a-zA-Z][a-zA-Z0-9\-]+$/';

			if ( $_CB_framework->getCfg( 'unicodeslugs' ) == 1 ) {
				// ES2015 Unicode regular expression in ES5 of ES6 /^[a-zA-Z\p{L}][a-zA-Z0-9\-\p{L}]+$/u using RegexpU https://github.com/mathiasbynens/regexpu
				// online at https://mothereff.in/regexpu
				$aliasReg			=	'/^(?:[A-Za-z\xAA\xB5\xBA\xC0-\xD6\xD8-\xF6\xF8-\u02C1\u02C6-\u02D1\u02E0-\u02E4\u02EC\u02EE\u0370-\u0374\u0376\u0377\u037A-\u037D\u037F\u0386\u0388-\u038A\u038C\u038E-\u03A1\u03A3-\u03F5\u03F7-\u0481\u048A-\u052F\u0531-\u0556\u0559\u0560-\u0588\u05D0-\u05EA\u05EF-\u05F2\u0620-\u064A\u066E\u066F\u0671-\u06D3\u06D5\u06E5\u06E6\u06EE\u06EF\u06FA-\u06FC\u06FF\u0710\u0712-\u072F\u074D-\u07A5\u07B1\u07CA-\u07EA\u07F4\u07F5\u07FA\u0800-\u0815\u081A\u0824\u0828\u0840-\u0858\u0860-\u086A\u08A0-\u08B4\u08B6-\u08BD\u0904-\u0939\u093D\u0950\u0958-\u0961\u0971-\u0980\u0985-\u098C\u098F\u0990\u0993-\u09A8\u09AA-\u09B0\u09B2\u09B6-\u09B9\u09BD\u09CE\u09DC\u09DD\u09DF-\u09E1\u09F0\u09F1\u09FC\u0A05-\u0A0A\u0A0F\u0A10\u0A13-\u0A28\u0A2A-\u0A30\u0A32\u0A33\u0A35\u0A36\u0A38\u0A39\u0A59-\u0A5C\u0A5E\u0A72-\u0A74\u0A85-\u0A8D\u0A8F-\u0A91\u0A93-\u0AA8\u0AAA-\u0AB0\u0AB2\u0AB3\u0AB5-\u0AB9\u0ABD\u0AD0\u0AE0\u0AE1\u0AF9\u0B05-\u0B0C\u0B0F\u0B10\u0B13-\u0B28\u0B2A-\u0B30\u0B32\u0B33\u0B35-\u0B39\u0B3D\u0B5C\u0B5D\u0B5F-\u0B61\u0B71\u0B83\u0B85-\u0B8A\u0B8E-\u0B90\u0B92-\u0B95\u0B99\u0B9A\u0B9C\u0B9E\u0B9F\u0BA3\u0BA4\u0BA8-\u0BAA\u0BAE-\u0BB9\u0BD0\u0C05-\u0C0C\u0C0E-\u0C10\u0C12-\u0C28\u0C2A-\u0C39\u0C3D\u0C58-\u0C5A\u0C60\u0C61\u0C80\u0C85-\u0C8C\u0C8E-\u0C90\u0C92-\u0CA8\u0CAA-\u0CB3\u0CB5-\u0CB9\u0CBD\u0CDE\u0CE0\u0CE1\u0CF1\u0CF2\u0D05-\u0D0C\u0D0E-\u0D10\u0D12-\u0D3A\u0D3D\u0D4E\u0D54-\u0D56\u0D5F-\u0D61\u0D7A-\u0D7F\u0D85-\u0D96\u0D9A-\u0DB1\u0DB3-\u0DBB\u0DBD\u0DC0-\u0DC6\u0E01-\u0E30\u0E32\u0E33\u0E40-\u0E46\u0E81\u0E82\u0E84\u0E87\u0E88\u0E8A\u0E8D\u0E94-\u0E97\u0E99-\u0E9F\u0EA1-\u0EA3\u0EA5\u0EA7\u0EAA\u0EAB\u0EAD-\u0EB0\u0EB2\u0EB3\u0EBD\u0EC0-\u0EC4\u0EC6\u0EDC-\u0EDF\u0F00\u0F40-\u0F47\u0F49-\u0F6C\u0F88-\u0F8C\u1000-\u102A\u103F\u1050-\u1055\u105A-\u105D\u1061\u1065\u1066\u106E-\u1070\u1075-\u1081\u108E\u10A0-\u10C5\u10C7\u10CD\u10D0-\u10FA\u10FC-\u1248\u124A-\u124D\u1250-\u1256\u1258\u125A-\u125D\u1260-\u1288\u128A-\u128D\u1290-\u12B0\u12B2-\u12B5\u12B8-\u12BE\u12C0\u12C2-\u12C5\u12C8-\u12D6\u12D8-\u1310\u1312-\u1315\u1318-\u135A\u1380-\u138F\u13A0-\u13F5\u13F8-\u13FD\u1401-\u166C\u166F-\u167F\u1681-\u169A\u16A0-\u16EA\u16F1-\u16F8\u1700-\u170C\u170E-\u1711\u1720-\u1731\u1740-\u1751\u1760-\u176C\u176E-\u1770\u1780-\u17B3\u17D7\u17DC\u1820-\u1878\u1880-\u1884\u1887-\u18A8\u18AA\u18B0-\u18F5\u1900-\u191E\u1950-\u196D\u1970-\u1974\u1980-\u19AB\u19B0-\u19C9\u1A00-\u1A16\u1A20-\u1A54\u1AA7\u1B05-\u1B33\u1B45-\u1B4B\u1B83-\u1BA0\u1BAE\u1BAF\u1BBA-\u1BE5\u1C00-\u1C23\u1C4D-\u1C4F\u1C5A-\u1C7D\u1C80-\u1C88\u1C90-\u1CBA\u1CBD-\u1CBF\u1CE9-\u1CEC\u1CEE-\u1CF1\u1CF5\u1CF6\u1D00-\u1DBF\u1E00-\u1F15\u1F18-\u1F1D\u1F20-\u1F45\u1F48-\u1F4D\u1F50-\u1F57\u1F59\u1F5B\u1F5D\u1F5F-\u1F7D\u1F80-\u1FB4\u1FB6-\u1FBC\u1FBE\u1FC2-\u1FC4\u1FC6-\u1FCC\u1FD0-\u1FD3\u1FD6-\u1FDB\u1FE0-\u1FEC\u1FF2-\u1FF4\u1FF6-\u1FFC\u2071\u207F\u2090-\u209C\u2102\u2107\u210A-\u2113\u2115\u2119-\u211D\u2124\u2126\u2128\u212A-\u212D\u212F-\u2139\u213C-\u213F\u2145-\u2149\u214E\u2183\u2184\u2C00-\u2C2E\u2C30-\u2C5E\u2C60-\u2CE4\u2CEB-\u2CEE\u2CF2\u2CF3\u2D00-\u2D25\u2D27\u2D2D\u2D30-\u2D67\u2D6F\u2D80-\u2D96\u2DA0-\u2DA6\u2DA8-\u2DAE\u2DB0-\u2DB6\u2DB8-\u2DBE\u2DC0-\u2DC6\u2DC8-\u2DCE\u2DD0-\u2DD6\u2DD8-\u2DDE\u2E2F\u3005\u3006\u3031-\u3035\u303B\u303C\u3041-\u3096\u309D-\u309F\u30A1-\u30FA\u30FC-\u30FF\u3105-\u312F\u3131-\u318E\u31A0-\u31BA\u31F0-\u31FF\u3400-\u4DB5\u4E00-\u9FEF\uA000-\uA48C\uA4D0-\uA4FD\uA500-\uA60C\uA610-\uA61F\uA62A\uA62B\uA640-\uA66E\uA67F-\uA69D\uA6A0-\uA6E5\uA717-\uA71F\uA722-\uA788\uA78B-\uA7B9\uA7F7-\uA801\uA803-\uA805\uA807-\uA80A\uA80C-\uA822\uA840-\uA873\uA882-\uA8B3\uA8F2-\uA8F7\uA8FB\uA8FD\uA8FE\uA90A-\uA925\uA930-\uA946\uA960-\uA97C\uA984-\uA9B2\uA9CF\uA9E0-\uA9E4\uA9E6-\uA9EF\uA9FA-\uA9FE\uAA00-\uAA28\uAA40-\uAA42\uAA44-\uAA4B\uAA60-\uAA76\uAA7A\uAA7E-\uAAAF\uAAB1\uAAB5\uAAB6\uAAB9-\uAABD\uAAC0\uAAC2\uAADB-\uAADD\uAAE0-\uAAEA\uAAF2-\uAAF4\uAB01-\uAB06\uAB09-\uAB0E\uAB11-\uAB16\uAB20-\uAB26\uAB28-\uAB2E\uAB30-\uAB5A\uAB5C-\uAB65\uAB70-\uABE2\uAC00-\uD7A3\uD7B0-\uD7C6\uD7CB-\uD7FB\uF900-\uFA6D\uFA70-\uFAD9\uFB00-\uFB06\uFB13-\uFB17\uFB1D\uFB1F-\uFB28\uFB2A-\uFB36\uFB38-\uFB3C\uFB3E\uFB40\uFB41\uFB43\uFB44\uFB46-\uFBB1\uFBD3-\uFD3D\uFD50-\uFD8F\uFD92-\uFDC7\uFDF0-\uFDFB\uFE70-\uFE74\uFE76-\uFEFC\uFF21-\uFF3A\uFF41-\uFF5A\uFF66-\uFFBE\uFFC2-\uFFC7\uFFCA-\uFFCF\uFFD2-\uFFD7\uFFDA-\uFFDC]|\uD800[\uDC00-\uDC0B\uDC0D-\uDC26\uDC28-\uDC3A\uDC3C\uDC3D\uDC3F-\uDC4D\uDC50-\uDC5D\uDC80-\uDCFA\uDE80-\uDE9C\uDEA0-\uDED0\uDF00-\uDF1F\uDF2D-\uDF40\uDF42-\uDF49\uDF50-\uDF75\uDF80-\uDF9D\uDFA0-\uDFC3\uDFC8-\uDFCF]|\uD801[\uDC00-\uDC9D\uDCB0-\uDCD3\uDCD8-\uDCFB\uDD00-\uDD27\uDD30-\uDD63\uDE00-\uDF36\uDF40-\uDF55\uDF60-\uDF67]|\uD802[\uDC00-\uDC05\uDC08\uDC0A-\uDC35\uDC37\uDC38\uDC3C\uDC3F-\uDC55\uDC60-\uDC76\uDC80-\uDC9E\uDCE0-\uDCF2\uDCF4\uDCF5\uDD00-\uDD15\uDD20-\uDD39\uDD80-\uDDB7\uDDBE\uDDBF\uDE00\uDE10-\uDE13\uDE15-\uDE17\uDE19-\uDE35\uDE60-\uDE7C\uDE80-\uDE9C\uDEC0-\uDEC7\uDEC9-\uDEE4\uDF00-\uDF35\uDF40-\uDF55\uDF60-\uDF72\uDF80-\uDF91]|\uD803[\uDC00-\uDC48\uDC80-\uDCB2\uDCC0-\uDCF2\uDD00-\uDD23\uDF00-\uDF1C\uDF27\uDF30-\uDF45]|\uD804[\uDC03-\uDC37\uDC83-\uDCAF\uDCD0-\uDCE8\uDD03-\uDD26\uDD44\uDD50-\uDD72\uDD76\uDD83-\uDDB2\uDDC1-\uDDC4\uDDDA\uDDDC\uDE00-\uDE11\uDE13-\uDE2B\uDE80-\uDE86\uDE88\uDE8A-\uDE8D\uDE8F-\uDE9D\uDE9F-\uDEA8\uDEB0-\uDEDE\uDF05-\uDF0C\uDF0F\uDF10\uDF13-\uDF28\uDF2A-\uDF30\uDF32\uDF33\uDF35-\uDF39\uDF3D\uDF50\uDF5D-\uDF61]|\uD805[\uDC00-\uDC34\uDC47-\uDC4A\uDC80-\uDCAF\uDCC4\uDCC5\uDCC7\uDD80-\uDDAE\uDDD8-\uDDDB\uDE00-\uDE2F\uDE44\uDE80-\uDEAA\uDF00-\uDF1A]|\uD806[\uDC00-\uDC2B\uDCA0-\uDCDF\uDCFF\uDE00\uDE0B-\uDE32\uDE3A\uDE50\uDE5C-\uDE83\uDE86-\uDE89\uDE9D\uDEC0-\uDEF8]|\uD807[\uDC00-\uDC08\uDC0A-\uDC2E\uDC40\uDC72-\uDC8F\uDD00-\uDD06\uDD08\uDD09\uDD0B-\uDD30\uDD46\uDD60-\uDD65\uDD67\uDD68\uDD6A-\uDD89\uDD98\uDEE0-\uDEF2]|\uD808[\uDC00-\uDF99]|\uD809[\uDC80-\uDD43]|[\uD80C\uD81C-\uD820\uD840-\uD868\uD86A-\uD86C\uD86F-\uD872\uD874-\uD879][\uDC00-\uDFFF]|\uD80D[\uDC00-\uDC2E]|\uD811[\uDC00-\uDE46]|\uD81A[\uDC00-\uDE38\uDE40-\uDE5E\uDED0-\uDEED\uDF00-\uDF2F\uDF40-\uDF43\uDF63-\uDF77\uDF7D-\uDF8F]|\uD81B[\uDE40-\uDE7F\uDF00-\uDF44\uDF50\uDF93-\uDF9F\uDFE0\uDFE1]|\uD821[\uDC00-\uDFF1]|\uD822[\uDC00-\uDEF2]|\uD82C[\uDC00-\uDD1E\uDD70-\uDEFB]|\uD82F[\uDC00-\uDC6A\uDC70-\uDC7C\uDC80-\uDC88\uDC90-\uDC99]|\uD835[\uDC00-\uDC54\uDC56-\uDC9C\uDC9E\uDC9F\uDCA2\uDCA5\uDCA6\uDCA9-\uDCAC\uDCAE-\uDCB9\uDCBB\uDCBD-\uDCC3\uDCC5-\uDD05\uDD07-\uDD0A\uDD0D-\uDD14\uDD16-\uDD1C\uDD1E-\uDD39\uDD3B-\uDD3E\uDD40-\uDD44\uDD46\uDD4A-\uDD50\uDD52-\uDEA5\uDEA8-\uDEC0\uDEC2-\uDEDA\uDEDC-\uDEFA\uDEFC-\uDF14\uDF16-\uDF34\uDF36-\uDF4E\uDF50-\uDF6E\uDF70-\uDF88\uDF8A-\uDFA8\uDFAA-\uDFC2\uDFC4-\uDFCB]|\uD83A[\uDC00-\uDCC4\uDD00-\uDD43]|\uD83B[\uDE00-\uDE03\uDE05-\uDE1F\uDE21\uDE22\uDE24\uDE27\uDE29-\uDE32\uDE34-\uDE37\uDE39\uDE3B\uDE42\uDE47\uDE49\uDE4B\uDE4D-\uDE4F\uDE51\uDE52\uDE54\uDE57\uDE59\uDE5B\uDE5D\uDE5F\uDE61\uDE62\uDE64\uDE67-\uDE6A\uDE6C-\uDE72\uDE74-\uDE77\uDE79-\uDE7C\uDE7E\uDE80-\uDE89\uDE8B-\uDE9B\uDEA1-\uDEA3\uDEA5-\uDEA9\uDEAB-\uDEBB]|\uD869[\uDC00-\uDED6\uDF00-\uDFFF]|\uD86D[\uDC00-\uDF34\uDF40-\uDFFF]|\uD86E[\uDC00-\uDC1D\uDC20-\uDFFF]|\uD873[\uDC00-\uDEA1\uDEB0-\uDFFF]|\uD87A[\uDC00-\uDFE0]|\uD87E[\uDC00-\uDE1D])(?:[\x2D0-9A-Za-z\xAA\xB5\xBA\xC0-\xD6\xD8-\xF6\xF8-\u02C1\u02C6-\u02D1\u02E0-\u02E4\u02EC\u02EE\u0370-\u0374\u0376\u0377\u037A-\u037D\u037F\u0386\u0388-\u038A\u038C\u038E-\u03A1\u03A3-\u03F5\u03F7-\u0481\u048A-\u052F\u0531-\u0556\u0559\u0560-\u0588\u05D0-\u05EA\u05EF-\u05F2\u0620-\u064A\u066E\u066F\u0671-\u06D3\u06D5\u06E5\u06E6\u06EE\u06EF\u06FA-\u06FC\u06FF\u0710\u0712-\u072F\u074D-\u07A5\u07B1\u07CA-\u07EA\u07F4\u07F5\u07FA\u0800-\u0815\u081A\u0824\u0828\u0840-\u0858\u0860-\u086A\u08A0-\u08B4\u08B6-\u08BD\u0904-\u0939\u093D\u0950\u0958-\u0961\u0971-\u0980\u0985-\u098C\u098F\u0990\u0993-\u09A8\u09AA-\u09B0\u09B2\u09B6-\u09B9\u09BD\u09CE\u09DC\u09DD\u09DF-\u09E1\u09F0\u09F1\u09FC\u0A05-\u0A0A\u0A0F\u0A10\u0A13-\u0A28\u0A2A-\u0A30\u0A32\u0A33\u0A35\u0A36\u0A38\u0A39\u0A59-\u0A5C\u0A5E\u0A72-\u0A74\u0A85-\u0A8D\u0A8F-\u0A91\u0A93-\u0AA8\u0AAA-\u0AB0\u0AB2\u0AB3\u0AB5-\u0AB9\u0ABD\u0AD0\u0AE0\u0AE1\u0AF9\u0B05-\u0B0C\u0B0F\u0B10\u0B13-\u0B28\u0B2A-\u0B30\u0B32\u0B33\u0B35-\u0B39\u0B3D\u0B5C\u0B5D\u0B5F-\u0B61\u0B71\u0B83\u0B85-\u0B8A\u0B8E-\u0B90\u0B92-\u0B95\u0B99\u0B9A\u0B9C\u0B9E\u0B9F\u0BA3\u0BA4\u0BA8-\u0BAA\u0BAE-\u0BB9\u0BD0\u0C05-\u0C0C\u0C0E-\u0C10\u0C12-\u0C28\u0C2A-\u0C39\u0C3D\u0C58-\u0C5A\u0C60\u0C61\u0C80\u0C85-\u0C8C\u0C8E-\u0C90\u0C92-\u0CA8\u0CAA-\u0CB3\u0CB5-\u0CB9\u0CBD\u0CDE\u0CE0\u0CE1\u0CF1\u0CF2\u0D05-\u0D0C\u0D0E-\u0D10\u0D12-\u0D3A\u0D3D\u0D4E\u0D54-\u0D56\u0D5F-\u0D61\u0D7A-\u0D7F\u0D85-\u0D96\u0D9A-\u0DB1\u0DB3-\u0DBB\u0DBD\u0DC0-\u0DC6\u0E01-\u0E30\u0E32\u0E33\u0E40-\u0E46\u0E81\u0E82\u0E84\u0E87\u0E88\u0E8A\u0E8D\u0E94-\u0E97\u0E99-\u0E9F\u0EA1-\u0EA3\u0EA5\u0EA7\u0EAA\u0EAB\u0EAD-\u0EB0\u0EB2\u0EB3\u0EBD\u0EC0-\u0EC4\u0EC6\u0EDC-\u0EDF\u0F00\u0F40-\u0F47\u0F49-\u0F6C\u0F88-\u0F8C\u1000-\u102A\u103F\u1050-\u1055\u105A-\u105D\u1061\u1065\u1066\u106E-\u1070\u1075-\u1081\u108E\u10A0-\u10C5\u10C7\u10CD\u10D0-\u10FA\u10FC-\u1248\u124A-\u124D\u1250-\u1256\u1258\u125A-\u125D\u1260-\u1288\u128A-\u128D\u1290-\u12B0\u12B2-\u12B5\u12B8-\u12BE\u12C0\u12C2-\u12C5\u12C8-\u12D6\u12D8-\u1310\u1312-\u1315\u1318-\u135A\u1380-\u138F\u13A0-\u13F5\u13F8-\u13FD\u1401-\u166C\u166F-\u167F\u1681-\u169A\u16A0-\u16EA\u16F1-\u16F8\u1700-\u170C\u170E-\u1711\u1720-\u1731\u1740-\u1751\u1760-\u176C\u176E-\u1770\u1780-\u17B3\u17D7\u17DC\u1820-\u1878\u1880-\u1884\u1887-\u18A8\u18AA\u18B0-\u18F5\u1900-\u191E\u1950-\u196D\u1970-\u1974\u1980-\u19AB\u19B0-\u19C9\u1A00-\u1A16\u1A20-\u1A54\u1AA7\u1B05-\u1B33\u1B45-\u1B4B\u1B83-\u1BA0\u1BAE\u1BAF\u1BBA-\u1BE5\u1C00-\u1C23\u1C4D-\u1C4F\u1C5A-\u1C7D\u1C80-\u1C88\u1C90-\u1CBA\u1CBD-\u1CBF\u1CE9-\u1CEC\u1CEE-\u1CF1\u1CF5\u1CF6\u1D00-\u1DBF\u1E00-\u1F15\u1F18-\u1F1D\u1F20-\u1F45\u1F48-\u1F4D\u1F50-\u1F57\u1F59\u1F5B\u1F5D\u1F5F-\u1F7D\u1F80-\u1FB4\u1FB6-\u1FBC\u1FBE\u1FC2-\u1FC4\u1FC6-\u1FCC\u1FD0-\u1FD3\u1FD6-\u1FDB\u1FE0-\u1FEC\u1FF2-\u1FF4\u1FF6-\u1FFC\u2071\u207F\u2090-\u209C\u2102\u2107\u210A-\u2113\u2115\u2119-\u211D\u2124\u2126\u2128\u212A-\u212D\u212F-\u2139\u213C-\u213F\u2145-\u2149\u214E\u2183\u2184\u2C00-\u2C2E\u2C30-\u2C5E\u2C60-\u2CE4\u2CEB-\u2CEE\u2CF2\u2CF3\u2D00-\u2D25\u2D27\u2D2D\u2D30-\u2D67\u2D6F\u2D80-\u2D96\u2DA0-\u2DA6\u2DA8-\u2DAE\u2DB0-\u2DB6\u2DB8-\u2DBE\u2DC0-\u2DC6\u2DC8-\u2DCE\u2DD0-\u2DD6\u2DD8-\u2DDE\u2E2F\u3005\u3006\u3031-\u3035\u303B\u303C\u3041-\u3096\u309D-\u309F\u30A1-\u30FA\u30FC-\u30FF\u3105-\u312F\u3131-\u318E\u31A0-\u31BA\u31F0-\u31FF\u3400-\u4DB5\u4E00-\u9FEF\uA000-\uA48C\uA4D0-\uA4FD\uA500-\uA60C\uA610-\uA61F\uA62A\uA62B\uA640-\uA66E\uA67F-\uA69D\uA6A0-\uA6E5\uA717-\uA71F\uA722-\uA788\uA78B-\uA7B9\uA7F7-\uA801\uA803-\uA805\uA807-\uA80A\uA80C-\uA822\uA840-\uA873\uA882-\uA8B3\uA8F2-\uA8F7\uA8FB\uA8FD\uA8FE\uA90A-\uA925\uA930-\uA946\uA960-\uA97C\uA984-\uA9B2\uA9CF\uA9E0-\uA9E4\uA9E6-\uA9EF\uA9FA-\uA9FE\uAA00-\uAA28\uAA40-\uAA42\uAA44-\uAA4B\uAA60-\uAA76\uAA7A\uAA7E-\uAAAF\uAAB1\uAAB5\uAAB6\uAAB9-\uAABD\uAAC0\uAAC2\uAADB-\uAADD\uAAE0-\uAAEA\uAAF2-\uAAF4\uAB01-\uAB06\uAB09-\uAB0E\uAB11-\uAB16\uAB20-\uAB26\uAB28-\uAB2E\uAB30-\uAB5A\uAB5C-\uAB65\uAB70-\uABE2\uAC00-\uD7A3\uD7B0-\uD7C6\uD7CB-\uD7FB\uF900-\uFA6D\uFA70-\uFAD9\uFB00-\uFB06\uFB13-\uFB17\uFB1D\uFB1F-\uFB28\uFB2A-\uFB36\uFB38-\uFB3C\uFB3E\uFB40\uFB41\uFB43\uFB44\uFB46-\uFBB1\uFBD3-\uFD3D\uFD50-\uFD8F\uFD92-\uFDC7\uFDF0-\uFDFB\uFE70-\uFE74\uFE76-\uFEFC\uFF21-\uFF3A\uFF41-\uFF5A\uFF66-\uFFBE\uFFC2-\uFFC7\uFFCA-\uFFCF\uFFD2-\uFFD7\uFFDA-\uFFDC]|\uD800[\uDC00-\uDC0B\uDC0D-\uDC26\uDC28-\uDC3A\uDC3C\uDC3D\uDC3F-\uDC4D\uDC50-\uDC5D\uDC80-\uDCFA\uDE80-\uDE9C\uDEA0-\uDED0\uDF00-\uDF1F\uDF2D-\uDF40\uDF42-\uDF49\uDF50-\uDF75\uDF80-\uDF9D\uDFA0-\uDFC3\uDFC8-\uDFCF]|\uD801[\uDC00-\uDC9D\uDCB0-\uDCD3\uDCD8-\uDCFB\uDD00-\uDD27\uDD30-\uDD63\uDE00-\uDF36\uDF40-\uDF55\uDF60-\uDF67]|\uD802[\uDC00-\uDC05\uDC08\uDC0A-\uDC35\uDC37\uDC38\uDC3C\uDC3F-\uDC55\uDC60-\uDC76\uDC80-\uDC9E\uDCE0-\uDCF2\uDCF4\uDCF5\uDD00-\uDD15\uDD20-\uDD39\uDD80-\uDDB7\uDDBE\uDDBF\uDE00\uDE10-\uDE13\uDE15-\uDE17\uDE19-\uDE35\uDE60-\uDE7C\uDE80-\uDE9C\uDEC0-\uDEC7\uDEC9-\uDEE4\uDF00-\uDF35\uDF40-\uDF55\uDF60-\uDF72\uDF80-\uDF91]|\uD803[\uDC00-\uDC48\uDC80-\uDCB2\uDCC0-\uDCF2\uDD00-\uDD23\uDF00-\uDF1C\uDF27\uDF30-\uDF45]|\uD804[\uDC03-\uDC37\uDC83-\uDCAF\uDCD0-\uDCE8\uDD03-\uDD26\uDD44\uDD50-\uDD72\uDD76\uDD83-\uDDB2\uDDC1-\uDDC4\uDDDA\uDDDC\uDE00-\uDE11\uDE13-\uDE2B\uDE80-\uDE86\uDE88\uDE8A-\uDE8D\uDE8F-\uDE9D\uDE9F-\uDEA8\uDEB0-\uDEDE\uDF05-\uDF0C\uDF0F\uDF10\uDF13-\uDF28\uDF2A-\uDF30\uDF32\uDF33\uDF35-\uDF39\uDF3D\uDF50\uDF5D-\uDF61]|\uD805[\uDC00-\uDC34\uDC47-\uDC4A\uDC80-\uDCAF\uDCC4\uDCC5\uDCC7\uDD80-\uDDAE\uDDD8-\uDDDB\uDE00-\uDE2F\uDE44\uDE80-\uDEAA\uDF00-\uDF1A]|\uD806[\uDC00-\uDC2B\uDCA0-\uDCDF\uDCFF\uDE00\uDE0B-\uDE32\uDE3A\uDE50\uDE5C-\uDE83\uDE86-\uDE89\uDE9D\uDEC0-\uDEF8]|\uD807[\uDC00-\uDC08\uDC0A-\uDC2E\uDC40\uDC72-\uDC8F\uDD00-\uDD06\uDD08\uDD09\uDD0B-\uDD30\uDD46\uDD60-\uDD65\uDD67\uDD68\uDD6A-\uDD89\uDD98\uDEE0-\uDEF2]|\uD808[\uDC00-\uDF99]|\uD809[\uDC80-\uDD43]|[\uD80C\uD81C-\uD820\uD840-\uD868\uD86A-\uD86C\uD86F-\uD872\uD874-\uD879][\uDC00-\uDFFF]|\uD80D[\uDC00-\uDC2E]|\uD811[\uDC00-\uDE46]|\uD81A[\uDC00-\uDE38\uDE40-\uDE5E\uDED0-\uDEED\uDF00-\uDF2F\uDF40-\uDF43\uDF63-\uDF77\uDF7D-\uDF8F]|\uD81B[\uDE40-\uDE7F\uDF00-\uDF44\uDF50\uDF93-\uDF9F\uDFE0\uDFE1]|\uD821[\uDC00-\uDFF1]|\uD822[\uDC00-\uDEF2]|\uD82C[\uDC00-\uDD1E\uDD70-\uDEFB]|\uD82F[\uDC00-\uDC6A\uDC70-\uDC7C\uDC80-\uDC88\uDC90-\uDC99]|\uD835[\uDC00-\uDC54\uDC56-\uDC9C\uDC9E\uDC9F\uDCA2\uDCA5\uDCA6\uDCA9-\uDCAC\uDCAE-\uDCB9\uDCBB\uDCBD-\uDCC3\uDCC5-\uDD05\uDD07-\uDD0A\uDD0D-\uDD14\uDD16-\uDD1C\uDD1E-\uDD39\uDD3B-\uDD3E\uDD40-\uDD44\uDD46\uDD4A-\uDD50\uDD52-\uDEA5\uDEA8-\uDEC0\uDEC2-\uDEDA\uDEDC-\uDEFA\uDEFC-\uDF14\uDF16-\uDF34\uDF36-\uDF4E\uDF50-\uDF6E\uDF70-\uDF88\uDF8A-\uDFA8\uDFAA-\uDFC2\uDFC4-\uDFCB]|\uD83A[\uDC00-\uDCC4\uDD00-\uDD43]|\uD83B[\uDE00-\uDE03\uDE05-\uDE1F\uDE21\uDE22\uDE24\uDE27\uDE29-\uDE32\uDE34-\uDE37\uDE39\uDE3B\uDE42\uDE47\uDE49\uDE4B\uDE4D-\uDE4F\uDE51\uDE52\uDE54\uDE57\uDE59\uDE5B\uDE5D\uDE5F\uDE61\uDE62\uDE64\uDE67-\uDE6A\uDE6C-\uDE72\uDE74-\uDE77\uDE79-\uDE7C\uDE7E\uDE80-\uDE89\uDE8B-\uDE9B\uDEA1-\uDEA3\uDEA5-\uDEA9\uDEAB-\uDEBB]|\uD869[\uDC00-\uDED6\uDF00-\uDFFF]|\uD86D[\uDC00-\uDF34\uDF40-\uDFFF]|\uD86E[\uDC00-\uDC1D\uDC20-\uDFFF]|\uD873[\uDC00-\uDEA1\uDEB0-\uDFFF]|\uD87A[\uDC00-\uDFE0]|\uD87E[\uDC00-\uDE1D])+$/';
			}

			$attributeArray[]		=	cbValidator::getRuleHtmlAttributes( 'pattern', $aliasReg, CBTxt::T( 'VALIDATION_ERROR_FIELD_ALIAS', 'Please enter a valid alphanumeric profile url. Must begin with a letter and contain letters, numbers, and dashes only.' ) );
			$attributeArray[]		=	cbValidator::getRuleHtmlAttributes( 'cbfield', array( 'user' => (int) $user->id, 'field' => htmlspecialchars( $field->name ), 'reason' => htmlspecialchars( $reason ) ) );
		}

		return parent::getDataAttributes( $field, $user, $output, $reason, $attributeArray );
	}

	/**
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		global $_CB_framework, $ueConfig;

		$value									=	$user->get( $field->get( 'name' ) );

		switch ( $output ) {
			case 'html':
			case 'rss':
				$profileLink					=	$user->get( '_allowProfileLink', $field->get( '_allowProfileLink', null, GetterInterface::BOOLEAN ), GetterInterface::BOOLEAN ); // For B/C

				if ( $profileLink === null ) {
					$profileLink				=	$field->params->get( 'fieldProfileLink', true, GetterInterface::BOOLEAN );
				}

				if ( $field->name == 'alias' ) {
					$url						=	$_CB_framework->viewUrl( 'userprofile', true, array( 'user' => $user->get( 'id', 0, GetterInterface::INT ) ) );

					return $this->formatFieldValueLayout( '<a href="' . $url . '">' . $url . '</a>', $reason, $field, $user );
				} elseif ( ( $field->type == 'predefined' ) && $profileLink && ( $reason != 'profile' ) && ( $reason != 'edit' ) ) {
					return $this->formatFieldValueLayout( '<a href="' . $_CB_framework->userProfileUrl( $user->id, true ) . '">' . htmlspecialchars( $value ) . '</a>', $reason, $field, $user );
				} else {
					return parent::getField( $field, $user, $output, $reason, $list_compare_types );
				}
				break;
			case 'htmledit':
				if ( $field->name == 'username' ) {
					if ( ! ( ( $ueConfig['usernameedit'] == 0 ) && ( $reason == 'edit' ) && ( $_CB_framework->getUi() == 1 ) ) ) {
						$profile				=	$field->get( 'profile' );

						$field->set( 'profile', 1 );

						$return					=	parent::getField( $field, $user, $output, $reason, $list_compare_types );

						$field->set( 'profile', $profile );
					} else {
						$field->set( 'readonly', 1 );

						$return					=	parent::getField( $field, $user, $output, $reason, $list_compare_types );
					}
				} elseif ( $field->name == 'alias' ) {
					if ( ! $_CB_framework->getCfg( 'sef' ) ) {
						// SEF isn't enabled so just do nothing:
						return null;
					}

					// Alias is always visible so force its icon to show visible on profile:
					$profile					=	$field->get( 'profile' );

					$field->set( 'profile', 1 );

					$return						=	parent::getField( $field, $user, $output, $reason, $list_compare_types );

					if ( ! Application::Cms()->getClientId() ) {
						$alias					=	$value;

						if ( ! $alias ) {
							$alias				=	$user->get( 'username', null, GetterInterface::STRING );
						}

						$aliasReg				=	'/(^[^a-zA-Z])|[^a-zA-Z0-9\-]/';

						if ( $_CB_framework->getCfg( 'unicodeslugs' ) == 1 ) {
							$aliasReg			=	'/(^[^a-zA-Z\p{L}])|[^a-zA-Z0-9\-\p{L}]/u';
						}

						if ( is_numeric( $alias ) || preg_match( $aliasReg, $alias ) || ( $alias != Application::Router()->stringToAlias( $alias ) ) || in_array( $alias, Application::Router()->getViews() ) ) {
							$alias				=	$user->get( 'id', 0, GetterInterface::INT ) . '-' . Application::Router()->stringToAlias( $alias );
						}

						$return					.=	'<div class="small text-muted mt-1 cbCurrentProfileURL">' . CBTxt::Th( 'YOUR_CURRENT_PROFILE_URL_IS_URL', 'Your current Profile URL is: [url]', array( '[url]' => $_CB_framework->viewUrl( 'userprofile', true, array( 'user' => $user->get( 'id', 0, GetterInterface::INT ) ) ), '[alias]' => $alias ) ) . '</div>';
					}

					$field->set( 'profile', $profile );
				} else {
					$return						=	parent::getField( $field, $user, $output, $reason, $list_compare_types );
				}

				return $return;
				break;
			default:
				return parent::getField( $field, $user, $output, $reason, $list_compare_types );
				break;
		}
	}

	/**
	 * Direct access to field for custom operations, like for Ajax
	 *
	 * WARNING: direct unchecked access, except if $user is set, then check
	 * that the logged-in user has rights to edit that $user.
	 *
	 * @param FieldTable     $field
	 * @param null|UserTable $user
	 * @param array          $postdata
	 * @param string         $reason 'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'search' for searches (always public!)
	 * @return string                  Expected output.
	 */
	public function fieldClass( &$field, &$user, &$postdata, $reason ) {
		global $_CB_database, $ueConfig, $_GET;

		parent::fieldClass( $field, $user, $postdata, $reason ); // Performs spoof check

		if ( ! in_array( $reason, array( 'edit', 'register' ) ) ) {
			return null; // wrong reason; do nothing
		}

		$function						=	cbGetParam( $_GET, 'function', '' );

		if ( ! in_array( $function, array( 'checkvalue', 'testexists' ) ) ) {
			return null; // wrong funcion; do nothing
		}

		$fieldName						=	$field->name;
		$ajaxChecker					=	( $fieldName == 'alias' ? 1 : ( isset( $ueConfig['reg_username_checker'] ) ? $ueConfig['reg_username_checker'] : 0 ) );

		if ( ! $ajaxChecker ) {
			return null; // ajax checking is disabled; do nothing
		}

		$valid							=	true;
		$message						=	null;
		$value							=	stripslashes( cbGetParam( $postdata, 'value', '' ) );

		if ( $fieldName == 'alias' ) {
			$value						=	Application::Router()->stringToAlias( $value );
		}

		if ( $value != '' ) {
			if ( ( ! $user ) || ( cbutf8_strtolower( trim( $value ) ) != cbutf8_strtolower( trim( $user->$fieldName ) ) ) ) {
				if ( $function == 'testexists' ) {
					// We're just checking that the exact field value exists or not so don't include the cross-checks (e.g. used on forgot login):
					if ( $fieldName == 'alias' ) {
						$exists			=	$this->checkAliasExists( $value );
					} else {
						$exists			=	$this->checkUsernameExists( $value );
					}
				} else {
					$exists				=	$this->checkUsernameExists( $value );

					if ( ! $exists ) {
						$exists			=	$this->checkAliasExists( $value );
					}
				}

				if ( ( $fieldName == 'alias' ) && in_array( trim( $value ), Application::Router()->getViews() ) ) {
					$exists				=	true;
				}

				if ( $function == 'testexists' ) {
					if ( $exists ) {
						if ( $fieldName == 'alias' ) {
							$message	=	CBTxt::Th( 'VALIDATION_ERROR_FIELD_ALIAS_EXISTS', "The profile url '[alias]' exists on this site.", array( '[alias]' => htmlspecialchars( $value ) ) );
						} else {
							$message	=	CBTxt::Th( 'UE_USERNAME_EXISTS_ON_SITE', "The username '[username]' exists on this site.", array( '[username]' =>  htmlspecialchars( $value ) ) );
						}
					} else {
						$valid			=	false;

						if ( $fieldName == 'alias' ) {
							$message	=	CBTxt::Th( 'VALIDATION_ERROR_FIELD_ALIAS_DOESNT_EXIST', "The profile url '[alias]' does not exist on this site.", array( '[alias]' => htmlspecialchars( $value ) ) );
						} else {
							$message	=	CBTxt::Th( 'UE_USERNAME_DOESNT_EXISTS', "The username '[username]' does not exist on this site.", array( '[username]' =>  htmlspecialchars( $value ) ) );
						}
					}
				} else {
					if ( $exists ) {
						$valid			=	false;

						if ( $fieldName == 'alias' ) {
							$message	=	CBTxt::Th( 'VALIDATION_ERROR_FIELD_ALIAS_NOT_AVAILABLE', "The profile url '[alias]' is already in use.", array( '[alias]' => htmlspecialchars( $value ) ) );
						} else {
							$message	=	CBTxt::Th( 'UE_USERNAME_NOT_AVAILABLE', "The username '[username]' is already in use.", array( '[username]' =>  htmlspecialchars( $value ) ) );
						}
					} else {
						if ( $fieldName == 'alias' ) {
							$message	=	CBTxt::Th( 'VALIDATION_ERROR_FIELD_ALIAS_AVAILABLE', "The profile url '[alias]' is available.", array( '[alias]' => htmlspecialchars( $value ) ) );
						} else {
							$message	=	CBTxt::Th( 'UE_USERNAME_AVAILABLE', "The username '[username]' is available.", array( '[username]' =>  htmlspecialchars( $value ) ) );
						}
					}
				}
			}
		}

		return json_encode( array( 'valid' => $valid, 'message' => $message ) );
	}

	/**
	 * Checks if the username exists or not
	 *
	 * @param $username
	 * @return int
	 */
	private function checkUsernameExists( $username )
	{
		global $_CB_database;

		static $cache			=	array();

		if ( ! isset( $cache[$username] ) ) {
			$query				=	'SELECT ' . $_CB_database->NameQuote( 'id' )
								.	"\n FROM " . $_CB_database->NameQuote( '#__users' )
								.	"\n WHERE " . $_CB_database->NameQuote( 'username' ) . " = " . $_CB_database->Quote( trim( $username ) );
			$_CB_database->setQuery( $query, 0, 1 );
			$cache[$username]	=	(int) $_CB_database->loadResult();
		}

		return $cache[$username];
	}

	/**
	 * Checks if the alias exists or not
	 *
	 * @param $alias
	 * @return int
	 */
	private function checkAliasExists( $alias )
	{
		global $_CB_database;

		static $cache			=	array();

		if ( ! isset( $cache[$alias] ) ) {
			$query				=	'SELECT ' . $_CB_database->NameQuote( 'id' )
								.	"\n FROM " . $_CB_database->NameQuote( '#__comprofiler' )
								.	"\n WHERE " . $_CB_database->NameQuote( 'alias' ) . " = " . $_CB_database->Quote( trim( $alias ) );
			$_CB_database->setQuery( $query, 0, 1 );
			$cache[$alias]		=	(int) $_CB_database->loadResult();
		}

		return $cache[$alias];
	}

	/**
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 */
	public function prepareFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		global $_CB_framework, $ueConfig;

		$this->_prepareFieldMetaSave( $field, $user, $postdata, $reason );

		switch ( $field->name ) {
			case 'username':
				if ( ! ( ( $ueConfig['usernameedit'] == 0 ) && ( $reason == 'edit' ) && ( $_CB_framework->getUi() == 1 ) ) ) {
					$username				=	stripslashes( cbGetParam( $postdata, 'username', null ) );

					if ( $this->validate( $field, $user, $field->name, $username, $postdata, $reason ) ) {
						if ( ( $username !== null ) && ( $username !== $user->username ) ) {
							$this->_logFieldUpdate( $field, $user, $reason, $user->username, $username );
						}
					}

					if ( $username !== null ) {
						$user->username		=	$username;
					}
				}
				break;
			case 'alias':
				if ( ! $_CB_framework->getCfg( 'sef' ) ) {
					// SEF isn't enabled so just do nothing:
					return;
				}

				$alias						=	Application::Router()->stringToAlias( stripslashes( cbGetParam( $postdata, 'alias', null ) ) );

				if ( $this->validate( $field, $user, $field->name, $alias, $postdata, $reason ) ) {
					if ( ( $alias !== null ) && ( $alias !== $user->alias ) ) {
						$this->_logFieldUpdate( $field, $user, $reason, $user->alias, $alias );
					}
				}

				if ( $alias !== null ) {
					$user->alias			=	$alias;
				}
				break;
			case 'name':
			case 'firstname':
			case 'middlename':
			case 'lastname':
				$value							=	stripslashes( cbGetParam( $postdata, $field->name ) );
				$col							=	$field->name;
				if ( $this->validate( $field, $user, $field->name, $value, $postdata, $reason ) ) {
					if ( ( (string) $user->$col ) !== (string) $value ) {
						$this->_logFieldUpdate( $field, $user, $reason, $user->$col, $value );
					}
				}
				if ( $value !== null ) {
					// Form name from first/middle/last name if needed:
					if ( $field->name !== 'name' ) {
						$nameArr				=	array();
						if ( $ueConfig['name_style'] >= 2 ) {
							$firstname		=	stripslashes( cbGetParam( $postdata, 'firstname', $user->firstname ) );
							if ( $firstname ) {
								$nameArr[]	=	 $firstname;
							}
							if ( $ueConfig['name_style'] == 3 ) {
								$middlename	=	stripslashes( cbGetParam( $postdata, 'middlename', $user->middlename ) );
								if ( $middlename ) {
									$nameArr[]	=	$middlename;
								}
							}
							$lastname		=	stripslashes( cbGetParam( $postdata, 'lastname', $user->lastname ) );
							if ( $lastname ) {
								$nameArr[]	=	$lastname;
							}
						}
						if ( count( $nameArr ) > 0 ) {
							$user->name			=	implode( ' ', $nameArr );
						}
					} else {
						// Form first/middle/last name from name if needed:
						$middleNamePos				=	strpos( $value, ' ' );
						$lastNamePos				=	strrpos( $value, ' ' );

						if ( $lastNamePos !== false ) {
							$user->firstname		=	substr( $value, 0, $middleNamePos );
							$user->lastname			=	substr( $value, ( $lastNamePos + 1 ) );

							if ( $middleNamePos !== $lastNamePos ) {
								$user->middlename	=	substr( $value, ( $middleNamePos + 1 ), ( $lastNamePos - $middleNamePos - 1 ) );
							} else {
								$user->middlename	=	'';
							}
						} else {
							$user->firstname		=	'';
							$user->middlename		=	'';
							$user->lastname			=	$value;
						}
					}

					$user->$col					=	$value;
				}
				break;

			default:
				$this->_setValidationError( $field, $user, $reason, sprintf(CBTxt::T( 'Unknown field %s' ), $field->name) );
				break;
		}
	}
	/**
	 * Validator:
	 * Validates $value for $field->required and other rules
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user        RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  string      $columnName  Column to validate
	 * @param  string      $value       (RETURNED:) Value to validate, Returned Modified if needed !
	 * @param  array       $postdata    Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason      'edit' for save profile edit, 'register' for registration, 'search' for searches
	 * @return boolean                  True if validate, $this->_setErrorMSG if False
	 */
	public function validate( &$field, &$user, $columnName, &$value, &$postdata, $reason ) {
		global $_CB_framework, $_CB_database;

		$validated				=	parent::validate( $field, $user, $columnName, $value, $postdata, $reason );
		$fieldName				=	$field->name;

		if ( $validated && in_array( $fieldName, array( 'username', 'alias' ) ) ) {
			if ( $fieldName == 'alias' ) {
				$aliasReg		=	'/(^[^a-zA-Z])|[^a-zA-Z0-9\-]/';

				if ( $_CB_framework->getCfg( 'unicodeslugs' ) == 1 ) {
					$aliasReg	=	'/(^[^a-zA-Z\p{L}])|[^a-zA-Z0-9\-\p{L}]/u';
				}

				if ( is_numeric( $value ) || preg_match( $aliasReg, $value ) ) {
					// Profile urls should be strictly alphanumeric with or without dashes:
					$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'VALIDATION_ERROR_FIELD_ALIAS', 'Please enter a valid alphanumeric profile url. Must begin with a letter and contain letters, numbers, and dashes only.' ) );
					return false;
				}

				if ( in_array( $value, Application::Router()->getViews() ) ) {
					// Profile urls can not match core views as it'd cause prefixed view parsing to kick in and unable to find the profile:
					$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'VALIDATION_ERROR_FIELD_ALIAS_NOT_AVAILABLE', "The profile url '[alias]' is already in use.", array( '[alias]' => htmlspecialchars( $value ) ) ) );
					return false;
				}
			} elseif ( preg_match( '#[<>"\'%;()&\\\\]|\\.\\./#', $value ) ) {
				$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'VALIDATION_ERROR_FIELD_USERNAME', 'Please enter a valid username with no space at beginning or end and must not contain the following characters: < > \ " \' % ; ( ) &' ) );

				return false;
			}

			if ( ( $value !== '' ) && ( $value !== null ) ) {
				if ( cbutf8_strtolower( trim( $value ) ) != cbutf8_strtolower( trim( $user->$fieldName ) ) ) {
					// Username and Profile URL mut be unique and be unique to each other:
					$exists		=	$this->checkUsernameExists( $value );

					if ( ! $exists ) {
						$exists	=	$this->checkAliasExists( $value );
					}

					if ( $exists ) {
						if ( $fieldName == 'alias' ) {
							$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'VALIDATION_ERROR_FIELD_ALIAS_NOT_AVAILABLE', "The profile url '[alias]' is already in use.", array( '[alias]' => htmlspecialchars( $value ) ) ) );
						} else {
							$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'UE_USERNAME_NOT_AVAILABLE', "The username '[username]' is already in use.", array( '[username]' =>  htmlspecialchars( $value ) ) ) );
						}

						return false;
					}
				}
			}
		}

		return $validated;
	}

	/**
	 * Returns the minimum field length as set
	 *
	 * @param  FieldTable  $field
	 * @return int
	 */
	public function getMinLength( $field ) {
		$min						=	parent::getMinLength( $field );

		if ( in_array( $field->name, array( 'username', 'alias' ) ) ) {
			if ( $min < 2 ) {
				$min				=	2;
			}
		}

		return $min;
	}

	/**
	 * Returns the maximum field length as set
	 *
	 * @param  FieldTable  $field
	 * @return int
	 */
	public function getMaxLength( $field ) {
		$maxLen						=	parent::getMaxLength( $field );
		if ( $maxLen ) {
			return $maxLen;
		}
		if ( in_array( $field->name, array( 'username', 'alias' ) ) ) {
			return 150;
		} else {
			return 100;
		}
	}
}
class CBfield_password extends CBfield_text {

	/**
	 * formats variable array into data attribute string
	 *
	 * @param  FieldTable   $field
	 * @param  UserTable    $user
	 * @param  string       $output
	 * @param  string       $reason
	 * @param  array        $attributeArray
	 * @return null|string
	 */
	protected function getDataAttributes( $field, $user, $output, $reason, $attributeArray = array() ) {
		if ( $field->params->get( 'passTestSrength', 0 ) && ( ! isset( $field->_identicalTo ) ) && ( ! isset( $field->_requiredIf ) ) ) {
			$attributeArray[]		=	cbValidator::getRuleHtmlAttributes( 'passwordstrength' );
		}

		if ( $field->params->get( 'fieldVerifyCurrent', 1 ) && ( isset( $field->_requiredIf ) ) ) {
			$attributeArray[]		=	cbValidator::getRuleHtmlAttributes( 'requiredif', '#' . $field->_requiredIf );

			// Only validate the required state so turn off the other validations:
			$field->maxlength		=	0;

			$field->params->set( 'fieldValidateExpression', '' );
			$field->params->set( 'fieldMinLength', 0 );
		}

		return parent::getDataAttributes( $field, $user, $output, $reason, $attributeArray );
	}

	/**
	 * Returns a PASSWORD field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output      'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $formatting  'table', 'td', 'span', 'div', 'none'
	 * @param  string      $reason      'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getFieldRow( &$field, &$user, $output, $formatting, $reason, $list_compare_types ) {
		global $ueConfig, $_CB_OneTwoRowsStyleToggle;

		$results										=	null;

		if ( $output == 'htmledit' ) {
			if ( ( $field->name != 'password' ) || ( $reason != 'register' ) || ! ( isset( $ueConfig['emailpass'] ) && ( $ueConfig['emailpass'] == "1" ) ) ) {
				$fieldTitle								=	$this->getFieldTitle( $field, $user, 'text', $reason );
				$fieldEditTitle							=	$fieldTitle;

				if ( $reason == 'edit' ) {
					$editTitle							=	$field->params->get( 'fieldEditTitle', null, GetterInterface::STRING );

					if ( $editTitle ) {
						$field->set( 'title', CBTxt::Th( $editTitle, null, array( '[title]' => $fieldTitle ) ) );

						$fieldEditTitle					=	$this->getFieldTitle( $field, $user, 'text', $reason );
					}
				}

				if ( $field->params->get( 'fieldVerifyInput', false, GetterInterface::BOOLEAN ) ) {
					$verifyField						=	new FieldTable( $field->getDbo() );

					foreach ( array_keys( get_object_vars( $verifyField ) ) as $k ) {
						$verifyField->$k				=	$field->$k;
					}

					$verifyField->set( 'name', $field->get( 'name', null, GetterInterface::STRING ) . '__verify' );
					$verifyField->set( 'fieldid', $field->get( 'fieldid', 0, GetterInterface::INT ) . '__verify' );
					$verifyField->set( 'params', new Registry( $field->params->asArray() ) );

					$titleOfVerifyField					=	$field->params->get( 'verifyPassTitle', null, GetterInterface::STRING );
					$descOfVerifyField					=	$field->params->get( 'verifyPassDesc', 'Please verify your new password.', GetterInterface::HTML );

					if ( $titleOfVerifyField ) {
						// Handles B/C legacy language strings and legacy %s usage in language string
						// CBTxt::Th( 'UE_VPASS', 'Verify Password' )
						// CBTxt::Th( '_UE_VERIFY_SOMETHING', 'Verify %s' )
						$verifyField->set( 'title', CBTxt::Th( $titleOfVerifyField, null, array( '%s' => $fieldEditTitle, '[title]' => $fieldEditTitle ) ) );
					} else {
						$verifyField->set( 'title', CBTxt::Th( '_UE_VERIFY_SOMETHING', 'Verify %s', array( '%s' => $fieldEditTitle, '[title]' => $fieldEditTitle ) ) );
					}

					if ( $descOfVerifyField ) {
						$verifyField->set( 'description', $descOfVerifyField );
					}

					$placeholderOfVerifyField			=	$field->params->get( 'verifyPassPlaceholder', null, GetterInterface::STRING );

					if ( $placeholderOfVerifyField ) {
						$verifyField->params->set( 'fieldPlaceholder', $placeholderOfVerifyField );
					}

					$verifyField->set( '_identicalTo', $field->get( 'name', null, GetterInterface::STRING ) );
				}

				$verifyCurr								=	( $field->params->get( 'fieldVerifyCurrent', 1 ) && ( $reason == 'edit' ) && ( ! Application::Cms()->getClientId() ) && ( $user->id == Application::MyUser()->getUserId() ) );

				if ( $verifyCurr ) {
					$verifyCurrField					=	new FieldTable( $field->getDbo() );

					foreach ( array_keys( get_object_vars( $verifyCurrField ) ) as $k ) {
						$verifyCurrField->$k			=	$field->$k;
					}

					$verifyCurrField->set( 'name', $field->get( 'name', null, GetterInterface::STRING ) . '__current' );
					$verifyCurrField->set( 'fieldid', $field->get( 'fieldid', 0, GetterInterface::INT ) . '__current' );
					$verifyCurrField->set( 'params', new Registry( $field->params->asArray() ) );

					$titleOfVerifyCurrentField			=	$field->params->get( 'verifyCurrentTitle', null, GetterInterface::STRING );
					$descOfVerifyCurrentField			=	$field->params->get( 'verifyCurrentDesc', 'Please verify your current password.', GetterInterface::HTML );

					if ( $titleOfVerifyCurrentField ) {
						$verifyCurrField->set( 'title', CBTxt::Th( $titleOfVerifyCurrentField, null, array( '[title]' => $fieldTitle ) ) );
					} else {
						$verifyCurrField->set( 'title', CBTxt::Th( 'VERIFY_CURRENT_SOMETHING', 'Current [title]', array( '[title]' => $fieldTitle ) ) );
					}

					if ( $descOfVerifyCurrentField ) {
						$verifyCurrField->set( 'description', $descOfVerifyCurrentField );
					}

					$placeholderOfVerifyCurrentField	=	$field->params->get( 'verifyCurrentPlaceholder', null, GetterInterface::STRING );

					if ( $placeholderOfVerifyCurrentField ) {
						$verifyCurrField->params->set( 'fieldPlaceholder', $placeholderOfVerifyCurrentField );
					}

					$verifyCurrField->set( '_requiredIf', $field->get( 'name', null, GetterInterface::STRING ) );
				}

				$toggleState							=	$_CB_OneTwoRowsStyleToggle;

				if ( $verifyCurr ) {
					$_CB_OneTwoRowsStyleToggle			=	$toggleState;

					$results							.=	parent::getFieldRow( $verifyCurrField, $user, $output, $formatting, $reason, $list_compare_types );

					unset( $verifyCurrField );
				}

				$results								.=	parent::getFieldRow( $field, $user, $output, $formatting, $reason, $list_compare_types );

				if ( $field->params->get( 'fieldVerifyInput', false, GetterInterface::BOOLEAN ) ) {
					$_CB_OneTwoRowsStyleToggle			=	$toggleState;

					$results							.=	parent::getFieldRow( $verifyField, $user, $output, $formatting, $reason, $list_compare_types );

					unset( $verifyField );
				}
			} else {
				// case of "sending password by email" at registration time for main password field:
				$results								=	parent::getFieldRow( $field, $user, $output, $formatting, $reason, $list_compare_types );
			}
		} else {
			$results									=	parent::getFieldRow( $field, $user, $output, $formatting, $reason, $list_compare_types );
		}

		return $results;
	}
	/**
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		global $ueConfig;

		$value									=	'';			// passwords are never sent back to forms.

		switch ( $output ) {
			case 'htmledit':
				if ( $reason == 'search' ) {
					return null;
				}

			if ( ( $field->name != 'password' ) || ( $reason != 'register' ) || ! ( isset( $ueConfig['emailpass'] ) && ( $ueConfig['emailpass'] == "1" ) ) ) {

					$req							=	$field->required;
					if ( ( $reason == 'edit' ) && in_array( $field->name, array( 'password', 'password__verify', 'password__current' ) ) ) {
						if ( checkJversion( '3.2+' ) && $user->requireReset ) {
							$field->required		=	1;
						} else {
							$field->required		=	0;
						}
					}

					$html							=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', $field->type, $value, $this->getDataAttributes( $field, $user, $output, $reason ) );
					$field->required				=	$req;

				} else {
					// case of "sending password by email" at registration time for main password field:
					$html							=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', 'html', CBTxt::Th( 'SENDING_PASSWORD', 'Your password will be sent to the above e-mail address.<br />Once you have received your new password you can log in and change it.' ), '' );
				}
				return $html;
				break;

			case 'html':
				return CBTxt::T( 'HIDDEN_CHARACTERS', '********' );
				break;
			default:
				return null;
				break;
		}
	}
	/**
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 */
	public function prepareFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		global $_CB_framework, $ueConfig;

		$this->_prepareFieldMetaSave( $field, $user, $postdata, $reason );

		// For CB main password don't save if it's on registration and passwords are auto-generated.
		if ( ( $reason == 'register' ) && ( $field->name == 'password' ) ) {
			if ( isset( $ueConfig['emailpass'] ) && ( $ueConfig['emailpass'] == "1" ) ) {
				return;
			}
		}

		foreach ( $field->getTableColumns() as $col ) {
			$value					=	stripslashes( cbGetParam( $postdata, $col,				'', _CB_ALLOWRAW ) );
			$valueVerify			=	stripslashes( cbGetParam( $postdata, $col . '__verify',	'', _CB_ALLOWRAW ) );
			$valueCurrent			=	stripslashes( cbGetParam( $postdata, $col . '__current',	'', _CB_ALLOWRAW ) );

			$fieldRequired			=	$field->required;

			if ( $_CB_framework->getUi() == 2 ) {
				$field->required	=	0;
			} elseif ( ( $reason == 'edit' ) && ( $user->id != 0 ) && ( $user->$col || ( $field->name == 'password' ) ) ) {
				if ( checkJversion( '3.2+' ) && $user->requireReset ) {
					// Password is required for password reset:
					$field->required	=	1;
				} else {
					$field->required	=	0;
				}
			}

			$this->validate( $field, $user, $col, $value, $postdata, $reason );

			if ( ( ( $reason == 'edit' ) && ( $user->id != 0 ) && ( $user->$col || ( $field->name == 'password' ) ) ) || ( $_CB_framework->getUi() == 2 ) ) {
				$field->required	=	$fieldRequired;
			}

			$fieldMinLength			=	$this->getMinLength( $field );

			$user->$col				=	null;		// don't update unchanged (hashed) passwords unless typed-in and all validates:
			if ( $value ) {
				if ( cbIsoUtf_strlen( $value ) < $fieldMinLength ) {
					$this->_setValidationError( $field, $user, $reason, sprintf( CBTxt::T( 'UE_VALID_PASS_CHARS', 'Please enter a valid %s.  No spaces, at least %s characters and contain lower and upper-case letters, numbers and special signs' ), CBTxt::T( 'UE_PASS', 'Password' ), $fieldMinLength ) );
				} elseif ( $field->params->get( 'fieldVerifyInput', false, GetterInterface::BOOLEAN ) && ( $value != $valueVerify ) ) {
					$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'UE_REGWARN_VPASS2', 'Password and verification do not match, please try again.' ) );
				} elseif ( $field->params->get( 'fieldVerifyCurrent', 1 ) && ( $reason == 'edit' ) && ( ! Application::Cms()->getClientId() ) && ( $user->id == Application::MyUser()->getUserId() ) && ( ! $user->verifyPassword( $valueCurrent ) ) ) {
					$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'Incorrect password, please try again.' ) );
				} else {
					if ( checkJversion( '3.2+' ) && $user->requireReset ) {
						// Password was changed and passed validation so turn off resetting:
						$user->requireReset	=	0;
					}

					// There is no event for password changes on purpose here !
					$user->$col		=	$value;			// store only if validated
				}
			}
		}
	}
	/**
	 * Finder:
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 * @return cbSqlQueryPart[]
	 */
	function bindSearchCriteria( &$field, &$user, &$postdata, $list_compare_types, $reason ) {
		return array();
	}
	/**
	 * Returns the minimum field length as set
	 *
	 * @param  FieldTable  $field
	 * @return int
	 */
	function getMinLength( $field ) {
		$defaultMin					=	6;
		return $field->params->get( 'fieldMinLength', $defaultMin );
	}
}
class CBfield_select_multi_radio extends cbFieldHandler {
	/**
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		global $_CB_framework;

		$value					=	$user->get( $field->name );

		switch ( $output ) {
			case 'html':
			case 'rss':
				global $_CB_database;

				static $fieldValues			=	array();

				$cacheId					=	(int) $field->fieldid;

				if ( ! isset( $fieldValues[$cacheId] ) ) {
					$_CB_database->setQuery( "SELECT fieldtitle, fieldlabel FROM #__comprofiler_field_values WHERE fieldid = " . $cacheId . " AND fieldtitle != '' AND fieldgroup = 0 ORDER BY ordering" );
					$fieldValues[$cacheId]	=	$_CB_database->loadObjectList();
				}

				$allValues					=	$fieldValues[$cacheId];

				if ( $value != '' ) {
					$chosen					=	$this->_explodeCBvalues( $value );
				} else {
					$chosen					=	array();
				}

				$class						=	trim( $field->params->get( 'field_display_class' ) );
				$displayStyle				=	$field->params->get( 'field_display_style' );
				$listType					=	( $displayStyle == 1 ? 'ul' : ( $displayStyle == 2 ? 'ol' : ( $displayStyle == 3 ? 'tag' : ', ' ) ) );
				$isTags						=	( $field->get( 'type', null, GetterInterface::STRING ) == 'tag' );

				foreach ( $chosen as $k => $v ) {
					foreach ( $allValues as $allValue ) {
						if ( $v != $allValue->fieldtitle ) {
							continue;
						}

						$chosen[$k]			=	( $allValue->fieldlabel == '' ? CBTxt::T( $allValue->fieldtitle ) : CBTxt::T( $allValue->fieldlabel ) );
						continue 2;
					}

					// We don't want to translate custom tag values supplied by the user; for other types lets fallback to translating encase it was a removed option:
					$chosen[$k]				=	( $isTags ? $v : CBTxt::T( $v ) );
				}

				return $this->formatFieldValueLayout( $this->_arrayToFormat( $field, $chosen, $output, $listType, $class ), $reason, $field, $user );

			/** @noinspection PhpMissingBreakStatementInspection */
			case 'htmledit':
				global $_CB_database;

				static $fieldOptions		=	array();

				$cacheId					=	(int) $field->fieldid;

				if ( ! isset( $fieldOptions[$cacheId] ) ) {
					$_CB_database->setQuery( "SELECT fieldtitle AS `value`, if ( fieldlabel != '', fieldlabel, fieldtitle ) AS `text`, `fieldgroup` AS `group` FROM #__comprofiler_field_values"		// id needed for the labels
											. "\n WHERE fieldid = " . $cacheId
											. "\n ORDER BY ordering" );
					$fieldOptions[$cacheId]	=	$_CB_database->loadObjectList();
				}

				$allValues					=	$fieldOptions[$cacheId];
/*
				if ( $reason == 'search' ) {
					array_unshift( $allValues, $this->_valueDoesntMatter( $field, $reason, ( $field->type == 'multicheckbox' ) ) );
					if ( ( $field->type == 'multicheckbox' ) && ( $value === null ) ) {
						$value	=	array( null );			// so that "None" is really not checked if not checked...
					}
				}
*/
				if ( $field->get( 'type', null, GetterInterface::STRING ) == 'tag' ) {
					static $loaded	=	0;

					if ( ! $loaded++ ) {
						$js			=	"$( '.cbSelectTag' ).cbselect({"
									.		"tags: true,"
									.		"language: {"
									.			"errorLoading: function() {"
									.				"return " . json_encode( CBTxt::T( 'The results could not be loaded.' ), JSON_HEX_TAG ) . ";"
									.			"},"
									.			"inputTooLong: function() {"
									.				"return " . json_encode( CBTxt::T( 'Search input too long.' ), JSON_HEX_TAG ) . ";"
									.			"},"
									.			"inputTooShort: function() {"
									.				"return " . json_encode( CBTxt::T( 'Search input too short.' ), JSON_HEX_TAG ) . ";"
									.			"},"
									.			"loadingMore: function() {"
									.				"return " . json_encode( CBTxt::T( 'Loading more results...' ), JSON_HEX_TAG ) . ";"
									.			"},"
									.			"maximumSelected: function() {"
									.				"return " . json_encode( CBTxt::T( 'You cannot select any more choices.' ), JSON_HEX_TAG ) . ";"
									.			"},"
									.			"noResults: function() {"
									.				"return " . json_encode( CBTxt::T( 'No results found.' ), JSON_HEX_TAG ) . ";"
									.			"},"
									.			"searching: function() {"
									.				"return " . json_encode( CBTxt::T( 'Searching...' ), JSON_HEX_TAG ) . ";"
									.			"}"
									.		"}"
									.	"});";

						$_CB_framework->outputCbJQuery( $js, 'cbselect' );
					}
				}

				if ( $reason == 'search' ) {
//					$html			=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', 'multicheckbox', $value, '', $allValues );
					$displayType	=	$field->type;
					if ( ( $field->type == 'radio' ) && ( ( $list_compare_types == 2 ) || ( is_array( $value ) && ( count( $value ) > 1 ) ) ) ) {
						$displayType	=	'multicheckbox';
					}
					if ( ( $field->type == 'select' ) && ( ( $list_compare_types == 1 ) || ( is_array( $value ) && ( count( $value ) > 1 ) ) ) ) {
						$displayType	=	'multiselect';
					}
					if ( in_array( $list_compare_types, array( 0, 2 ) ) && ( ! in_array( $displayType, array( 'multicheckbox', 'tag' ) ) ) ) {
						if ( $allValues && ( $allValues[0]->value == '' ) ) {
							// About to add 'No preference' so remove custom blank
							unset( $allValues[0] );
						}

						array_unshift( $allValues, moscomprofilerHTML::makeOption( '', 'UE_NO_PREFERENCE' ) ); // CBTxt::T( 'UE_NO_PREFERENCE', 'No preference' )
					}
					$html			=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', $displayType, $value, '', $allValues );
					$html			=	$this->_fieldSearchModeHtml( $field, $user, $html, ( ( ( strpos( $displayType, 'multi' ) === 0 ) && ( ! in_array( $field->type, array( 'radio', 'select' ) ) ) ) || ( $displayType == 'tag' ) ? 'multiplechoice' : 'singlechoice' ), $list_compare_types );
				} else {
					if ( $field->get( 'type', null, GetterInterface::STRING ) == 'tag' ) {
						// Since we're a tag usage we can have custom values so lets see if any exist to be added to available options:
						if ( $value != '' ) {
							$chosen						=	$this->_explodeCBvalues( $value );
						} else {
							$chosen						=	array();
						}

						foreach ( $chosen as $k => $v ) {
							foreach ( $allValues as $allValue ) {
								if ( $v != $allValue->value ) {
									// Custom values we'll add further below:
									continue;
								}

								// Skip values that actually exist:
								continue 2;
							}

							// Add custom tags to the available values list:
							$customValue				=	new stdClass();
							$customValue->value			=	$v;
							$customValue->text			=	$v;
							$customValue->group			=	0;

							$allValues[]				=	$customValue;
						}
					}

					if ( in_array( $field->type, array( 'multicheckbox', 'radio' ) ) && $field->params->get( 'field_edit_style', 0, GetterInterface::INT ) ) {
						$html		=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', $field->type . 'buttons', $value, ( in_array( $field->type, array( 'multicheckbox', 'multiselect', 'tag' ) ) ? $this->getDataAttributes( $field, $user, $output, $reason ) : '' ), $allValues );
					} else {
						$html		=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', $field->type, $value, ( in_array( $field->type, array( 'multicheckbox', 'multiselect', 'tag' ) ) ? $this->getDataAttributes( $field, $user, $output, $reason ) : '' ), $allValues );
					}
				}

				return $html;

			case 'xml':
			case 'json':
			/** @noinspection PhpMissingBreakStatementInspection */
			case 'php':
				if ( substr( $reason, -11 ) == ':translated' ) {
					// Translated version in case reason finishes by :translated: (will be used later):
					if ( in_array( $field->type, array( 'radio', 'select' ) ) ) {
						$chosen			=	CBTxt::T( $value );

						return $this->_formatFieldOutput( $field->name, $chosen, $output, ( $output != 'xml' ) );
					}

					// multiselect, multicheckbox, tag:
					$chosen			=	$this->_explodeCBvalues( $value );
					for( $i = 0, $n = count( $chosen ); $i < $n; $i++ ) {
						$chosen[$i]	=	CBTxt::T( $chosen[$i] );
					}

					return $this->_arrayToFormat( $field, $chosen, $output );
				}
				// else: fall-through on purpose here (fixes bug #2960):

			case 'csv':
				if ( in_array( $field->type, array( 'radio', 'select' ) ) ) {
					return $this->_formatFieldOutput( $field->name, $value, $output, ( $output != 'xml' ) );
				}

				// multiselect, multicheckbox, tag:
				$chosen			=	$this->_explodeCBvalues( $value );
				return $this->_arrayToFormat( $field, $chosen, $output );

			case 'csvheader':
			case 'fieldslist':
			default:
				return parent::getField( $field, $user, $output, $reason, $list_compare_types );
		}
	}
	/**
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 */
	public function prepareFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		global $_CB_database;

		$isTags							=	( $field->get( 'type', null, GetterInterface::STRING ) == 'tag' );

		$this->_prepareFieldMetaSave( $field, $user, $postdata, $reason );

		foreach ( $field->getTableColumns() as $col ) {
			$value						=	cbGetParam( $postdata, $col, null, _CB_ALLOWRAW );
//			if ( $value === null ) {
//				$value				=	array();
//			} elseif ( $field->type == 'radio' ) {
//				$value				=	array( $value );
//			}

			if ( is_array( $value ) ) {
				if ( count( $value ) > 0 ) {

					$_CB_database->setQuery( 'SELECT fieldtitle AS id FROM #__comprofiler_field_values'
											. "\n WHERE fieldid = " . (int) $field->fieldid
											. "\n AND fieldtitle != ''"
											. "\n AND fieldgroup = 0"
											. "\n ORDER BY ordering" );
					$authorizedValues	=	$_CB_database->loadResultArray();

					$okVals				=	array();
					foreach ( $value as $k => $v ) {
						// revert escaping of cbGetParam:
						$v				=	stripslashes( $v );
						// check for duplicates:
						if ( in_array( $v, $okVals, true ) )  {
							continue;
						}
						// check authorized values:
						if ( in_array( $v, $authorizedValues, true ) ) {
							$okVals[$k]	=	$v;
						} elseif ( $isTags ) {
							// Allow unauthorized values for tags, but clean them to strings:
							$okVals[$k]	=	Get::clean( $v, GetterInterface::STRING );
						}
					}
					$value				=	$this->_implodeCBvalues( $okVals );
				} else {
					$value				=	'';
				}
			} elseif ( ( $value === null ) || ( $value === '' ) ) {
				$value					=	'';
			} else {
				$value					=	stripslashes( $value );	// compensate for cbGetParam.
				$_CB_database->setQuery( 'SELECT fieldtitle AS id FROM #__comprofiler_field_values'
											. "\n WHERE fieldid = " . (int) $field->fieldid
											. "\n AND fieldtitle = " . $_CB_database->Quote( $value )
											. "\n AND fieldgroup = 0" );
				$authorizedValues		=	$_CB_database->loadResultArray();

				if ( ! in_array( $value, $authorizedValues, true ) ) {
					if ( $isTags ) {
						// Allow unauthorized value for tags, but clean it to string:
						$value			=	Get::clean( $value, GetterInterface::STRING );
					} else {
						$value			=	null;
					}
				}
			}
			if ( $this->validate( $field, $user, $col, $value, $postdata, $reason ) ) {
				if ( isset( $user->$col ) && ( (string) $user->$col ) !== (string) $value ) {
					$this->_logFieldUpdate( $field, $user, $reason, $user->$col, $value );
				}
			}
			$user->$col				=	$value;
		}
	}
	/**
	 * Finder:
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $searchVals  RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata    Typically $_POST (but not necessarily), filtering required.
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @param  string      $reason      'edit' for save profile edit, 'register' for registration, 'search' for searches
	 * @return cbSqlQueryPart[]
	 */
	function bindSearchCriteria( &$field, &$searchVals, &$postdata, $list_compare_types, $reason ) {
		global $_CB_database;

		$displayType						=	$field->type;
		if ( ( $field->type == 'radio' ) && ( $list_compare_types == 2 ) ) {
			$displayType	=	'multicheckbox';
		}

		$query								=	array();
		$searchMode							=	$this->_bindSearchMode( $field, $searchVals, $postdata, ( ( strpos( $displayType, 'multi' ) === 0 ) || ( $displayType == 'tag' ) ? 'multiplechoice' : 'singlechoice' ), $list_compare_types );
		if ( $searchMode ) {
			foreach ( $field->getTableColumns() as $col ) {
				$value						=	cbGetParam( $postdata, $col );
				if ( is_array( $value ) ) {
					if ( count( $value ) > 0 ) {
						$_CB_database->setQuery( 'SELECT fieldtitle AS id FROM #__comprofiler_field_values'
												. "\n WHERE fieldid = " . (int) $field->fieldid
												. "\n AND fieldtitle != ''"
												. "\n AND fieldgroup = 0"
												. "\n ORDER BY ordering" );
						$authorizedValues	=	$_CB_database->loadResultArray();

						foreach ( $value as $k => $v ) {
							if ( ( count( $value ) == 1 ) && ( $v === '' ) ) {
								if ( $list_compare_types == 1 ) {
									$value		=	'';		// Advanced search: "None": checked: search for nothing selected
								} else {
									$value		=	null;	// Type 0 and 2 : Simple search: "Do not care" checked: do not search
								}
								break;
							}
							// revert escaping of cbGetParam:
							$v				=	stripslashes( $v );
							// check authorized values:
							if ( in_array( $v, $authorizedValues ) ) {
								$value[$k]	=	$v;
							} elseif ( $displayType == 'tag' ) {
								// Allow unauthorized values for tags, but clean them to strings:
								$value[$k]	=	Get::clean( $v, GetterInterface::STRING );
							} else {
								unset( $value[$k] );
							}
						}

					} else {
						$value				=	null;
					}
					if ( ( $value !== null ) && ( $value !== '' ) && in_array( $searchMode, array( 'is', 'isnot' ) ) ) {		// keep $value array if search is not strict
						$value				=	stripslashes( $this->_implodeCBvalues( $value ) );	// compensate for cbGetParam.
					}
				} else {
					if ( ( $value !== null ) && ( $value !== '' ) ) {
						$value					=	stripslashes( $value );	// compensate for cbGetParam.
						$_CB_database->setQuery( 'SELECT fieldtitle AS id FROM #__comprofiler_field_values'
													. "\n WHERE fieldid = " . (int) $field->fieldid
													. "\n AND fieldtitle = " . $_CB_database->Quote( $value )
													. "\n AND fieldgroup = 0" );
						$authorizedValues	=	$_CB_database->loadResultArray();
						if ( ! in_array( $value, $authorizedValues ) ) {
							if ( $displayType == 'tag' ) {
								// Allow unauthorized value for tags, but clean it to string:
								$value			=	Get::clean( $value, GetterInterface::STRING );
							} else {
								$value			=	null;
							}
						}
					} else {
						if ( ( $list_compare_types == 1 ) && in_array( $searchMode, array( 'is', 'isnot' ) ) ) {
							$value			=	'';
						} else {
	//					if ( ( $field->type == 'multicheckbox' ) && ( $value === null ) ) {
							$value			=	null;				// 'none' is not checked and no other is checked: search for DON'T CARE
						}
					}
				}
				if ( $value !== null ) {
					$searchVals->$col		=	$value;
					// $this->validate( $field, $user, $col, $value, $postdata, $reason );
					$sql					=	new cbSqlQueryPart();
					$sql->tag				=	'column';
					$sql->name				=	$col;
					$sql->table				=	$field->table;
					$sql->type				=	'sql:field';
					$sql->operator			=	'=';
					$sql->value				=	$value;
					$sql->valuetype			=	'const:string';
					$sql->searchmode		=	$searchMode;
					$query[]				=	$sql;
				}
			}
		}
		return $query;
	}
}
class CBfield_checkbox extends cbFieldHandler {
	/**
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		$value			=	$user->get( $field->name );

		switch ( $output ) {
			case 'html':
			case 'rss':
				if ( $value == 1 ) {
					return $this->formatFieldValueLayout( CBTxt::T( 'UE_YES', 'Yes' ), $reason, $field, $user );
				} elseif ( $value == 0 ) {
					return $this->formatFieldValueLayout( CBTxt::T( 'UE_NO', 'No' ), $reason, $field, $user );
				} else {
					return $this->formatFieldValueLayout( null, $reason, $field, $user );
				}
				break;

			case 'htmledit':
				if ( $reason == 'search' ) {
					$choices	=	array();
					$choices[]	=	moscomprofilerHTML::makeOption( '', CBTxt::T( 'UE_NO_PREFERENCE', 'No preference' ) );
					$choices[]	=	moscomprofilerHTML::makeOption( '1', CBTxt::T( 'UE_YES', 'Yes' ) );
					$choices[]	=	moscomprofilerHTML::makeOption( '0', CBTxt::T( 'UE_NO', 'No' ) );
					$html		=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', 'select', $value, '', $choices, true, null, false );
					$html		=	$this->_fieldSearchModeHtml( $field, $user, $html, 'singlechoice', $list_compare_types );
					return $html;
				} else {
					if ( $field->params->get( 'field_edit_style', 0, GetterInterface::INT ) ) {
						return $this->_fieldEditToHtml( $field, $user, $reason, 'input', 'yesno', ( $value == 1 ? 1 : 0 ), '' );
					} else {
						$checked		=	'';
						if ( $value == 1 ) {
							$checked	=	' checked="checked"';
						}
						return $this->_fieldEditToHtml( $field, $user, $reason, 'input', 'checkbox', $value, $checked );
					}
				}
				break;

			case 'json':
				return "'" . $field->name . "' : " . (int) $value;
				break;

			case 'php':
				return array( $field->name => (int) $value );
				break;

			case 'xml':
			case 'csvheader':
			case 'fieldslist':
			case 'csv':
			default:
				return parent::getField( $field, $user, $output, $reason, $list_compare_types );
				break;
		}
	}
	/**
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 */
	public function prepareFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		$this->_prepareFieldMetaSave( $field, $user, $postdata, $reason );

		foreach ( $field->getTableColumns() as $col ) {
			$value					=	stripslashes( cbGetParam( $postdata, $col ) );

			if ( $value == '' ) {
				$value				=	0;
			} elseif ( $value == '1' ) {
				$value				=	1;
			}
			$validated				=	$this->validate( $field, $user, $col, $value, $postdata, $reason );
			if ( ( $value === 0 ) || ( $value === 1 ) ) {
				if ( $validated && isset( $user->$col ) && ( ( (string) $user->$col ) !== (string) $value ) ) {
					$this->_logFieldUpdate( $field, $user, $reason, $user->$col, $value );
				}
			}
			$user->$col				=	$value;
		}
	}
	/**
	 * Finder:
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $searchVals  RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata    Typically $_POST (but not necessarily), filtering required.
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @param  string      $reason      'edit' for save profile edit, 'register' for registration, 'search' for searches
	 * @return cbSqlQueryPart[]
	 */
	function bindSearchCriteria( &$field, &$searchVals, &$postdata, $list_compare_types, $reason ) {
		$query							=	array();
		$searchMode						=	$this->_bindSearchMode( $field, $searchVals, $postdata, 'none', $list_compare_types );
		if ( $searchMode ) {
			foreach ( $field->getTableColumns() as $col ) {
				$value						=	stripslashes( cbGetParam( $postdata, $col ) );
				if ( $value === '0' ) {
					$value				=	0;
				} elseif ( $value == '1' ) {
					$value				=	1;
				} else {
					continue;
				}
				$searchVals->$col		=	$value;
				// $this->validate( $field, $user, $col, $value, $postdata, $reason );
				$sql					=	new cbSqlQueryPart();
				$sql->tag				=	'column';
				$sql->name				=	$col;
				$sql->table				=	$field->table;
				$sql->type				=	'sql:field';
				$sql->operator			=	'=';
				$sql->value				=	$value;
				$sql->valuetype			=	'const:int';
				$sql->searchmode		=	$searchMode;
				$query[]				=	$sql;
			}
		}
		return $query;
	}
}
/**
 * Basic CB integer field extender.
 */
class CBfield_integer extends CBfield_text
{

	/**
	 * Accessor:
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types )
	{
		$value						=	$user->get( $field->name );

		switch ( $output ) {
			case 'htmledit':
				if ( $reason == 'search' ) {
					$minNam			=	$field->name . '__minval';
					$maxNam			=	$field->name . '__maxval';

					$minVal			=	$user->get( $minNam );
					$maxVal			=	$user->get( $maxNam );

					$fieldNameSave	=	$field->name;
					$field->name	=	$minNam;
					$minHtml		=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', 'number', $minVal, '' );
					$field->name	=	$maxNam;
					$maxHtml		=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', 'number', $maxVal, '' );
					$field->name	=	$fieldNameSave;

					return $this->_fieldSearchRangeModeHtml( $field, $user, $output, $reason, $value, $minHtml, $maxHtml, $list_compare_types );
				} else {
					if ( $field->get( 'type', null, GetterInterface::STRING ) == 'float' ) {
						$min		=	$field->params->get( 'integer_minimum', 0, GetterInterface::FLOAT );
						$value		=	(float) $value;
						$type		=	'float';
					} else {
						$min		=	$field->params->get( 'integer_minimum', 0, GetterInterface::INT );
						$value		=	(int) $value;
						$type		=	'integer';
					}

					if ( ( $min > 0 ) && ( (string) $value === '0' ) ) {
						// If the minimum does not allow for 0 and the value is 0 then treat it as null to allow range validation for non-required usage:
						$value		=	null;
					}

					return $this->_fieldEditToHtml( $field, $user, $reason, 'input', $type, $value, $this->getDataAttributes( $field, $user, $output, $reason ) );
				}
				break;
			case 'html':
			case 'rss':
				$thousandsSep		=	CBTxt::T( $field->params->get( 'fieldThousandsSeparator', '', GetterInterface::STRING ) );
				$decimalPoint		=	'';
				$decimals			=	0;

				if ( $field->get( 'type', null, GetterInterface::STRING ) == 'float' ) {
					$decimalPoint	=	CBTxt::T( $field->params->get( 'fieldDecimalsSeparator', '', GetterInterface::STRING ) );
					$decimals		=	strlen( substr( strrchr( (string) $field->params->get( 'integer_step', 0.01, GetterInterface::FLOAT ), '.' ), 1 ) );

					$value			=	(float) $value;
				} else {
					$value			=	(int) $value;
				}

				if ( $thousandsSep || $decimalPoint ) {
					$value			=	number_format( $value, $decimals, $decimalPoint, $thousandsSep );
				}

				return $this->formatFieldValueLayout( $this->_formatFieldOutput( $field->get( 'name', null, GetterInterface::STRING ), $value, $output, true ), $reason, $field, $user );
				break;
			case 'json':
			case 'php':
			case 'xml':
			case 'csvheader':
			case 'fieldslist':
			case 'csv':
			default:
				return parent::getField( $field, $user, $output, $reason, $list_compare_types );
				break;
		}
	}

	/**
	 * Mutator:
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 */
	public function prepareFieldDataSave( &$field, &$user, &$postdata, $reason )
	{
		$this->_prepareFieldMetaSave( $field, $user, $postdata, $reason );

		foreach ( $field->getTableColumns() as $col ) {
			$value					=	cbGetParam( $postdata, $col );

			if ( ! is_array( $value ) ) {
				$value				=	stripslashes( $value );
				$validated			=	$this->validate( $field, $user, $col, $value, $postdata, $reason );

				if ( $value !== null ) {
					if ( $field->get( 'type', null, GetterInterface::STRING ) == 'float' ) {
						$value		=	(float) $value;
					} else {
						$value		=	(int) $value;
					}

					if ( $validated && isset( $user->$col ) && ( ( (string) $user->$col ) !== (string) $value ) ) {
						$this->_logFieldUpdate( $field, $user, $reason, $user->$col, $value );
					}

					$user->$col		=	$value;
				}
			}
		}
	}

	/**
	 * Validator:
	 * Validates $value for $field->required and other rules
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user        RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  string      $columnName  Column to validate
	 * @param  string      $value       (RETURNED:) Value to validate, Returned Modified if needed !
	 * @param  array       $postdata    Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason      'edit' for save profile edit, 'register' for registration, 'search' for searches
	 * @return boolean                  True if validate, $this->_setErrorMSG if False
	 */
	public function validate( &$field, &$user, $columnName, &$value, &$postdata, $reason )
	{
		if ( ! parent::validate( $field, $user, $columnName, $value, $postdata, $reason ) ) {
			return false;
		}

		if ( ( $value !== '' ) && ( $value !== null ) ) {		// empty values (e.g. non-mandatory) are treated in the parent validation.
			if ( $field->get( 'type', null, GetterInterface::STRING ) == 'float' ) {
				if ( ! preg_match( '/^[-0-9.]*$/', $value ) ) {
					$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'Not a number' ) );

					return false;
				}

				$min			=	$field->params->get( 'integer_minimum', 0, GetterInterface::FLOAT );
				$max			=	$field->params->get( 'integer_maximum', 1000000, GetterInterface::FLOAT );
				$step			=	$field->params->get( 'integer_step', 0.01, GetterInterface::FLOAT );
				$value			=	(float) $value;
			} else {
				if ( ! preg_match( '/^[-0-9]*$/', $value ) ) {
					$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'Not an integer' ) );

					return false;
				}

				$min			=	$field->params->get( 'integer_minimum', 0, GetterInterface::INT );
				$max			=	$field->params->get( 'integer_maximum', 1000000, GetterInterface::INT );
				$step			=	$field->params->get( 'integer_step', 1, GetterInterface::INT );
				$value			=	(int) $value;
			}

			// Validate Min/Max Range:
			if ( $max < $min ) {
				$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'Min setting > Max setting !' ) );

				return false;
			}

			if ( $min && $max && ( ( $value < $min ) || ( $value > $max ) ) ) {
				$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'VALIDATION_ERROR_FIELD_BETWEEN_AND_NUMBER', 'Please enter a value between {0} and {1}.', array( '{0}' => $min, '{1}' => $max ) ) );

				return false;
			} elseif ( $min && ( $value < $min )  ) {
				$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'VALIDATION_ERROR_FIELD_GREATER_OR_EQUAL_TO', 'Please enter a value greater than or equal to {0}.', array( '{0}' => $min ) ) );

				return false;
			} elseif ( $max && ( $value > $max ) ) {
				$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'VALIDATION_ERROR_FIELD_LESS_OR_EQUAL_TO', 'Please enter a value less than or equal to {0}.', array( '{0}' => $max ) ) );

				return false;
			}

			// Validate Divisable by Step:
			if ( $value && $step ) {
				if ( $field->get( 'type', null, GetterInterface::STRING ) == 'float' ) {
					if ( ! ( abs( ( $value / $step ) - round( $value / $step ) ) < 0.0000001 ) ) {
						$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'VALIDATION_ERROR_FIELD_STEP', 'Please enter a multiple of {0}.', array( '{0}' => $step ) ) );

						return false;
					}
				} elseif ( ( abs( $value ) % $step ) != 0 ) {
					$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'VALIDATION_ERROR_FIELD_STEP', 'Please enter a multiple of {0}.', array( '{0}' => $step ) ) );

					return false;
				}
			}

			// Validate Forbidden Words:
			$forbiddenContent		=	$field->params->get( 'fieldValidateForbiddenList_' . $reason, '' );

			if ( $forbiddenContent != '' ) {
				$forbiddenContent	=	explode( ',', $forbiddenContent );

				if ( in_array( (string) $value, $forbiddenContent ) ) {
					$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'UE_INPUT_VALUE_NOT_ALLOWED', 'This input value is not authorized.' ) );

					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Finder:
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $searchVals  RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata    Typically $_POST (but not necessarily), filtering required.
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @param  string      $reason      'edit' for save profile edit, 'register' for registration, 'search' for searches
	 * @return cbSqlQueryPart[]
	 */
	function bindSearchCriteria( &$field, &$searchVals, &$postdata, $list_compare_types, $reason )
	{
		$query							=	array();

		foreach ( $field->getTableColumns() as $col ) {
			$minNam						=	$col . '__minval';
			$maxNam						=	$col . '__maxval';
			$searchMode					=	$this->_bindSearchRangeMode( $field, $searchVals, $postdata, $minNam, $maxNam, $list_compare_types );

			if ( $searchMode ) {
				if ( $field->get( 'type', null, GetterInterface::STRING ) == 'float' ) {
					$minVal				=	(float) cbGetParam( $postdata, $minNam, 0 );
					$maxVal				=	(float) cbGetParam( $postdata, $maxNam, 0 );
				} else {
					$minVal				=	(int) cbGetParam( $postdata, $minNam, 0 );
					$maxVal				=	(int) cbGetParam( $postdata, $maxNam, 0 );
				}

				if ( $minVal && ( cbGetParam( $postdata, $minNam, '' ) !== '' ) ) {
					$searchVals->$minNam =	$minVal;

					if ( $field->get( 'type', null, GetterInterface::STRING ) == 'float' ) {
						$operator		=	( $searchMode == 'isnot' ? ( abs( $minVal - $maxVal ) < 0.0000001 ? '<' : '<=' ) : '>=' );
					} else {
						$operator		=	( $searchMode == 'isnot' ? ( $minVal == $maxVal ? '<' : '<=' ) : '>=' );
					}

					$min				=	$this->_intToSql( $field, $col, $minVal, $operator, $searchMode );
				} else {
					$min				=	null;
				}

				if ( $maxVal && ( cbGetParam( $postdata, $maxNam, '' ) !== '' ) ) {
					$searchVals->$maxNam =	$maxVal;

					if ( $field->get( 'type', null, GetterInterface::STRING ) == 'float' ) {
						$operator		=	( $searchMode == 'isnot' ? ( ( abs( $maxVal - $minVal ) < 0.0000001 ? '<' : '<=' ) ? '>' : '>=' ) : '<=' );
					} else {
						$operator		=	( $searchMode == 'isnot' ? ( $maxVal == $minVal ? '>' : '>=' ) : '<=' );
					}

					$max				=	$this->_intToSql( $field, $col, $maxVal, $operator, $searchMode );
				} else {
					$max				=	null;
				}

				if ( $min && $max ) {
					$sql				=	new cbSqlQueryPart();
					$sql->tag			=	'column';
					$sql->name			=	$col;
					$sql->table			=	$field->table;
					$sql->type			=	'sql:operator';
					$sql->operator		=	( $searchMode == 'isnot' ? 'OR' : 'AND' );
					$sql->searchmode	=	$searchMode;

					$sql->addChildren( array( $min, $max ) );

					$query[]			=	$sql;
				} elseif ( $min ) {
					$query[]			=	$min;
				} elseif ( $max ) {
					$query[]			=	$max;
				}
			}
		}

		return $query;
	}

	/**
	 * Internal function to create an SQL query part based on a comparison operator
	 *
	 * @param  FieldTable  $field
	 * @param  string      $col
	 * @param  int         $value
	 * @param  string      $operator
	 * @param  string      $searchMode
	 * @return cbSqlQueryPart
	 */
	protected function _intToSql( &$field, $col, $value, $operator, $searchMode )
	{
		if ( $field->get( 'type', null, GetterInterface::STRING ) == 'float' ) {
			$value						=	(float) $value;
			$valueType					=	'const:float';
		} else {
			$value						=	(int) $value;
			$valueType					=	'const:int';
		}

		$sql							=	new cbSqlQueryPart();
		$sql->tag						=	'column';
		$sql->name						=	$col;
		$sql->table						=	$field->table;
		$sql->type						=	'sql:field';
		$sql->operator					=	$operator;
		$sql->value						=	$value;
		$sql->valuetype					=	$valueType;
		$sql->searchmode				=	$searchMode;

		return $sql;
	}
}

class CBfield_date extends cbFieldHandler {

	/**
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		global $_CB_framework;

		$value								=	$user->get( $field->get( 'name' ) );
		$return								=	null;

		switch ( $output ) {
			case 'html':
			case 'rss':
				if ( ( $value != null ) && ( $value != '' ) && ( $value != '0000-00-00 00:00:00' ) && ( $value != '0000-00-00' ) && ( $value != '00:00:00' ) ) {
					$offset					=	( $field->get( 'type', null, GetterInterface::STRING ) == 'date' ? false : $field->params->get( 'date_offset', true, GetterInterface::BOOLEAN ) );
					$showTime				=	( $field->get( 'type', null, GetterInterface::STRING ) == 'datetime' ? true : ( $field->get( 'type', null, GetterInterface::STRING ) == 'time' ? 2 : false ) );

					switch ( $field->params->get( 'field_display_by', 0, GetterInterface::INT ) ) {
						case 1: // Age
							$dateDiff		=	$_CB_framework->getUTCDateDiff( 'now', $value );
							$age			=	null;

							if ( $dateDiff ) {
								$age		=	$dateDiff->y;

								if ( $age < 0 ) {
									$age	=	null;
								}
							}

							$return			=	$this->formatFieldValueLayout( $age, $reason, $field, $user );
							break;
						case 2: // Timeago, with Ago
							$return			=	$this->formatFieldValueLayout( cbFormatDate( $value, $offset, 'timeago' ), $reason, $field, $user, false );
							break;
						case 6: // Timeago, without Ago
							$return			=	$this->formatFieldValueLayout( cbFormatDate( $value, $offset, 'exacttimeago' ), $reason, $field, $user, false );
							break;
						case 3: // Birthdate
							// Offset based off the profile owners timezone:
							$timeZone		=	JFactory::getUser( (int) $user->get( 'id' ) )->getParam( 'timezone' );

							if ( ! $timeZone ) {
								// If no profile timezone specified then offset based off site:
								$timeZone	=	JFactory::getConfig()->get( 'offset' );
							}

							$return			=	$this->formatFieldValueLayout( htmlspecialchars( cbFormatDate( $value, $offset, $showTime, 'F d', ' g:i A', $timeZone ) ), $reason, $field, $user );
							break;
						case 4: // Date
							$return			=	$this->formatFieldValueLayout( htmlspecialchars( cbFormatDate( $value, $offset, false ) ), $reason, $field, $user );
							break;
						case 5: // Custom
							$dateFormat		=	CBTxt::T( $field->params->get( 'custom_date_format', 'Y-m-d', GetterInterface::STRING ) );
							$timeFormat		=	CBTxt::T( $field->params->get( 'custom_time_format', 'H:i:s', GetterInterface::STRING ) );

							$return			=	$this->formatFieldValueLayout( htmlspecialchars( cbFormatDate( $value, $offset, $showTime, $dateFormat, $timeFormat ) ), $reason, $field, $user );
							break;
						default: // Date/Datetime
							$return			=	$this->formatFieldValueLayout( htmlspecialchars( cbFormatDate( $value, $offset, $showTime ) ), $reason, $field, $user );
							break;
					}
				} else {
					$return					=	$this->formatFieldValueLayout( '', $reason, $field, $user );
				}
				break;
			case 'htmledit':
				global $_CB_framework;

				$offset						=	( $field->get( 'type', null, GetterInterface::STRING ) == 'date' ? false : $field->params->get( 'date_offset', true, GetterInterface::BOOLEAN ) );
				$displayBy					=	$field->params->get( 'field_display_by', 0, GetterInterface::INT );
				$searchBy					=	$field->params->get( 'field_search_by', 0, GetterInterface::INT );

				if ( $displayBy == 1 ) { // Age
					$offset					=	false;
				}

				$dateFormat					=	null;
				$timeFormat					=	null;

				if ( $reason == 'search' ) {
					if ( $searchBy == 2 ) {
						$dateFormat			=	CBTxt::T( $field->params->get( 'custom_date_search_format', 'Y-m-d', GetterInterface::STRING ) );

						if ( $field->get( 'type', null, GetterInterface::STRING ) != 'date' ) {
							$timeFormat		=	CBTxt::T( $field->params->get( 'custom_time_search_format', 'H:i:s', GetterInterface::STRING ) );
						}
					}
				} else {
					if ( $field->params->get( 'field_edit_format', false, GetterInterface::BOOLEAN ) ) {
						$dateFormat			=	CBTxt::T( $field->params->get( 'custom_date_edit_format', 'Y-m-d', GetterInterface::STRING ) );

						if ( $field->get( 'type', null, GetterInterface::STRING ) != 'date' ) {
							$timeFormat		=	CBTxt::T( $field->params->get( 'custom_time_edit_format', 'H:i:s', GetterInterface::STRING ) );
						}
					}
				}

				$calendars					=	new cbCalendars( $_CB_framework->getUi(), $field->params->get( 'calendar_type', null, GetterInterface::STRING ), $dateFormat, $timeFormat );
				$showTime					=	( $field->get( 'type', null, GetterInterface::STRING ) == 'datetime' ? true : ( $field->get( 'type', null, GetterInterface::STRING ) == 'time' ? 2 : false ) );

				$translatedTitle			=	$this->getFieldTitle( $field, $user, 'html', $reason );
				$htmlDescription			=	$this->getFieldDescription( $field, $user, 'htmledit', $reason );
				$trimmedDescription			=	trim( strip_tags( $htmlDescription ) );
				$inputDescription			=	$field->params->get( 'fieldLayoutInputDesc', 1, GetterInterface::INT );

				$tooltip					=	( $trimmedDescription && $inputDescription ? cbTooltip( $_CB_framework->getUi(), $htmlDescription, $translatedTitle, null, null, null, null, 'data-hascbtooltip="true"' ) : null );

				if ( $reason == 'search' ) {
					$minNam					=	$field->get( 'name' ) . '__minval';
					$maxNam					=	$field->get( 'name' ) . '__maxval';

					$minVal					=	$user->get( $minNam );
					$maxVal					=	$user->get( $maxNam );

					list( $yMin, $yMax, $yDesc )	=	$this->_yearsRange( $field, $searchBy );

					if ( $searchBy == 1 ) {
						// Search by age range:
						$choices			=	array();

						for ( $i = $yMin ; $i <= $yMax ; $i++ ) {
							$choices[]		=	moscomprofilerHTML::makeOption( $i, $i );
						}

						if ( $minVal === null ) {
							$minVal			=	$yMin;
						}

						if ( $maxVal === null ) {
							$maxVal			=	$yMax;
						}

						$additional			=	' class="form-control"' . ( trim( $tooltip ) ? ' ' . $tooltip : null );
						$minHtml			=	moscomprofilerHTML::selectList( $choices, $minNam, $additional, 'text', 'value', $minVal, 2 );
						$maxHtml			=	moscomprofilerHTML::selectList( $choices, $maxNam, $additional, 'text', 'value', $maxVal, 2 );
					} else {
						if ( $minVal !== null ) {
							if ( $field->get( 'type' ) == 'datetime' ) {
								$minVal		=	$_CB_framework->getUTCDate( 'Y-m-d H:i:s', $minVal );
							} elseif ( $field->get( 'type' ) == 'time' ) {
								$minVal		=	$_CB_framework->getUTCDate( 'H:i:s', $minVal );
							} else {
								$minVal		=	$_CB_framework->getUTCDate( 'Y-m-d', $minVal );
							}
						}

						if ( $maxVal !== null ) {
							if ( $field->get( 'type' ) == 'datetime' ) {
								$maxVal		=	$_CB_framework->getUTCDate( 'Y-m-d H:i:s', $maxVal );
							} elseif ( $field->get( 'type' ) == 'time' ) {
								$maxVal		=	$_CB_framework->getUTCDate( 'H:i:s', $maxVal );
							} else {
								$maxVal		=	$_CB_framework->getUTCDate( 'Y-m-d', $maxVal );
							}
						}

						// Search by date range:
						$minHtml			=	$this->formatFieldValueLayout( $calendars->cbAddCalendar( $minNam, CBTxt::Th( 'UE_SEARCH_FROM', 'Between' ) . ' ' . $this->getFieldTitle( $field, $user, 'text', $reason ), false, $minVal, false, $showTime, ( $yDesc ? $yMax : $yMin ), ( $yDesc ? $yMin : $yMax ), $tooltip, $offset ), $reason, $field, $user );
						$maxHtml			=	$this->formatFieldValueLayout( $calendars->cbAddCalendar( $maxNam, CBTxt::Th( 'UE_SEARCH_TO', 'and' ) . ' ' . $this->getFieldTitle( $field, $user, 'text', $reason ), false, $maxVal, false, $showTime, ( $yDesc ? $yMax : $yMin ), ( $yDesc ? $yMin : $yMax ), $tooltip, $offset ), $reason, $field, $user );
					}

					$return					=	$this->_fieldSearchRangeModeHtml( $field, $user, $output, $reason, $value, $minHtml, $maxHtml, $list_compare_types );
				} elseif ( ( ! in_array( $field->get( 'name' ), array( 'registerDate', 'lastvisitDate', 'lastupdatedate' ) ) ) ) {
					$timeZone				=	null;

					if ( $displayBy == 3 ) { // Birthdate
						// Offset based off the profile owners timezone:
						$timeZone			=	JFactory::getUser( (int) $user->get( 'id' ) )->getParam( 'timezone' );

						if ( ! $timeZone ) {
							// If no profile timezone specified then offset based off site:
							$timeZone		=	JFactory::getConfig()->get( 'offset' );
						}
					}

					list( $yMin, $yMax, $yDesc )	=	$this->_yearsRange( $field, 0 );

					// Check for age validation:
					if ( ( $field->get( 'type', null, GetterInterface::STRING ) == 'date' ) && in_array( $displayBy, array( 1, 3 ) ) ) {
						$fieldId			=	$field->get( 'fieldid', 0, GetterInterface::INT );
						$minAge				=	$field->params->get( 'age_min', 0, GetterInterface::INT );
						$maxAge				=	$field->params->get( 'age_max', 0, GetterInterface::INT );

						if ( $minAge && $maxAge ) {
							// CBTxt::T( 'VALIDATION_ERROR_FIELD_AGE_RANGE', 'You must be at least {0} years old, but not older than {1}.' )
							$tooltip		.=	' ' . cbValidator::getRuleHtmlAttributes( 'rangeage', array( $minAge, $maxAge ), CBTxt::T( 'FIELD_' . $fieldId . '_VALIDATION_ERROR_FIELD_AGE_RANGE VALIDATION_ERROR_FIELD_AGE_RANGE', 'You must be at least {0} years old, but not older than {1}.' ) );
						} elseif ( $minAge ) {
							// CBTxt::T( 'VALIDATION_ERROR_FIELD_MIN_AGE', 'You must be at least {0} years old.' )
							$tooltip		.=	' ' . cbValidator::getRuleHtmlAttributes( 'minage', $minAge, CBTxt::T( 'FIELD_' . $fieldId . '_VALIDATION_ERROR_FIELD_MIN_AGE VALIDATION_ERROR_FIELD_MIN_AGE', 'You must be at least {0} years old.' ) );
						} elseif ( $maxAge ) {
							// CBTxt::T( 'VALIDATION_ERROR_FIELD_MAX_AGE', 'You must be no more than {0} years old.' )
							$tooltip		.=	' ' . cbValidator::getRuleHtmlAttributes( 'maxage', $maxAge, CBTxt::T( 'FIELD_' . $fieldId . '_VALIDATION_ERROR_FIELD_MAX_AGE VALIDATION_ERROR_FIELD_MAX_AGE', 'You must be no more than {0} years old.' ) );
						}
					}

					$return					=	$this->formatFieldValueLayout( $calendars->cbAddCalendar( $field->get( 'name' ), $this->getFieldTitle( $field, $user, 'text', $reason ), $this->_isRequired( $field, $user, $reason ), $value, $this->_isReadOnly( $field, $user, $reason ), $showTime, ( $yDesc ? $yMax : $yMin ), ( $yDesc ? $yMin : $yMax ), $tooltip, $offset, $timeZone ), $reason, $field, $user )
											.	$this->_fieldIconsHtml( $field, $user, $output, $reason, null, $field->get( 'type' ), $value, 'input', null, true, $this->_isRequired( $field, $user, $reason ) && ! $this->_isReadOnly( $field, $user, $reason ) );
				}
				break;
			case 'json':
			case 'php':
			case 'xml':
			case 'csvheader':
			case 'fieldslist':
			case 'csv':
			default:
				$return						=	parent::getField( $field, $user, $output, $reason, $list_compare_types );
				break;
		}

		return $return;
	}

	/**
	 * @param  FieldTable  $field
	 * @param  int         $outputMode
	 * @return array
	 */
	function _yearsRange( &$field, $outputMode ) {
		if ( $field->get( 'type', null, GetterInterface::STRING ) == 'time' ) {
			return array( 0, 0, false );
		}

		$yMin					=	$this->_yearSetting( $field->params->get( 'year_min', '-110' ), $outputMode );
		$yMax					=	$this->_yearSetting( $field->params->get( 'year_max', '+25' ), $outputMode );
		$yDesc					=	false;

		// Reverse min and max year for age display or if min year is greater than max year:
		if ( ( $outputMode == 1 ) || ( $yMin > $yMax ) ) {
			$temp				=	$yMin;
			$yMin				=	$yMax;
			$yMax				=	$temp;
			$yDesc				=	true;
		}

		return array( $yMin, $yMax, $yDesc );
	}

	/**
	 * @param  string  $setParam
	 * @param  int     $outputMode
	 * @return int|null
	 */
	function _yearSetting( $setParam, $outputMode ) {
		$yearSetting			=	trim( $setParam );
		$offset					=	null;
		$fullYear				=	null;

		if ( ! $yearSetting ) {
			$offset				=	0;
		} else {
			$sign				=	$yearSetting[0];

			if ( $sign == '+' ) {
				$offset			=	(int) substr( $yearSetting, 1 );
			} elseif ( $sign == '-' ) {
				$offset			=	- (int) substr( $yearSetting, 1 );
			} else {
				$fullYear		=	(int) $yearSetting;
			}
		}

		if ( $outputMode == 1 ) {
			if ( $offset === null ) {
				$offset			=	( $fullYear - (int) cbFormatDate( 'now', false, false, 'Y' ) );
			}

			return -$offset;
		} else {
			if ( $offset !== null ) {
				$fullYear		=	( (int) cbFormatDate( 'now', false, false, 'Y' ) + $offset );
			}

			return $fullYear;
		}
	}

	/**
	 * Labeller for title:
	 * Returns a field title
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'text' or: 'html', 'htmledit', (later 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist')
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @return string
	 */
	public function getFieldTitle( &$field, &$user, $output, $reason ) {
		$title			=	'';
		$byAge			=	( ( ( $output == 'html' ) || ( $output == 'rss' ) ) && ( $field->params->get( 'field_display_by', 0 ) > 0 ) ) || ( ( $reason == 'search' ) && ( $field->params->get( 'field_search_by', 0 ) == 1 ) );

		if ( $byAge ) {
			$title		=	$field->params->get( 'duration_title' );
		}

		if ( $title != '' ) {
			if ( $output === 'text' ) {
				return strip_tags( cbReplaceVars( $title, $user, true, true, array( 'reason' => $reason ) ) );
			} else {
				return cbReplaceVars( $title, $user, true, true, array( 'reason' => $reason ) );
			}
		} else {
			return parent::getFieldTitle( $field, $user, $output, $reason );
		}
	}

	/**
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 */
	public function prepareFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		$this->_prepareFieldMetaSave( $field, $user, $postdata, $reason );

		if ( ( ! in_array( $field->name, array( 'registerDate', 'lastvisitDate', 'lastupdatedate' ) ) ) ) {
			foreach ( $field->getTableColumns() as $col ) {
				$value				=	stripslashes( cbGetParam( $postdata, $col ) );
				$validated			=	$this->validate( $field, $user, $col, $value, $postdata, $reason );

				if ( $value !== null ) {
					if ( $validated && isset( $user->$col ) && ( ( (string) $user->$col ) !== (string) $value ) && ! ( ( ( $user->$col === '0000-00-00' ) || ( $user->$col === '00:00:00' ) || ( $user->$col === '0000-00-00 00:00:00' ) ) && ( $value == '' ) ) ) {
						$this->_logFieldUpdate( $field, $user, $reason, $user->$col, $value );
					}

					$user->$col		=	$value;
				}
			}
		}
	}

	/**
	 * Validator:
	 * Validates $value for $field->required and other rules
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user        RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  string      $columnName  Column to validate
	 * @param  string      $value       (RETURNED:) Value to validate, Returned Modified if needed !
	 * @param  array       $postdata    Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason      'edit' for save profile edit, 'register' for registration, 'search' for searches
	 * @return boolean                  True if validate, $this->_setErrorMSG if False
	 */
	public function validate( &$field, &$user, $columnName, &$value, &$postdata, $reason ) {
		$validate								=	parent::validate( $field, $user, $columnName, $value, $postdata, $reason );

		if ( $validate && ( $value !== null ) ) {
			if ( $field->get( 'type', null, GetterInterface::STRING ) == 'time' ) {
				$hour							=	substr( $value, 0, 2 );

				if ( ( $hour == '' ) || ( $hour == '00' ) ) {
					if ( $this->_isRequired( $field, $user, $reason ) ) {
						$this->_setValidationError( $field, $user, $reason, cbUnHtmlspecialchars( CBTxt::T( 'UE_REQUIRED_ERROR', 'This field is required!' ) ) );

						$validate				=	false;
					}
				}
			} else {
				$year							=	substr( $value, 0, 4 );

				if ( ( $year == '' ) || ( $year == '0000' ) ) {
					if ( $this->_isRequired( $field, $user, $reason ) ) {
						$this->_setValidationError( $field, $user, $reason, cbUnHtmlspecialchars( CBTxt::T( 'UE_REQUIRED_ERROR', 'This field is required!' ) ) );

						$validate				=	false;
					}
				} else {
					// check range:
					list( $yMin, $yMax )		=	$this->_yearsRange( $field, 0 );

					if ( ( $year < $yMin ) || ( $year > $yMax ) ) {
						$this->_setValidationError( $field, $user, $reason, sprintf( CBTxt::T( 'UE_YEAR_NOT_IN_RANGE', 'Year %s is not between %s and %s' ), (int) $year, (int) $yMin, (int) $yMax ) );
						$validate				=	false;
					}

					// check age:
					if ( ( $field->get( 'type', null, GetterInterface::STRING ) == 'date' ) && in_array( $field->params->get( 'field_display_by', 0, GetterInterface::INT ), array( 1, 3 ) ) ) {
						$fieldId				=	$field->get( 'fieldid', 0, GetterInterface::INT );
						$minAge					=	$field->params->get( 'age_min', 0, GetterInterface::INT );
						$maxAge					=	$field->params->get( 'age_max', 0, GetterInterface::INT );

						if ( $minAge || $maxAge ) {
							$dateDiff			=	Application::Date( 'now', 'UTC' )->diff( $value );
							$age				=	0;

							if ( $dateDiff ) {
								$age			=	(int) $dateDiff->y;

								if ( $age < 0 ) {
									$age		=	0;
								}
							}

							if ( $minAge && $maxAge ) {
								if ( ( $age < $minAge ) || ( $age > $maxAge ) ) {
									// CBTxt::T( 'AGE_TOO_YOUNG_OR_OLD', 'Age [age] is too young or old. You must be at least [min] years old, but not older than [max].', array( '[age]' => $age, '[min]' => $minAge, '[max]' => $maxAge ) )
									$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'FIELD_' . $fieldId . '_AGE_TOO_YOUNG_OR_OLD AGE_TOO_YOUNG_OR_OLD', 'Age [age] is too young or old. You must be at least [min] years old, but not older than [max].', array( '[age]' => $age, '[min]' => $minAge, '[max]' => $maxAge ) ) );

									$validate	=	false;
								}
							} elseif ( $minAge ) {
								if ( $age < $minAge ) {
									// CBTxt::T( 'AGE_TOO_YOUNG', 'Age [age] is too young. You must be at least [min] years old.', array( '[age]' => $age, '[min]' => $minAge, '[max]' => $maxAge ) )
									$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'FIELD_' . $fieldId . '_AGE_TOO_YOUNG AGE_TOO_YOUNG', 'Age [age] is too young. You must be at least [min] years old.', array( '[age]' => $age, '[min]' => $minAge, '[max]' => $maxAge ) ) );

									$validate	=	false;
								}
							} elseif ( $maxAge ) {
								if ( $age > $maxAge ) {
									// CBTxt::T( 'AGE_TOO_OLD', 'Age [age] is too old. You must be no more than [max] years old.', array( '[age]' => $age, '[min]' => $minAge, '[max]' => $maxAge ) )
									$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'FIELD_' . $fieldId . '_AGE_TOO_OLD AGE_TOO_OLD', 'Age [age] is too old. You must be no more than [max] years old.', array( '[age]' => $age, '[min]' => $minAge, '[max]' => $maxAge ) ) );

									$validate	=	false;
								}
							}
						}
					}
				}
			}
		}

		return $validate;
	}

	/**
	 * Finder:
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $searchVals  RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata    Typically $_POST (but not necessarily), filtering required.
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @param  string      $reason      'edit' for save profile edit, 'register' for registration, 'search' for searches
	 * @return cbSqlQueryPart[]
	 */
	function bindSearchCriteria( &$field, &$searchVals, &$postdata, $list_compare_types, $reason ) {
		global $_CB_framework;

		$searchBy										=	$field->params->get( 'field_search_by', 0 );

		list( $yMinMin, $yMaxMax )						=	$this->_yearsRange( $field, $searchBy );

		$query											=	array();

		foreach ( $field->getTableColumns() as $col ) {
			$minNam										=	$col . '__minval';
			$maxNam										=	$col . '__maxval';
			$searchMode									=	$this->_bindSearchRangeMode( $field, $searchVals, $postdata, $minNam, $maxNam, $list_compare_types );

			if ( $searchMode ) {
				if ( $searchBy == 1 ) {
					// search by years:
					if ( $field->type == 'datetime' ) {
						list( $y, $c, $d, $h, $m, $s )	=	sscanf( $_CB_framework->getUTCDate( 'Y-m-d H:i:s' ), '%d-%d-%d %d:%d:%d' );
					} elseif ( $field->type == 'time' ) {
						list( $h, $m, $s )				=	sscanf( $_CB_framework->getUTCDate( 'H:i:s' ), '%d:%d:%d' );
						$y								=	null;
						$c								=	null;
						$d								=	null;
					} else {
						list( $y, $c, $d )				=	sscanf( $_CB_framework->getUTCDate( 'Y-m-d' ), '%d-%d-%d' );
						$h								=	null;
						$m								=	null;
						$s								=	null;
					}

					$minValIn							=	(int) cbGetParam( $postdata, $minNam, 0 );
					$maxValIn							=	(int) cbGetParam( $postdata, $maxNam, 0 );
					$ageMin								=	$minValIn;
					$ageMax								=	$maxValIn;

					if ( $ageMin == $ageMax ) {
						// We're searching for an exact age (e.g. min 30 and max 30) which causes >= 30 <= 30 and does not make sense for age date ranges; so lets add 1 year to the max:
						$ageMax++;
					}

					if ( ( $ageMax && ( $ageMax <= $yMaxMax ) ) && ( $ageMin && ( $ageMin > $yMinMin ) ) ) {
						$yMax							=	( $y - $ageMin );

						if ( $field->type == 'datetime' ) {
							$maxVal						=	sprintf( '%04d-%02d-%02d %02d:%02d:%02d', $yMax, $c, $d, $h, $m, $s );
						} elseif ( $field->type == 'time' ) {
							$maxVal						=	sprintf( '%02d:%02d:%02d', $h, $m, $s );
						} else {
							$maxVal						=	sprintf( '%04d-%02d-%02d', $yMax, $c, $d );
						}
					} else {
						$maxVal							=	null;
					}

					if ( ( $ageMin && ( $ageMin >= $yMinMin ) ) && ( $ageMax && ( $ageMax < $yMaxMax ) ) ) {
						$yMin							=	( $y - $ageMax );

						if ( $field->type == 'datetime' ) {
							$minVal						=	sprintf( '%04d-%02d-%02d %02d:%02d:%02d', $yMin, $c, $d, $h, $m, $s );
						} elseif ( $field->type == 'time' ) {
							$minVal						=	sprintf( '%02d:%02d:%02d', $h, $m, $s );
						} else {
							$minVal						=	sprintf( '%04d-%02d-%02d', $yMin, $c, $d );
						}
					} else {
						$minVal							=	null;
					}
				} else {
					$minVal								=	stripslashes( cbGetParam( $postdata, $minNam ) );
					$maxVal								=	stripslashes( cbGetParam( $postdata, $maxNam ) );
					$minValIn							=	$minVal;
					$maxValIn							=	$maxVal;
				}

				if ( $field->type == 'datetime' ) {
					$minSearch							=	( $minVal && ( $minVal !== '0000-00-00 00:00:00' ) );
					$maxSearch							=	( $maxVal && ( $maxVal !== '0000-00-00 00:00:00' ) );
				} elseif ( $field->type == 'time' ) {
					$minSearch							=	( $minVal && ( $minVal !== '00:00:00' ) );
					$maxSearch							=	( $maxVal && ( $maxVal !== '00:00:00' ) );
				} else {
					$minSearch							=	( $minVal && ( $minVal !== '0000-00-00' ) );
					$maxSearch							=	( $maxVal && ( $maxVal !== '0000-00-00' ) );
				}

				$forceMin								=	( ( ! $minSearch ) && $maxSearch && ( ! in_array( $field->name, array( 'lastupdatedate', 'lastvisitDate' ) ) ) );

				if ( $minSearch || $forceMin ) {
					$min								=	new cbSqlQueryPart();
					$min->tag							=	'column';
					$min->name							=	$col;
					$min->table							=	$field->table;
					$min->type							=	'sql:field';
					$min->operator						=	( ! $forceMin ? ( $searchMode == 'isnot' ? '<=' : '>=' ) : '>' );

					if ( $field->type == 'datetime' ) {
						$min->value						=	( ! $forceMin ? $minVal : '0000-00-00 00:00:00' );
						$min->valuetype					=	'const:datetime';
					} elseif ( $field->type == 'time' ) {
						$min->value						=	( ! $forceMin ? $minVal : '00:00:00' );
						$min->valuetype					=	'const:time';
					} else {
						$min->value						=	( ! $forceMin ? $minVal : '0000-00-00' );
						$min->valuetype					=	'const:date';
					}

					$min->searchmode					=	$searchMode;

					if ( ! $forceMin ) {
						if ( ( ! $maxVal ) && $maxValIn ) {
							$searchVals->$maxNam		=	$maxValIn;
						}

						$searchVals->$minNam			=	$minValIn;
					}
				}

				if ( $maxSearch ) {
					$max								=	new cbSqlQueryPart();
					$max->tag							=	'column';
					$max->name							=	$col;
					$max->table							=	$field->table;
					$max->type							=	'sql:field';
					$max->operator						=	( $searchMode == 'isnot' ? '>=' : '<=' );
					$max->value							=	$maxVal;

					if ( $field->type == 'datetime' ) {
						$max->valuetype					=	'const:datetime';
					} elseif ( $field->type == 'time' ) {
						$max->valuetype					=	'const:time';
					} else {
						$max->valuetype					=	'const:date';
					}

					$max->searchmode					=	$searchMode;

					if ( ( ! $minVal ) && $minValIn ) {
						$searchVals->$minNam			=	$minValIn;
					}

					$searchVals->$maxNam				=	$maxValIn;
				}

				if ( isset( $min ) && isset( $max ) ) {
					$sql								=	new cbSqlQueryPart();
					$sql->tag							=	'column';
					$sql->name							=	$col;
					$sql->table							=	$field->table;
					$sql->type							=	'sql:operator';
					$sql->operator						=	( $searchMode == 'isnot' ? 'OR' : 'AND' );
					$sql->searchmode					=	$searchMode;

					$sql->addChildren( array( $min, $max ) );

					$query[]							=	$sql;
				} elseif ( isset( $min ) ) {
					$query[]							=	$min;
				} elseif ( isset( $max ) ) {
					$query[]							=	$max;
				}
			}
		}

		return $query;
	}
}

class CBfield_editorta extends cbFieldHandler {
	/**
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		global $_CB_framework;

		$value							=	$user->get( implode( '', $field->getTableColumns() ) );

		switch ( $output ) {
			case 'html':
			case 'rss':
				$html					=	$this->formatFieldValueLayout( Get::clean( $value, GetterInterface::HTML ), $reason, $field, $user, false );
				unset( $cbFields );
				break;
			case 'htmledit':
				if ( $reason == 'search' ) {
					$rows				=	$field->rows;
					if ( $rows > 5 ) {
						$field->rows	=	5;
					}
					$html				=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', 'textarea', $value, '' );
					$field->rows		=	$rows;
					$html				=	$this->_fieldSearchModeHtml( $field, $user, $html, 'text', $list_compare_types );
				} elseif ( ! ( $this->_isReadOnly( $field, $user, $reason ) ) ) {
					$value				=	Get::clean( $value, GetterInterface::HTML );
					unset( $cbFields );

					$translatedTitle	=	$this->getFieldTitle( $field, $user, 'html', $reason );
					$htmlDescription	=	$this->getFieldDescription( $field, $user, 'htmledit', $reason );
					$trimmedDescription	=	trim( strip_tags( $htmlDescription ) );
					$inputDescription	=	$field->params->get( 'fieldLayoutInputDesc', 1, GetterInterface::INT );

					$editor				=	Application::Cms()->displayCmsEditor( $field->name, $value, 600, 350, $field->cols, $field->rows );

					$html				=	$this->formatFieldValueLayout( ( $trimmedDescription && $inputDescription ? cbTooltip( $_CB_framework->getUi(), $htmlDescription, $translatedTitle, null, null, $editor, null, 'class="d-block clearfix"' ) : $editor ), $reason, $field, $user, false )
										.	$this->_fieldIconsHtml( $field, $user, $output, $reason, null, $field->type, $value, 'input', null, true, ( $this->_isRequired( $field, $user, $reason ) && ( ! $this->_isReadOnly( $field, $user, $reason ) ) ) );
					$this->_addSaveAndValidateCode( $field, $user, $reason );
				} else {
					$html				=	null;
				}
				break;

			case 'json':
			case 'php':
			case 'xml':
			case 'csvheader':
			case 'fieldslist':
			case 'csv':
			default:
				return parent::getField( $field, $user, $output, $reason, $list_compare_types );
				break;
		}
		return $html;
	}

	/**
	 * Adds validation and saving Javascript
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $reason
	 * @return void
	 */
	function _addSaveAndValidateCode( $field, $user, $reason ) {
		global $_CB_framework;

		$js				=	null;

		if ( $this->_isRequired( $field, $user, $reason ) ) {
			$js			.=	"$( '#" . addslashes( $field->name ) . "' ).addClass( 'required' );";
		}

		if ( $js ) {
			$_CB_framework->outputCbJQuery( $js );
		}
	}
	/**
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 */
	public function prepareFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		$this->_prepareFieldMetaSave( $field, $user, $postdata, $reason );

		foreach ( $field->getTableColumns() as $col ) {
			$value						=	stripslashes( cbGetParam( $postdata, $col, '', _CB_ALLOWRAW ) );
			if ( $value !== null ) {
				$value					=	Get::clean( $value, GetterInterface::HTML );
			}
			$validated					=	$this->validate( $field, $user, $col, $value, $postdata, $reason );
			if ( $value !== null ) {
				if ( $validated && isset( $user->$col ) && ( ( (string) $user->$col ) !== (string) $value ) ) {
					$this->_logFieldUpdate( $field, $user, $reason, $user->$col, $value );
				}
				$user->$col				=	$value;
			}
		}
	}
}
class CBfield_email extends CBfield_text {

	/**
	 * formats variable array into data attribute string
	 *
	 * @param  FieldTable   $field
	 * @param  UserTable    $user
	 * @param  string       $output
	 * @param  string       $reason
	 * @param  array        $attributeArray
	 * @return null|string
	 */
	protected function getDataAttributes( $field, $user, $output, $reason, $attributeArray = array() ) {
		if ( $field->params->get( 'field_check_email', 0 ) && ( ! isset( $field->_identicalTo ) ) ) {
			$attributeArray[]	=	cbValidator::getRuleHtmlAttributes( 'cbfield', array( 'user' => (int) $user->id, 'field' => htmlspecialchars( $field->name ), 'reason' => htmlspecialchars( $reason ) ) );
		}

		return parent::getDataAttributes( $field, $user, $output, $reason, $attributeArray );
	}

	/**
	 * Returns a PASSWORD field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output      'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $formatting  'table', 'td', 'span', 'div', 'none'
	 * @param  string      $reason      'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getFieldRow( &$field, &$user, $output, $formatting, $reason, $list_compare_types ) {
		global $_CB_OneTwoRowsStyleToggle;

		$results								=	null;

		if ( $output == 'htmledit' ) {
			if ( ( $reason != 'search' ) && $field->params->get( 'fieldVerifyInput', 0 ) ) {
				$verifyField					=	new FieldTable( $field->getDbo() );

				foreach ( array_keys( get_object_vars( $verifyField ) ) as $k ) {
					$verifyField->$k			=	$field->$k;
				}

				$verifyName						=	$field->name . '__verify';
				$verifyField->name				=	$verifyName;
				$verifyField->fieldid			=	$field->fieldid . '__verify';

				// cbReplaceVars to be done only once later:
				$titleOfVerifyField			=	$field->params->get( 'verifyEmailTitle' );
				if ( $titleOfVerifyField ) {
					$verifyField->title		=	CBTxt::Th( $titleOfVerifyField, null, array( '%s' => CBTxt::T( $field->title ) ) );
				} else {
					$verifyField->title		=	CBTxt::Th( '_UE_VERIFY_SOMETHING', 'Verify %s', array( '%s' => CBTxt::T( $field->title ) ) );
				}

				$verifyField->_identicalTo		=	$field->name;

				$toggleState					=	$_CB_OneTwoRowsStyleToggle;

				$results						=	parent::getFieldRow( $field, $user, $output, $formatting, $reason, $list_compare_types );

				$_CB_OneTwoRowsStyleToggle		=	$toggleState;

				$user->set( $verifyName, $user->get( $field->name ) );

				$results						.=	parent::getFieldRow( $verifyField, $user, $output, $formatting, $reason, $list_compare_types );

				unset( $verifyField );
				unset( $user->$verifyName );
			} else {
				$results						=	parent::getFieldRow( $field, $user, $output, $formatting, $reason, $list_compare_types );
			}
		} else {
			$results							=	parent::getFieldRow( $field, $user, $output, $formatting, $reason, $list_compare_types );
		}
		return $results;
	}
	/**
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		global $_CB_framework, $ueConfig;

		$value								=	$user->get( $field->name );

		switch ( $output ) {
			case 'html':
			case 'rss':
				$useLayout						=	true;

				if ( $field->type == 'primaryemailaddress' ) {
					$imgMode					=	$field->get( '_imgMode', null, GetterInterface::INT ); // For B/C

					if ( $imgMode === null ) {
						$imgMode				=	$field->params->get( ( $reason == 'list' ? 'displayModeList' : 'displayMode' ), 0, GetterInterface::INT );
					}

					if ( ( $ueConfig['allow_email_display'] == 3 ) || ( $imgMode != 0 ) ) {
						$oValueText				=	CBTxt::T( 'UE_SENDEMAIL', 'Send Email' );
					} else {
						$oValueText				=	htmlspecialchars( $value );
					}

					$emailIMG					=	'<span class="fa fa-envelope"' . ( $ueConfig['allow_email_display'] != 1 ? ' title="' . htmlspecialchars( CBTxt::T( 'UE_SENDEMAIL', 'Send Email' ) ) . '"' : null ) . '></span>';

					switch ( $imgMode ) {
						case 1:
							$useLayout			=	false; // We don't want to use layout for icon only display as we use it externally
							$linkItemImg		=	$emailIMG;
							$linkItemSep		=	null;
							$linkItemTxt		=	null;
							break;
						case 2:
							$linkItemImg		=	$emailIMG;
							$linkItemSep		=	' ';
							$linkItemTxt		=	$oValueText;
							break;
						case 0:
						default:
							$linkItemImg		=	null;
							$linkItemSep		=	null;
							$linkItemTxt		=	$oValueText;
							break;
					}
					$oReturn					=	'';
					//if no email or 4 (do not display email) then return empty string
					if ( ( $value == null ) || ( $ueConfig['allow_email_display'] == 4 ) || ( ( $imgMode == 1 ) && ( $ueConfig['allow_email_display'] == 1 ) ) ) {
						// $oReturn				=	'';
					} else {
						switch ( $ueConfig['allow_email_display'] ) {
							case 1: //display email only
								$oReturn		=	( $linkItemImg ? $linkItemImg . $linkItemSep : null )
												.	moscomprofilerHTML::emailCloaking( htmlspecialchars( $value ), 0 );
								break;
							case 2: //mailTo link
								// cloacking doesn't cloack the text of the hyperlink, if that text does contain email addresses		//TODO: fix it.
								if ( ! $linkItemImg && $linkItemTxt == htmlspecialchars( $value ) ) {
									$oReturn	=	moscomprofilerHTML::emailCloaking( htmlspecialchars( $value ), 1, '', 0 );
								} elseif ( $linkItemImg && $linkItemTxt != htmlspecialchars( $value ) ) {
									$oReturn	=	moscomprofilerHTML::emailCloaking( htmlspecialchars( $value ), 1, $linkItemImg . $linkItemSep . $linkItemTxt, 0 );
								} elseif ( $linkItemImg && $linkItemTxt == htmlspecialchars( $value ) ) {
									$oReturn 	=	moscomprofilerHTML::emailCloaking( htmlspecialchars( $value ), 1, $linkItemImg, 0 ) . $linkItemSep;
									$oReturn	.=	moscomprofilerHTML::emailCloaking( htmlspecialchars( $value ), 1, '', 0 );
								} elseif ( ! $linkItemImg && $linkItemTxt != htmlspecialchars( $value ) ) {
									$oReturn	=	moscomprofilerHTML::emailCloaking( htmlspecialchars( $value ), 1, $linkItemTxt, 0 );
								}
								break;
							case 3: //email Form (with cloacked email address if visible)
								$oReturn		=	"<a href=\""
												.	$_CB_framework->viewUrl( array( 'emailuser', 'uid' => $user->id ) )
												.	"\" title=\"" . CBTxt::T( 'UE_MENU_SENDUSEREMAIL_DESC', 'Send an Email to this user' ) . "\">" . $linkItemImg . $linkItemSep;
								if ( $linkItemTxt && ( $linkItemTxt != CBTxt::T( 'UE_SENDEMAIL', 'Send Email' ) ) ) {
									$oReturn	.=	moscomprofilerHTML::emailCloaking( $linkItemTxt, 0 );
								} else {
									$oReturn	.=	$linkItemTxt;
								}
								$oReturn		.=	"</a>";
								break;
						}
					}

				} else {

					// emailaddress:
					if ( $value == null ) {
						$oReturn				=	'';
					} else {
						if ( $ueConfig['allow_email'] == 1 ) {
							$oReturn			=	moscomprofilerHTML::emailCloaking( htmlspecialchars( $value ), 1, "", 0 );
						} else {
							$oReturn			=	moscomprofilerHTML::emailCloaking( htmlspecialchars( $value ), 0 );
						}
					}

				}

				if ( $useLayout ) {
					$oReturn					=	$this->formatFieldValueLayout( $oReturn, $reason, $field, $user );
				}
				break;

			case 'htmledit':
				$oReturn						=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', 'email', $value, ( $reason != 'search' ? $this->getDataAttributes( $field, $user, $output, $reason ) : null ) );

				if ( $reason == 'search' ) {
					$oReturn					=	$this->_fieldSearchModeHtml( $field, $user, $oReturn, 'text', $list_compare_types );
				}
				break;

			case 'json':
			case 'php':
			case 'xml':
			case 'csvheader':
			case 'fieldslist':
			case 'csv':
			default:
				$oReturn				=	parent::getField( $field, $user, $output, $reason, $list_compare_types );
				break;
		}
		return $oReturn;
	}
	/**
	 * Direct access to field for custom operations, like for Ajax
	 *
	 * WARNING: direct unchecked access, except if $user is set, then check
	 * that the logged-in user has rights to edit that $user.
	 *
	 * @param FieldTable     $field
	 * @param null|UserTable $user
	 * @param array          $postdata
	 * @param string         $reason 'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'search' for searches (always public!)
	 * @return string                  Expected output.
	 */
	public function fieldClass( &$field, &$user, &$postdata, $reason ) {
		global $_CB_framework, $_CB_database, $ueConfig, $_GET;

		parent::fieldClass( $field, $user, $postdata, $reason ); // Performs spoof check

		if ( ! in_array( $reason, array( 'edit', 'register' ) ) ) {
			return null; // wrong reason; do nothing
		}

		$function								=	cbGetParam( $_GET, 'function', '' );

		if ( ! in_array( $function, array( 'checkvalue', 'testexists' ) ) ) {
			return null; // wrong funcion; do nothing
		}

		$emailChecker							=	$field->params->get( 'field_check_email', 0 );

		if ( ! $emailChecker ) {
			return null; // email checking is disabled; do nothing
		}

		$valid									=	true;
		$message								=	null;
		$email									=	stripslashes( cbGetParam( $postdata, 'value', '' ) );
		$emailConfirmation						=	( ( $field->name == 'email' ) && $ueConfig['reg_confirmation'] );

		foreach ( $field->getTableColumns() as $col ) {
			if ( ( ! $user ) || ( strtolower( trim( $email ) ) != strtolower( trim( $user->$col ) ) ) ) {
				if ( ! $this->validate( $field, $user, $col, $email, $postdata, $reason ) ) {
					global $_PLUGINS;

					$valid						=	false;
					$message					=	$_PLUGINS->getErrorMSG( '<br />' );
				} else {
					// Advanced:
					if ( $emailChecker == 2 ) {
						$query					=	'SELECT COUNT(*)'
												.	"\n FROM " . $_CB_database->NameQuote( $field->table );
						if ( $_CB_database->isDbCollationCaseInsensitive() ) {
							$query				.=	"\n WHERE " . $_CB_database->NameQuote( $col ) . " = " . $_CB_database->Quote( trim( $email ) );
						} else {
							$query				.=	"\n WHERE LOWER( " . $_CB_database->NameQuote( $col ) . " ) = " . $_CB_database->Quote( strtolower( trim( $email ) ) );
						}
						$_CB_database->setQuery( $query );
						$exists					=	$_CB_database->loadResult();

						if ( $function == 'testexists' ) {
							if ( $exists ) {
								$message		=	CBTxt::Th( 'UE_EMAIL_EXISTS_ON_SITE', "The email '[email]' exists on this site.", array( '[email]' =>  htmlspecialchars( $email ) ) );
							} else {
								$valid			=	false;
								$message		=	CBTxt::Th( 'UE_EMAIL_DOES_NOT_EXISTS_ON_SITE', "The email '[email]' does not exist on this site.", array( '[email]' =>  htmlspecialchars( $email ) ) );
							}
						} else {
							if ( $exists ) {
								$valid			=	false;
								$message		=	CBTxt::Th( 'UE_EMAIL_NOT_AVAILABLE', "The email '[email]' is already in use.", array( '[email]' =>  htmlspecialchars( $email ) ) );
							} else {
								$message		=	CBTxt::Th( 'UE_EMAIL_AVAILABLE', "The email '[email]' is available.", array( '[email]' =>  htmlspecialchars( $email ) ) );
							}
						}
					}

					// Simple:
					if ( ( $function != 'testexists' ) && $valid ) {
						$checkResult			=	cbCheckMail( $_CB_framework->getCfg( 'mailfrom' ), $email );

						switch ( $checkResult ) {
							case -2: // Wrong Format
								$valid			=	false;
								$message		=	CBTxt::Th( 'UE_EMAIL_NOVALID', 'This is not a valid email address.', array( '[email]' =>  htmlspecialchars( $email ) ) );
								break;
							case -1: // Couldn't Check
								break;
							case 0: // Invalid
								$valid			=	false;

								if ( $emailConfirmation ) {
									$message	=	CBTxt::Th( 'UE_EMAIL_INCORRECT_CHECK_NEEDED', 'This address does not accept email: Needed for confirmation.', array( '[email]' =>  htmlspecialchars( $email ) ) );
								} else {
									$message	=	CBTxt::Th( 'UE_EMAIL_INCORRECT_CHECK', 'This email does not accept email: Please check.', array( '[email]' =>  htmlspecialchars( $email ) ) );
								}
								break;
						}
					}
				}
			}
		}

		return json_encode( array( 'valid' => $valid, 'message' => $message ) );
	}

	/**
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 */
	public function prepareFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		$this->_prepareFieldMetaSave( $field, $user, $postdata, $reason );

		foreach ( $field->getTableColumns() as $col ) {
			$value					=	stripslashes( cbGetParam( $postdata, $col ) );
			$valueVerify			=	stripslashes( cbGetParam( $postdata, $col . '__verify' ) );

			if ( $value !== null ) {
				$value				=	str_replace( array( 'mailto:', 'http://', 'https://' ), '', $value );
			}

			if ( $valueVerify !== null ) {
				$valueVerify		=	str_replace( array( 'mailto:', 'http://', 'https://' ), '', $valueVerify );
			}

			$validated				=	$this->validate( $field, $user, $col, $value, $postdata, $reason );

			if ( $value !== null ) {
				if ( $validated && isset( $user->$col ) ) {
					if ( ( $reason != 'search' ) && $field->params->get( 'fieldVerifyInput', 0 ) && ( $value != $valueVerify ) ) {
						$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'Email and verification do not match, please try again.' ) );
					} elseif ( ( (string) $user->$col ) !== (string) $value ) {
						$this->_logFieldUpdate( $field, $user, $reason, $user->$col, $value );
					}
				}

				$user->$col			=	$value;
			}
		}
	}
	/**
	 * Validator:
	 * Validates $value for $field->required and other rules
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user        RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  string      $columnName  Column to validate
	 * @param  string      $value       (RETURNED:) Value to validate, Returned Modified if needed !
	 * @param  array       $postdata    Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason      'edit' for save profile edit, 'register' for registration, 'search' for searches
	 * @return boolean                            True if validate, $this->_setErrorMSG if False
	 */
	public function validate( &$field, &$user, $columnName, &$value, &$postdata, $reason ) {
		$validate	=	parent::validate( $field, $user, $columnName, $value, $postdata, $reason );
		if ( $validate && ( $value != null ) ) {
			if ( ! cbIsValidEmail( $value ) ) {
				$this->_setValidationError( $field, $user, $reason, sprintf( CBTxt::T( 'UE_EMAIL_NOVALID', 'This is not a valid email address.' ), htmlspecialchars( $value ) ) );
				$validate				=	false;
			}
		}
		return $validate;
	}
}
class CBfield_webaddress extends CBfield_text {

	/**
	 * formats variable array into data attribute string
	 *
	 * @param  FieldTable   $field
	 * @param  UserTable    $user
	 * @param  string       $output
	 * @param  string       $reason
	 * @param  array        $attributeArray
	 * @return null|string
	 */
	protected function getDataAttributes( $field, $user, $output, $reason, $attributeArray = array() ) {
		$attributeArray[]	=	cbValidator::getRuleHtmlAttributes( 'cburl' );

		return parent::getDataAttributes( $field, $user, $output, $reason, $attributeArray );
	}

	/**
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		global $ueConfig;

		$value						=	$user->get( $field->name );

		switch ( $output ) {
			case 'html':
			case 'rss':
				if ( $value == null ) {
					return $this->formatFieldValueLayout( '', $reason, $field, $user );
				} elseif ( $ueConfig['allow_website'] == 1 ) {
					$oReturn		=	$this->_explodeCBvalues( $value );
					if ( count( $oReturn ) < 2) {
						$oReturn[1]	=	$oReturn[0];
					}

					$scheme			=	parse_url( $oReturn[0], PHP_URL_SCHEME );

					if ( $scheme && ( ! in_array( $scheme, array( 'http', 'https' ) ) ) ) {
						// Stored scheme is invalid so remove it:
						$scheme		=	null;
						$oReturn[0]	=	preg_replace( '%^(?:(?:.(?<!^http|^https))+:(?://)?)%', '', $oReturn[0] );
					}

					return $this->formatFieldValueLayout( '<a href="' . htmlspecialchars( ( ! $scheme ? 'http://' : null ) . $oReturn[0] ) . '" target="_blank" rel="' . ( (int) $field->params->get( 'webaddress_nofollow', 1 ) ? 'nofollow ' : null ) . ( (int) $field->params->get( 'webaddress_noreferrer', 1 ) ? 'noreferrer ' : null ) . 'noopener">' . htmlspecialchars( $oReturn[1] ) . '</a>', $reason, $field, $user );
				} else {
					return $this->formatFieldValueLayout( htmlspecialchars( $value ), $reason, $field, $user );
				}
				break;

			case 'htmledit':
				if ( $field->params->get( 'webaddresstypes', 0 ) != 2 ) {
					$oReturn			=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', 'text', $value, ( $reason != 'search' ? $this->getDataAttributes( $field, $user, $output, $reason ) : null ) );
				} else {
					$oValuesArr			=	$this->_explodeCBvalues( $value );

					if ( count( $oValuesArr ) < 2 ) {
						$oValuesArr[1]	=	'';
					}

					$oReturn			=	'<div class="form-group row no-gutters cb_form_line">'
										.		'<label for="' . htmlspecialchars( $field->name ) . '" class="col-form-label col-sm-3 pr-sm-2">' . CBTxt::Th( 'UE_WEBURL', 'Address of Site' ) . '</label>'
										.		'<div class="cb_field col-sm-9">'
										.			$this->_fieldEditToHtml( $field, $user, $reason, 'input', 'text', $oValuesArr[0], ( $reason != 'search' ? $this->getDataAttributes( $field, $user, $output, $reason ) : null ) )
										.		'</div>'
										.	'</div>';

					$saveFieldName		=	$field->name;
					$saveFieldTitle		=	$field->title;
					$field->name		=	$saveFieldName . 'Text';
					$field->title		=	$field->title . ': ' . CBTxt::Th( 'UE_WEBTEXT', 'Name of Site');

					$oReturn			.=	'<div class="form-group row no-gutters mb-0 cb_form_line">'
										.		'<label for="' . htmlspecialchars( $field->name ) . '" class="col-form-label col-sm-3 pr-sm-2">' . CBTxt::Th( 'UE_WEBTEXT', 'Name of Site' ) . '</label>'
										.		'<div class="cb_field col-sm-9">'
										.			$this->_fieldEditToHtml( $field, $user, $reason, 'input', 'text', $oValuesArr[1], '' )
										.		'</div>'
										.	'</div>';

					$field->name		=	$saveFieldName;
					$field->title		=	$saveFieldTitle;
				}

				if ( $reason == 'search' ) {
					$oReturn			=	$this->_fieldSearchModeHtml( $field, $user, $oReturn, 'text', $list_compare_types );
				}
				return $oReturn;
				break;

			case 'json':
			case 'php':
			case 'xml':
			case 'csvheader':
			case 'fieldslist':
			case 'csv':
			default:
				return parent::getField( $field, $user, $output, $reason, $list_compare_types );
				break;
		}
	}
	/**
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 */
	public function prepareFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		$this->_prepareFieldMetaSave( $field, $user, $postdata, $reason );

		foreach ( $field->getTableColumns() as $col ) {
			$value						=	stripslashes( cbGetParam( $postdata, $col, '' ) );
			$valueText					=	stripslashes( cbGetParam( $postdata, $col . 'Text', '' ) );

			if ( $value !== null ) {
				$value					=	preg_replace( '%^(?:(?:.(?<!^http|^https))+:(?://)?)%', '', $value );

				if ( $valueText ) {
					$oValuesArr			=	array();
					$oValuesArr[0]		=	$value;
					$oValuesArr[1]		=	preg_replace( '%^(?:(?:.(?<!^http|^https))+:(?://)?)%', '', $valueText );
					$value				=	$this->_implodeCBvalues( $oValuesArr );
				}
			}
			$validated					=	$this->validate( $field, $user, $col, $value, $postdata, $reason );
			if ( $value !== null ) {
				if ( $validated && isset( $user->$col ) && ( ( (string) $user->$col ) !== (string) $value ) ) {
					$this->_logFieldUpdate( $field, $user, $reason, $user->$col, $value );
				}
				$user->$col				=	$value;
			}
		}
	}
}
class CBfield_pm extends cbFieldHandler {

	/**
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		global $_CB_framework, $_CB_PMS;

		$oReturn								=	null;

		if ( ! $_CB_PMS ) {
			return $oReturn;
		}

		$pmLinks								=	$_CB_PMS->getPMSlinks( $user->id, $_CB_framework->myId(), null, null, 1 ) ;

		if ( count( $pmLinks ) > 0 ) {
			switch ( $output ) {
				case 'html':
				case 'rss':
					$imgMode					=	$field->get( '_imgMode', null, GetterInterface::INT ); // For B/C

					if ( $imgMode === null ) {
						$imgMode				=	$field->params->get( ( $reason == 'list' ? 'displayModeList' : 'displayMode' ), 0, GetterInterface::INT );
					}

					$pmIMG						=	'<span class="fa fa-comment" title="' . htmlspecialchars( CBTxt::T( '_UE_PM_USER', 'Send Private Message' ) ) . '"></span>';
					$useLayout					=	true;

					foreach ( $pmLinks as $pmLink ) {
					 	if ( is_array( $pmLink ) ) {
							switch ( $imgMode ) {
								default:
								case 0:
									$linkItem	=	$pmLink['caption'];		// Already translated in PMS plugin
									break;
								case 1:
									$useLayout	=	false; // We don't want to use layout for icon only display as we use it externally
									$linkItem	=	$pmIMG;
									break;
								case 2:
									$linkItem	=	$pmIMG . ' ' . $pmLink['caption'];
									break;
							}

							$oReturn			.=	'<a href="' . cbSef( $pmLink['url'] ) . '" title="' . htmlspecialchars( $pmLink['tooltip'] ) . '">' . $linkItem . '</a>';
					 	}
					}

					if ( $useLayout ) {
						$oReturn				=	$this->formatFieldValueLayout( $oReturn, $reason, $field, $user );
					}
					break;
				case 'htmledit':
					$oReturn					=	null;
					break;
				case 'json':
				case 'php':
				case 'xml':
				case 'csvheader':
				case 'fieldslist':
				case 'csv':
				default:
					$retArray					=	array();

					foreach ( $pmLinks as $pmLink ) {
					 	if ( is_array( $pmLink ) ) {
							$title				=	cbReplaceVars( $pmLink['caption'], $user );
							$url				=	cbSef( $pmLink['url'] );
							$description		=	cbReplaceVars( $pmLink['tooltip'], $user );

	 						$retArray[]			=	array( 'title' => $title, 'url' => $url, 'tooltip' => $description );
					 	}
					}

					$oReturn					=	$this->_linksArrayToFormat( $field, $retArray, $output );
					break;
			}
		}

		return $oReturn;
	}

	/**
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 */
	public function prepareFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		$this->_prepareFieldMetaSave( $field, $user, $postdata, $reason );
		// on purpose don't log field update
		// nothing to do, PM fields don't save :-)
	}

	/**
	 * Finder:
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 * @return cbSqlQueryPart[]
	 */
	function bindSearchCriteria( &$field, &$user, &$postdata, $list_compare_types, $reason ) {
		return array();
	}
}
/**
 * Avatar
 */
class CBfield_image extends cbFieldHandler {

	/**
	 * @param  FieldTable  $field
	 * @param  string      $name
	 * @param  null        $default
	 * @return null|string
	 */
	function _getImageFieldParam( &$field, $name, $default = null ) {
		global $ueConfig;

		$fieldDefault				=	'';

		if ( $field->getString( 'name' ) === 'avatar' ) {
			switch ( $name ) {
				case 'avatarHeight':
					$fieldDefault	=	160;
					break;
				case 'avatarWidth':
					$fieldDefault	=	160;
					break;
				case 'thumbHeight':
					$fieldDefault	=	80;
					break;
				case 'thumbWidth':
					$fieldDefault	=	80;
					break;
			}
		} elseif ( $field->getString( 'name' ) === 'canvas' ) {
			switch ( $name ) {
				case 'avatarHeight':
					$fieldDefault	=	640;
					break;
				case 'avatarWidth':
					$fieldDefault	=	1280;
					break;
				case 'thumbHeight':
					$fieldDefault	=	320;
					break;
				case 'thumbWidth':
					$fieldDefault	=	640;
					break;
			}
		}

		$paramValue					=	$field->params->get( $name, $fieldDefault );

		if ( $paramValue == '' ) {
			if ( isset( $ueConfig[$name] ) ) {
				$paramValue			=	$ueConfig[$name];
			} else {
				$paramValue			=	$default;
			}
		}

		return $paramValue;
	}

	/**
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		global $_CB_framework;

		switch ( $output ) {
			case 'html':
			case 'rss':
				$thumbnail			=	$field->get( '_imageThumbnail', ( $reason != 'profile' ) );
				$oReturn			=	$this->_avatarHtml( $field, $user, $reason, $thumbnail, 2 );

				$name				=	$field->name;
				$nameapproved		=	$field->name . 'approved';
				//Application::MyUser()->isSuperAdmin()
				if ( ( $reason == 'profile' ) && ( $user->$name != '' ) && ( $user->$nameapproved == 0 ) && Application::MyUser()->isModeratorFor( Application::User( (int) $user->id ) ) ) {
					$oReturn		=	'<span class="cbImagePendingApproval">'
									.		$oReturn . ' ' . $this->_avatarHtml( $field, $user, $reason, false, 10 )
									.		'<div class="cbImagePendingApprovalButtons">'
									.			'<input type="button" class="btn btn-sm btn-success cbImagePendingApprovalAccept" value="' . htmlspecialchars( CBTxt::Th( 'UE_APPROVE', 'Approve' ) ) . '" onclick="location.href=\'' . $_CB_framework->viewUrl( 'approveimage', true, array( 'flag' => 1, 'images[' . (int) $user->id . '][]' => $name ) ) . '\';" />'
									.			' <input type="button" class="btn btn-sm btn-danger cbImagePendingApprovalReject" value="' . htmlspecialchars( CBTxt::Th( 'UE_REJECT', 'Reject' ) ) . '" onclick="location.href=\'' . $_CB_framework->viewUrl( 'approveimage', true, array( 'flag' => 0, 'images[' . (int) $user->id . '][]' => $name ) ) . '\';" />'
									.		'</div>'
									.	'</span>';
				}
				$oReturn			=	$this->formatFieldValueLayout( $oReturn, $reason, $field, $user );
				break;

			case 'htmledit':
				if ( $reason == 'search' ) {
					$choices		=	array();
					$choices[]		=	moscomprofilerHTML::makeOption( '', CBTxt::T( 'UE_NO_PREFERENCE', 'No preference' ) );
					$choices[]		=	moscomprofilerHTML::makeOption( '1', CBTxt::T( 'UE_HAS_PROFILE_IMAGE', 'Has a profile image' ) );
					$choices[]		=	moscomprofilerHTML::makeOption( '0', CBTxt::T( 'UE_HAS_NO_PROFILE_IMAGE', 'Has no profile image' ) );
					$col			=	$field->name;
					$value			=	$user->$col;
					$html			=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', 'select', $value, '', $choices, true, null, false );
					$html			=	$this->_fieldSearchModeHtml( $field, $user, $html, 'singlechoice', $list_compare_types );		//TBD: Has avatarapproved...
				} else {
					$html			=	$this->formatFieldValueLayout( $this->_htmlEditForm( $field, $user, $reason ), $reason, $field, $user );
				}
				return $html;
			case 'json':
			case 'php':
			case 'xml':
			case 'csvheader':
			case 'fieldslist':
			case 'csv':
			default:
				$thumbnail			=	$field->get( '_imageThumbnail', ( $reason != 'profile' ) );
				$imgUrl				=	$this->_avatarLivePath( $field, $user, $thumbnail );
				$oReturn			=	$this->_formatFieldOutput( $field->name, $imgUrl, $output );
				break;
		}

		return $oReturn;
	}

	/**
	 * Parses $_FILES for the image file or its hidden data input
	 *
	 * @param FieldTable $field
	 * @param array      $postdata
	 * @return array|null
	 */
	public function getImageFile( $field, $postdata )
	{
		global $_CB_framework, $_FILES;

		$col							=	$field->name;
		$col_file						=	$col . '__file';
		$col_file_data					=	$col . '__file_image_data';

		$file							=	( isset( $_FILES[$col_file] ) ? $_FILES[$col_file] : null );

		if ( ( ! $file ) && $field->params->get( 'image_client_resize', 1, GetterInterface::INT ) ) {
			$dataFile					=	stripslashes( cbGetParam( $postdata, $col_file_data ) );

			if ( $dataFile && preg_match( '%^data:(image/[A-Za-z]+);base64,(.+)%', $dataFile, $matches ) ) {
				$mimeTypes				=	array( 'image/png' => 'png', 'image/jpeg' => 'jpg', 'image/gif' => 'gif' );

				if ( isset( $mimeTypes[$matches[1]] ) ) {
					$name				=	md5( uniqid( rand(), true ) );
					$tmpPath			=	$_CB_framework->getCfg( 'tmp_path' );
					$tmpName			=	null;
					$size				=	0;

					if ( ! is_dir( $tmpPath ) ) {
						$error			=	UPLOAD_ERR_NO_TMP_DIR;
					} else {
						$tmpFile		=	$tmpPath . '/' . $name . '.tmp';
						$error			=	UPLOAD_ERR_OK;

						if ( file_put_contents( $tmpFile, base64_decode( $matches[2] ) ) === false ) {
							$error		=	UPLOAD_ERR_NO_FILE;
						} else {
							$tmpName	=	$tmpFile;
							$size		=	@filesize( $tmpName );
						}
					}

					$file				=	array(	'name'		=>	md5( $matches[2] ) . '.' . $mimeTypes[$matches[1]],
													'type'		=>	$matches[1],
													'tmp_name'	=>	$tmpName,
													'error'		=>	$error,
													'size'		=>	$size,
													'is_data'	=>	true
												);
				}
			}
		}

		return $file;
	}

	/**
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 */
	public function prepareFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		global $_CB_framework;

		$this->_prepareFieldMetaSave( $field, $user, $postdata, $reason );

		$col										=	$field->name;
		$colapproved								=	$col . 'approved';
		$colposition								=	$col . 'position';
		$col_choice									=	$col . '__choice';
		$col_gallery								=	$col . '__gallery';
		$col_position								=	$col . '__position';

		$choice										=	stripslashes( cbGetParam( $postdata, $col_choice ) );

		switch ( $choice ) {
			case 'upload':
				$value								=	$this->getImageFile( $field, $postdata );

				// Image is uploaded in the commit, but lets validate it here as well:
				$this->validate( $field, $user, $choice, $value, $postdata, $reason );
				break;
			case 'gallery':
				$newAvatar							=	stripslashes( cbGetParam( $postdata, $col_gallery ) );

				if ( $this->validate( $field, $user, $choice, $newAvatar, $postdata, $reason ) ) {
					$value							=	'gallery/' . $newAvatar;

					if ( isset( $user->$col ) ) {
						$this->_logFieldUpdate( $field, $user, $reason, $user->$col, $value );

						deleteAvatar( $user->$col ); // delete old avatar
					}

					$user->$col							=	$value;
					$user->$colapproved					=	1;

					if ( $col == 'canvas' ) {
						$user->$colposition				=	50;
					}
				}
				break;
			case 'delete':
				if ( $user->id && ( $user->$col != null ) && ( $user->$col != '' ) ) {
					global $_CB_database;

					if ( isset( $user->$col ) ) {
						$this->_logFieldUpdate( $field, $user, $reason, $user->$col, '' );

						deleteAvatar( $user->$col ); // delete old avatar
					}

					$user->$col						=	null; // this will not update, so we do query below:
					$user->$colapproved				=	1;

					if ( $col == 'canvas' ) {
						$user->$colposition			=	50;
					}

					// This is needed because user store does not save null:
					if ( $field->table ) {
						$query						=	'UPDATE ' . $_CB_database->NameQuote( $field->table )
													.	"\n SET " . $_CB_database->NameQuote( $col )			  . ' = NULL'
													.	', '	  . $_CB_database->NameQuote( $col . 'approved' ) . ' = 1'
													.	', '	  . $_CB_database->NameQuote( 'lastupdatedate' )  . ' = ' . $_CB_database->Quote( $_CB_framework->dateDbOfNow() )
													.	"\n WHERE " . $_CB_database->NameQuote( 'id' )			  . ' = ' . (int) $user->id;
						$_CB_database->setQuery( $query );
						$_CB_database->query();
					}
				}
				break;
			case 'approve':
				if ( isset( $user->$col ) && ( $_CB_framework->getUi() == 2 ) && $user->id && ( $user->$col != null ) && ( $user->$colapproved == 0 ) ) {
					$this->_logFieldUpdate( $field, $user, $reason, '', $user->$col );	// here we are missing the old value, so can't give it...

					$user->$colapproved				=	1;
					$user->lastupdatedate			=	$_CB_framework->dateDbOfNow();

					$cbNotification					=	new cbNotification();
					$cbNotification->sendFromSystem( $user, CBTxt::T( 'UE_IMAGEAPPROVED_SUB', 'Image Approved' ), CBTxt::T( 'UE_IMAGEAPPROVED_MSG', 'Your image has been approved by a moderator.' ) );
				}
				break;
			case 'position':
				if ( $user->id && ( $col == 'canvas' ) && ( $user->$col != null ) && ( $user->$col != '' ) && $user->$colapproved ) {
					$position						=	stripslashes( cbGetParam( $postdata, $col_position ) );

					if ( $position != '' ) {
						$this->_logFieldUpdate( $field, $user, $reason, '', $user->$col );	// here we are missing the old value, so can't give it...

						if ( $position < 0 ) {
							$position				=	0;
						} elseif ( $position > 100 ) {
							$position				=	100;
						}

						$user->$colposition			=	(int) $position;
					}
				}
				break;
			default:
				$value								=	$user->get( $col );

				$this->validate( $field, $user, $choice, $value, $postdata, $reason );
				break;
		}
	}

	/**
	 * Mutator:
	 * Prepares field data commit
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save user edit, 'register' for save registration
	 */
	public function commitFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		global $_CB_framework, $ueConfig, $_PLUGINS;

		$col										=	$field->name;
		$colapproved								=	$col . 'approved';
		$colposition								=	$col . 'position';
		$col_choice									=	$col . '__choice';

		$choice										=	stripslashes( cbGetParam( $postdata, $col_choice ) );

		switch ( $choice ) {
			case 'upload':
				$value								=	$this->getImageFile( $field, $postdata );

				if ( $this->validate( $field, $user, $choice, $value, $postdata, $reason ) ) {
					$_PLUGINS->loadPluginGroup( 'user' );

					$isModerator					=	Application::MyUser()->isModeratorFor( Application::User( (int) $user->id ) );

					$_PLUGINS->trigger( 'onBeforeUserAvatarUpdate', array( &$user, &$user, $isModerator, &$value['tmp_name'] ) );
					if ( $_PLUGINS->is_errors() ) {
						$this->_setErrorMSG( $_PLUGINS->getErrorMSG() );
					}

					$conversionType					=	(int) ( isset( $ueConfig['conversiontype'] ) ? $ueConfig['conversiontype'] : 0 );
					$imageSoftware					=	( $conversionType == 5 ? 'gmagick' : ( $conversionType == 1 ? 'imagick' : ( $conversionType == 4 ? 'gd' : 'auto' ) ) );
					$imagePath						=	$_CB_framework->getCfg( 'absolute_path' ) . '/images/comprofiler/';
					$fileName						=	( $col == 'avatar' ? '' : $col . '_' ) . uniqid( $user->id . '_' );

					try {
						$image						=	new \CBLib\Image\Image( $imageSoftware, $this->_getImageFieldParam( $field, 'avatarResizeAlways', 1 ), $this->_getImageFieldParam( $field, 'avatarMaintainRatio', 1 ) );

						$image->setName( $fileName );
						$image->setSource( $value );
						$image->setDestination( $imagePath );

						$image->processImage( $this->_getImageFieldParam( $field, 'avatarWidth', 200 ), $this->_getImageFieldParam( $field, 'avatarHeight', 500 ) );

						$newFileName				=	$image->getCleanFilename();

						$image->setName( 'tn' . $fileName );

						$image->processImage( $this->_getImageFieldParam( $field, 'thumbWidth', 60 ), $this->_getImageFieldParam( $field, 'thumbHeight', 86 ) );

						if ( isset( $value['is_data'] ) && file_exists( $value['tmp_name'] ) ) {
							@unlink( $value['tmp_name'] );
						}
					} catch ( Exception $e ) {
						$this->_setValidationError( $field, $user, $reason, $e->getMessage() );

						if ( isset( $value['is_data'] ) && file_exists( $value['tmp_name'] ) ) {
							@unlink( $value['tmp_name'] );
						}

						return;
					}

					$uploadApproval					=	$this->_getImageFieldParam( $field, 'avatarUploadApproval', 1 );

					if ( isset( $user->$col ) && ( ! ( ( $uploadApproval == 1 ) && ! $isModerator ) ) ) {
						// if auto-approved:				//TBD: else need to log update on image approval !
						$this->_logFieldUpdate( $field, $user, $reason, $user->$col, $newFileName );
					}

					if ( isset( $user->$col ) && ( $user->$col != '' ) ) {
						deleteAvatar( $user->$col );
					}

					if ( ( $uploadApproval == 1 ) && ! $isModerator ) {
						$cbNotification				=	new cbNotification();
						$cbNotification->sendToModerators( cbReplaceVars( CBTxt::T( 'UE_IMAGE_ADMIN_SUB', 'Image Pending Approval' ), $user ), cbReplaceVars( CBTxt::T( 'UE_IMAGE_ADMIN_MSG', 'A user has submitted an image for approval. Please log in and take the appropriate action.'), $user ) );

						$user->$col					=	$newFileName;
						$user->$colapproved			=	0;
					} else {
						$user->$col					=	$newFileName;
						$user->$colapproved			=	1;
					}

					if ( $col == 'canvas' ) {
						$user->$colposition			=	50;
					}

					$_PLUGINS->trigger( 'onAfterUserAvatarUpdate', array( &$user, &$user, $isModerator, $newFileName ) );
				}
				break;
		}
	}

	/**
	 * Mutator:
	 * Prepares field data rollback
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save user edit, 'register' for save registration
	 */
	public function rollbackFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		$col				=	$field->name;
		$col_choice			=	$col . '__choice';

		$choice				=	stripslashes( cbGetParam( $postdata, $col_choice ) );

		switch ( $choice ) {
			case 'upload':
				$value		=	$this->getImageFile( $field, $postdata );

				if ( $this->validate( $field, $user, $choice, $value, $postdata, $reason ) ) {
					deleteAvatar( $user->$col );
				}
				break;
		}
	}

	/**	 * Validator:
	 * Validates $value for $field->required and other rules
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user        RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  string      $columnName  Column to validate
	 * @param  string      $value       (RETURNED:) Value to validate, Returned Modified if needed !
	 * @param  array       $postdata    Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason      'edit' for save user edit, 'register' for save registration
	 * @return boolean                  True if validate, $this->_setErrorMSG if False
	 */
	public function validate( &$field, &$user, $columnName, &$value, &$postdata, $reason ) {
		global $_CB_framework;

		$isRequired							=	$this->_isRequired( $field, $user, $reason );

		switch ( $columnName ) {
			case 'upload':
				if ( ! $field->params->get( 'image_allow_uploads', 1 ) ) {
					$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'UE_NOT_AUTHORIZED', 'You are not authorized to view this page!' ) );

					if ( isset( $value['is_data'] ) && file_exists( $value['tmp_name'] ) ) {
						@unlink( $value['tmp_name'] );
					}

					return false;
				} elseif ( ! isset( $value['tmp_name'] ) || empty( $value['tmp_name'] ) || ( $value['error'] != 0 ) || ( ( ! is_uploaded_file( $value['tmp_name'] ) ) && ( ! isset( $value['is_data'] ) ) ) ) {
					if ( $isRequired ) {
						$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'Please select a image file before uploading' ) );
					}

					if ( isset( $value['is_data'] ) && file_exists( $value['tmp_name'] ) ) {
						@unlink( $value['tmp_name'] );
					}

					return false;
				} else {
					$upload_size_limit_max	=	(int) $this->_getImageFieldParam( $field, 'avatarSize', 2000 );
					$upload_ext_limit		=	array( 'jpg', 'jpeg', 'gif', 'png' );
					$uploaded_ext			=	strtolower( preg_replace( '/[^-a-zA-Z0-9_]/u', '', pathinfo( $value['name'], PATHINFO_EXTENSION ) ) );

					if ( ( ! $uploaded_ext ) || ( ! in_array( $uploaded_ext, $upload_ext_limit ) ) ) {
						$this->_setValidationError( $field, $user, $reason, sprintf( CBTxt::T( 'Please upload only %s' ), implode( ', ', $upload_ext_limit ) ) );

						if ( isset( $value['is_data'] ) && file_exists( $value['tmp_name'] ) ) {
							@unlink( $value['tmp_name'] );
						}

						return false;
					}

					$uploaded_size			=	$value['size'];

					if ( ( $uploaded_size / 1024 ) > $upload_size_limit_max ) {
						$this->_setValidationError( $field, $user, $reason, sprintf( CBTxt::T( 'The image file size exceeds the maximum of %s' ), $this->formattedFileSize( $upload_size_limit_max * 1024 ) ) );

						if ( isset( $value['is_data'] ) && file_exists( $value['tmp_name'] ) ) {
							@unlink( $value['tmp_name'] );
						}

						return false;
					}
				}
				break;
			case 'gallery':
				if ( ! $field->params->get( 'image_allow_gallery', ( in_array( $field->name, array( 'avatar', 'canvas' ) ) ? 1 : 0 ) ) ) {
					$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'UE_NOT_AUTHORIZED', 'You are not authorized to view this page!' ) );
					return false;
				}

				$galleryPath				=	$field->params->get( 'image_gallery_path', null );

				if ( ! $galleryPath ) {
					if ( $field->get( 'name' ) == 'canvas' ) {
						$galleryPath		=	'/images/comprofiler/gallery/canvas';
					} else {
						$galleryPath		=	'/images/comprofiler/gallery';
					}
				}

				$galleryImages				=	$this->displayImagesGallery( $_CB_framework->getCfg( 'absolute_path' ) . $galleryPath, 'all' );

				if ( ! in_array( $value, $galleryImages ) ) {
					$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'UE_UPLOAD_ERROR_CHOOSE', 'You didn\'t choose an image from the gallery.' ) . $value );
					return false;
				}
				break;
			default:
				$valCol			=	$field->name;
				if ( $isRequired && ( ( ! $user ) || ( ! isset( $user->$valCol ) ) || ( ! $user->$valCol ) ) ) {
					if ( ! $value ) {
						$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'UE_FIELDREQUIRED', 'This Field is required' ) );
						return false;
					}
				}
				break;
		}

		return true;
	}

	/**	 * Finder:
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $searchVals  RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata    Typically $_POST (but not necessarily), filtering required.
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @param  string      $reason      'edit' for save profile edit, 'register' for registration, 'search' for searches
	 * @return cbSqlQueryPart[]
	 */
	function bindSearchCriteria( &$field, &$searchVals, &$postdata, $list_compare_types, $reason ) {
		$query								=	array();
		$searchMode							=	$this->_bindSearchMode( $field, $searchVals, $postdata, 'none', $list_compare_types );
		$col								=	$field->name;
		$colapproved						=	$col . 'approved';
		$value								=	cbGetParam( $postdata, $col );

		if ( $value === '0' ) {
			$value							=	0;
		} elseif ( $value == '1' ) {
			$value							=	1;
		} else {
			$value							=	null;
		}

		if ( $value !== null ) {
			$searchVals->$col				=	$value;

			// When is not advanced search is used we need to invert our search:
			if ( $searchMode == 'isnot' ) {
				if ( $value === 0 ) {
					$value					=	1;
				} elseif ( $value == 1 ) {
					$value					=	0;
				}
			}

			$sql							=	new cbSqlQueryPart();
			$sql->tag						=	'column';
			$sql->name						=	$colapproved;
			$sql->table						=	$field->table;
			$sql->type						=	'sql:operator';
			$sql->operator					=	$value ? 'AND' : 'OR';
			$sql->searchmode				=	$searchMode;

			$sqlpict						=	new cbSqlQueryPart();
			$sqlpict->tag					=	'column';
			$sqlpict->name					=	$col;
			$sqlpict->table					=	$field->table;
			$sqlpict->type					=	'sql:field';
			$sqlpict->operator				=	$value ? 'IS NOT' : 'IS';
			$sqlpict->value					=	'NULL';
			$sqlpict->valuetype				=	'const:null';
			$sqlpict->searchmode			=	$searchMode;

			$sqlapproved					=	new cbSqlQueryPart();
			$sqlapproved->tag				=	'column';
			$sqlapproved->name				=	$colapproved;
			$sqlapproved->table				=	$field->table;
			$sqlapproved->type				=	'sql:field';
			$sqlapproved->operator			=	$value ? '>' : '=';
			$sqlapproved->value				=	0;
			$sqlapproved->valuetype			=	'const:int';
			$sqlapproved->searchmode		=	$searchMode;

			$sql->addChildren( array( $sqlpict, $sqlapproved ) );

			$query[]						=	$sql;
		}

		return $query;
	}

	/**
	 * returns full or thumbnail image tag
	 *
	 * @param  FieldTable   $field
	 * @param  UserTable    $user
	 * @param  string       $reason
	 * @param  boolean      $thumbnail
	 * @param  int          $showAvatar
	 * @return null|string
	 */
	function _avatarHtml( &$field, &$user, $reason, $thumbnail = true, $showAvatar = 2 ) {
		global $_CB_framework;

		switch ( $field->params->getInt( 'titleText', 0 ) ) {
			case 2:
				$title			=	cbReplaceVars( $field->params->getHTML( 'titleTextCustom' ), $user, true, true, array( 'reason' => $reason ) );
				break;
			case 1:
				$title			=	null;
				break;
			default:
				if ( $field->getString( 'name' ) === 'avatar' ) {
					if ( $user && $user->getInt( 'id' ) ) {
						$title	=	$user->getFormattedName();
					} else {
						$title	=	null;
					}
				} elseif ( $field->getString( 'name' ) === 'canvas' ) {
					$title		=	null;
				} else {
					$title		=	cbReplaceVars( $field->getHTML( 'title' ), $user, true, true, array( 'reason' => $reason ) );		// does htmlspecialchars()
				}
				break;
		}

		$approved				=	( $user->getBool( $field->getString( 'name' ) . 'approved', true ) || ( $showAvatar === 10 ) );
		$isInitials				=	( $field->params->getString( ( ! $approved ? 'pendingDefaultAvatar' : 'defaultAvatar' ), ( in_array( $field->getString( 'name' ), array( 'avatar', 'canvas' ) ) ? 'initial' : '' ) ) === 'initial' );
		$imgUrl					=	$this->_avatarLivePath( $field, $user, $thumbnail, $showAvatar );

		if ( ( ! $imgUrl ) && ( ! $isInitials ) ) {
			return null;
		}

		if ( $field->getString( 'name' ) === 'canvas' ) {
			if ( $imgUrl ) {
				if ( $approved && ( $user->getString( $field->getString( 'name' ) ) != '' ) ) {
					$position		=	$user->getInt( $field->getString( 'name' ) . 'position', 50 );

					if ( $position < 0 ) {
						$position	=	0;
					} elseif ( $position > 100 ) {
						$position	=	100;
					}
				} else {
					$position		=	50;
				}

				return '<div style="background-image: url(' . $imgUrl . '); background-position-y: ' . $position . '%;"' . ( $title ? ' title="' . htmlspecialchars( $title ) . '"' : null ) . ' class="cbImgCanvas' . ( ! $approved ? ' cbImgCanvasPending' : null ) . ( $thumbnail ? ' cbThumbCanvas' : ' cbFullCanvas' ) . '"></div>';
			}

			return '<div style="background: linear-gradient( 0deg, ' . htmlspecialchars( Color::stringToHex( $user->getFormattedName() ) ) . ' 0%, ' . htmlspecialchars( Color::stringToHex( $user->getFormattedName(), 0.9 ) ) . ' 100% );"' . ( $title ? ' title="' . htmlspecialchars( $title ) . '"' : null ) . ' class="cbImgCanvas cbImgCanvasInitial' . ( ! $approved ? ' cbImgCanvasPending' : null ) . ( $thumbnail ? ' cbThumbCanvas' : ' cbFullCanvas' ) . '"></div>';
		}

		switch ( $field->params->getInt( 'altText', 0 ) ) {
			case 2:
				$alt			=	cbReplaceVars( $field->params->getHTML( 'altTextCustom' ), $user, true, true, array( 'reason' => $reason ) );
				break;
			case 1:
				$alt			=	null;
				break;
			default:
				if ( $field->getString( 'name' ) === 'avatar' ) {
					if ( $user && $user->getInt( 'id' ) ) {
						$alt	=	$user->getFormattedName();
					} else {
						$alt	=	null;
					}
				} else {
					$alt		=	cbReplaceVars( $field->getHTML( 'title' ), $user, true, true, array( 'reason' => $reason ) );		// does htmlspecialchars()
				}
				break;
		}

		switch ( $field->params->getString( 'imageStyle', ( $field->getString( 'name' ) === 'avatar' ? 'circlebordered' : '' ) ) ) {
			case 'rounded':
				$style			=	' rounded';
				break;
			case 'roundedbordered':
				$style			=	' img-thumbnail';
				break;
			case 'circle':
				$style			=	' rounded-circle';
				break;
			case 'circlebordered':
				$style			=	' img-thumbnail rounded-circle';
				break;
			default:
				$style			=	null;
				break;
		}

		if ( $field->getString( 'name' ) === 'avatar' ) {
			$style				.=	' cbImgAvatar';
		}

		$profileLink			=	$user->getBool( '_allowProfileLink', $field->getBool( '_allowProfileLink' ) ); // For B/C

		if ( $profileLink === null ) {
			$profileLink		=	$field->params->getBool( 'fieldProfileLink', true );
		}

		if ( $profileLink && ( ! in_array( $reason, array( 'profile', 'edit' ) ) ) && $user && $user->getInt( 'id' ) ) {
			$openTag			=	'<a href="' . $_CB_framework->userProfileUrl( $user->getInt( 'id' ), true, ( $field->getString( 'name' ) === 'avatar' ? null : $field->getInt( 'tabid' ) ) ) . '">';
			$closeTag			=	'</a>';
		} else {
			$openTag			=	null;
			$closeTag			=	null;
		}

		$return 				=	$openTag;

		if ( $imgUrl ) {
			$return				.=	'<img src="' . $imgUrl . '"' . ( $alt ? ' alt="' . htmlspecialchars( $alt ) . '"' : null ) . ( $title ? ' title="' . htmlspecialchars( $title ) . '"' : null ) . ' class="cbImgPict' . ( ! $approved ? ' cbImgPictPending' : null ) . ( $thumbnail ? ' cbThumbPict' : ' cbFullPict' ) . $style . '" />';
		} else {
			if ( in_array( Application::Config()->getInt( 'name_format', 3 ), array( 1, 2, 4, 7, 8, 9, 10, 11 ), true ) ) {
				$initials		=	cbIsoUtf_strtoupper( $user->getFormattedName( 9 ) );
			} else {
				$initials		=	cbIsoUtf_strtoupper( cbutf8_substr( $user->getFormattedName(), 0, 1 ) );
			}

			$return				.=	'<svg viewBox="0 0 100 100" class="cbImgPict cbImgPictInitial' . ( ! $approved ? ' cbImgPictPending' : null ) . ( $thumbnail ? ' cbThumbPict' : ' cbFullPict' ) . $style . '">'
								.		'<rect fill="' . htmlspecialchars( Color::stringToHex( $user->getFormattedName() ) ) . '" width="100" height="100" cx="50" cy="50" r="50" />'
								.		'<text x="50%" y="50%" style="color: #ffffff; line-height: 1;" alignment-baseline="middle" text-anchor="middle" font-size="40" font-weight="600" dy="0.1em" dominant-baseline="middle" fill="#ffffff">'
								.			$initials
								.		'</text>'
								.	'</svg>';
		}

		$return					.=	$closeTag;

		return $return;
	}

	/**
	 * returns full or thumbnail path of image
	 *
	 * @param  FieldTable   $field
	 * @param  UserTable    $user
	 * @param  boolean      $thumbnail
	 * @param  int          $showAvatar
	 * @param  boolean      $absolute
	 * @return null|string
	 */
	function _avatarLivePath( &$field, &$user, $thumbnail = true, $showAvatar = 2, $absolute = false ) {
		global $_CB_framework;

		$liveSite						=	$_CB_framework->getCfg( 'live_site' );
		$absolutePath					=	$_CB_framework->getCfg( 'absolute_path' );
		$fieldName						=	$field->get( 'name' );
		$approvedFieldName				=	$fieldName . 'approved';

		if ( $user && $user->id ) {
			$value						=	$user->get( $fieldName );
			$approvedValue				=	$user->get( $approvedFieldName );
		} else {
			$value						=	null;
			$approvedValue				=	1;
		}

		$tn								=	( $thumbnail ? 'tn' : null );
		$return							=	null;

		if ( ( $value != '' ) && ( ( $approvedValue > 0 ) || ( $showAvatar == 10 ) ) ) {
			if ( strpos( $value, 'gallery/' ) === false ) {
				$return					=	'/images/comprofiler/' . $tn . $value;
			} else {
				$galleryPath			=	$field->params->get( 'image_gallery_path', null );

				if ( ! $galleryPath ) {
					if ( $fieldName == 'canvas' ) {
						$galleryPath	=	'/images/comprofiler/gallery/canvas';
					} else {
						$galleryPath	=	'/images/comprofiler/gallery';
					}
				}

				$return					=	$galleryPath . '/' . preg_replace( '!^gallery/(tn)?!', ( $tn ? 'tn' : '' ), $value );

				if ( ! is_file( $absolutePath . $return ) ) {
					$return				=	$galleryPath . '/' . preg_replace( '!^gallery/!', '', $value );
				}
			}

			if ( ! is_file( $absolutePath . $return ) ) {
				$return					=	null;
			}
		}

		if ( ( $return === null ) && ( $showAvatar == 2 ) ) {
			$imagesBase					=	'avatar';

			if ( $field->name == 'canvas' ) {
				$imagesBase				=	'canvas';
			}

			$imageDefault				=	( in_array( $field->getString( 'name' ), array( 'avatar', 'canvas' ) ) ? 'initial' : '' );

			if ( $approvedValue == 0 ) {
				$icon					=	$field->params->get( 'pendingDefaultAvatar', $imageDefault );

				if ( ( $icon == 'none' ) || ( $icon == 'initial' ) ) {
					return null;
				} elseif ( $icon ) {
					if ( ( $icon != 'pending_n.png' ) && ( ! is_file( selectTemplate( 'absolute_path' ) . '/images/' . $imagesBase . '/' . $tn . $icon ) ) ) {
						$icon			=	null;
					}
				}

				if ( ! $icon ) {
					$icon				=	'pending_n.png';
				}
			} else {
				$icon					=	$field->params->get( 'defaultAvatar', $imageDefault );

				if ( ( $icon == 'none' ) || ( $icon == 'initial' ) ) {
					return null;
				} elseif ( $icon ) {
					if ( ( $icon != 'nophoto_n.png' ) && ( ! is_file( selectTemplate( 'absolute_path' ) . '/images/' . $imagesBase . '/' . $tn . $icon ) ) ) {
						$icon			=	null;
					}
				}

				if ( ! $icon ) {
					$icon				=	'nophoto_n.png';
				}
			}

			// Image doesn't exist in the template; check default template:
			if ( ! is_file( selectTemplate( 'absolute_path' ) . '/images/' . $imagesBase . '/' . $tn . $icon ) ) {
				// Image doesn't exist in the default template so return null to suppress display:
				if ( ! is_file( selectTemplate( 'absolute_path', 'default' ) . '/images/' . $imagesBase . '/' . $tn . $icon ) ) {
					return null;
				}

				return ( $absolute ? selectTemplate( 'absolute_path', 'default' ) . '/' : selectTemplate( 'live_site', 'default' ) ) . 'images/' . $imagesBase . '/' . $tn . $icon;
			}

			return ( $absolute ? selectTemplate( 'absolute_path' ) . '/' : selectTemplate() ) . 'images/' . $imagesBase . '/' . $tn . $icon;
		}

		if ( $return ) {
			$return						=	( $absolute ? $absolutePath : $liveSite ) . $return;
		}

		return $return;
	}

	/**
	 * returns html edit display of image field
	 *
	 * @param  FieldTable   $field
	 * @param  UserTable    $user
	 * @param  string       $reason
	 * @param  boolean      $displayFieldIcons
	 * @return null|string
	 */
	function _htmlEditForm( &$field, &$user, $reason, $displayFieldIcons = true ) {
		global $_CB_framework;

		$fieldName								=	$field->get( 'name' );

		if ( ! ( $field->params->get( 'image_allow_uploads', 1 ) || $field->params->get( 'image_allow_gallery', ( in_array( $fieldName, array( 'avatar', 'canvas' ) ) ? 1 : 0 ) ) ) ) {
			return null;
		}

		$approvedFieldName						=	$fieldName . 'approved';
		$value									=	$user->get( $fieldName );
		$approvedValue							=	$user->get( $approvedFieldName );
		$required								=	$this->_isRequired( $field, $user, $reason );

		$uploadWidthLimit						=	$this->_getImageFieldParam( $field, 'avatarWidth', 500 );
		$uploadHeightLimit						=	$this->_getImageFieldParam( $field, 'avatarHeight', 200 );
		$uploadSizeLimitMax						=	$this->_getImageFieldParam( $field, 'avatarSize', 2000 );
		$uploadExtLimit							=	array( 'gif', 'png', 'jpg', 'jpeg' );
		$restrictions							=	array();

		if ( $uploadExtLimit ) {
			$restrictions[]						=	CBTxt::Th( 'IMAGE_FILE_UPLOAD_LIMITS_EXT', 'Your image file must be of [ext] type.', array( '[ext]' => implode( ', ', $uploadExtLimit ) ) );
		}

		if ( $uploadSizeLimitMax ) {
			$restrictions[]						=	CBTxt::Th( 'IMAGE_FILE_UPLOAD_LIMITS_MAX', 'Your image file should not exceed [size].', array( '[size]' => $this->formattedFileSize( $uploadSizeLimitMax * 1024 ) ) );
		}

		if ( $uploadWidthLimit ) {
			$restrictions[]						=	CBTxt::Th( 'IMAGE_FILE_UPLOAD_LIMITS_WIDTH', 'Images exceeding the maximum width of [size] will be resized.', array( '[size]' => $uploadWidthLimit ) );
		}

		if ( $uploadHeightLimit ) {
			$restrictions[]						=	CBTxt::Th( 'IMAGE_FILE_UPLOAD_LIMITS_HEIGHT', 'Images exceeding the maximum height of [size] will be resized.', array( '[size]' => $uploadHeightLimit ) );
		}

		$existingFile							=	( $user->get( 'id' ) ? ( ( $value != null ) ? true : false ) : false );
		$choices								=	array();

		if ( ( $reason == 'register' ) || ( ( $reason == 'edit' ) && ( $user->id == 0 ) ) ) {
			if ( $required == 0 ) {
				$choices[]						=	moscomprofilerHTML::makeOption( '', CBTxt::T( 'No image' ) );
			}
		} else {
			if ( $existingFile || ( $required == 0 ) ) {
				$choices[]						=	moscomprofilerHTML::makeOption( '', CBTxt::T( 'No change of image' ) );
			}
		}

		$selected								=	null;

		if ( ( $required == 1 ) && ( ! $existingFile ) ) {
			$selected							=	( $field->params->get( 'image_allow_uploads', 1 ) ? 'upload' : 'gallery' );
		}

		if ( $field->params->get( 'image_allow_uploads', 1 ) ) {
			$choices[]							=	moscomprofilerHTML::makeOption( 'upload', ( $existingFile ? CBTxt::T( 'Upload new image' ) : CBTxt::T( 'Upload image' ) ) );
		}

		if ( $field->params->get( 'image_allow_gallery', ( in_array( $fieldName, array( 'avatar', 'canvas' ) ) ? 1 : 0 ) ) ) {
			$choices[]							=	moscomprofilerHTML::makeOption( 'gallery', ( $existingFile ? CBTxt::T( 'Select new image from gallery' ) : CBTxt::T( 'Select image from gallery' ) ) );
		}

		if ( ( $_CB_framework->getUi() == 2 ) && $existingFile && ( $approvedValue == 0 ) ) {
			$choices[]							=	moscomprofilerHTML::makeOption( 'approve', CBTxt::T( 'Approve image' ) );
		}

		$canReposition							=	false;

		if ( ( $fieldName == 'canvas' ) && ( $reason == 'edit' ) && $existingFile && $approvedValue ) {
			$canvasSize							=	@getimagesize( $this->_avatarLivePath( $field, $user, false, 2, true ) );

			if ( ( $canvasSize !== false ) && ( $canvasSize[1] > 200 ) ) {
				$canReposition					=	true;

				$choices[]						=	moscomprofilerHTML::makeOption( 'position', CBTxt::T( 'Reposition image' ) );
			}
		}

		if ( $existingFile && ( $required == 0 ) ) {
			$choices[]							=	moscomprofilerHTML::makeOption( 'delete', CBTxt::T( 'Remove image' ) );
		}

		$return									=	null;

		if ( ( $reason != 'register' ) && ( $user->id != 0 ) && $existingFile ) {
			$return								.=	'<div class="row no-gutters mb-3 cbImageFieldImage">' . $this->_avatarHtml( $field, $user, $reason ) . '</div>';
		}

		if ( ( $reason == 'edit' ) && $existingFile && ( $approvedValue == 0 ) && Application::MyUser()->isModeratorFor( Application::User( (int) $user->id ) ) ) {
			$return								.=	'<div class="row no-gutters mb-3 cbImageFieldImage">' . $this->_avatarHtml( $field, $user, $reason, false, 10 ) . '</div>';
		}

		$hasChoices								=	( count( $choices ) > 1 );

		if ( $hasChoices ) {
			static $functOut					=	false;

			$additional							=	' class="form-control cbImageFieldChoice"';

			if ( ( $_CB_framework->getUi() == 1 ) && ( $reason == 'edit' ) && $field->get( 'readonly' ) ) {
				$additional						.=	' disabled="disabled"';
			}

			$translatedTitle					=	$this->getFieldTitle( $field, $user, 'html', $reason );
			$htmlDescription					=	$this->getFieldDescription( $field, $user, 'htmledit', $reason );
			$trimmedDescription					=	trim( strip_tags( $htmlDescription ) );
			$inputDescription					=	$field->params->get( 'fieldLayoutInputDesc', 1, GetterInterface::INT );

			$tooltip							=	( $trimmedDescription && $inputDescription ? cbTooltip( $_CB_framework->getUi(), $htmlDescription, $translatedTitle, null, null, null, null, $additional ) : $additional );

			$return								.=	'<div class="form-group mb-0 cb_form_line">'
												.		moscomprofilerHTML::selectList( $choices, $fieldName . '__choice', $tooltip, 'value', 'text', $selected, $required, true, null, false )
												.		$this->_fieldIconsHtml( $field, $user, 'htmledit', $reason, 'select', '', null, '', array(), $displayFieldIcons, $required )
												.	'</div>';

			if ( ! $functOut ) {
				$js								=	"$.fn.cbslideImageFile = function() {"
												.		"var element = $( this );"
												.		"element.on( 'click.cbimagefield change.cbimagefield', function() {"
												.			"if ( ( $( this ).val() == '' ) || ( $( this ).val() == 'delete' ) ) {"
												.				"element.parent().siblings( '.cbImageFieldUpload,.cbImageFieldGallery,.cbImageFieldPosition' ).addClass( 'hidden' ).find( 'input' ).prop( 'disabled', true );"
												.			"} else if ( $( this ).val() == 'upload' ) {"
												.				"element.parent().siblings( '.cbImageFieldUpload' ).removeClass( 'hidden' ).find( 'input' ).prop( 'disabled', false );"
												.				"element.parent().siblings( '.cbImageFieldGallery,.cbImageFieldPosition' ).addClass( 'hidden' ).find( 'input' ).prop( 'disabled', true );"
												.			"} else if ( $( this ).val() == 'gallery' ) {"
												.				"element.parent().siblings( '.cbImageFieldGallery' ).removeClass( 'hidden' ).find( 'input' ).prop( 'disabled', false );"
												.				"element.parent().siblings( '.cbImageFieldUpload,.cbImageFieldPosition' ).addClass( 'hidden' ).find( 'input' ).prop( 'disabled', true );"
												.			"} else if ( $( this ).val() == 'position' ) {"
												.				"element.parent().siblings( '.cbImageFieldPosition' ).removeClass( 'hidden' ).find( 'input' ).prop( 'disabled', false );"
												.				"element.parent().siblings( '.cbImageFieldUpload,.cbImageFieldGallery' ).addClass( 'hidden' ).find( 'input' ).prop( 'disabled', true );"
												.				"element.parent().siblings( '.cbImageFieldPosition' ).find( '.cbCanvasRepositionSelect' ).draggable({"
												.					"containment: 'parent',"
												.					"scroll: false,"
												.					"axes: 'y',"
												.					"create: function() {"
												.						"$( this ).css({"
												.							"height: ( ( 200 / $( this ).parent().height() ) * 100 ) + '%',"
												.							"width: '100%'"
												.						"});"
												.						"var top = element.parent().siblings( '.cbImageFieldPosition' ).find( 'input' ).val();"
												.						"if ( top != '' ) {"
												.							"if ( top < 0 ) {"
												.								"top = 0;"
												.							"} else if ( top > 100 ) {"
												.								"top = 100;"
												.							"}"
												.							"top = ( ( $( this ).parent().height() / 2 ) * ( top / 100 ) );"
												.						"} else {"
												.							"top = ( ( $( this ).parent().height() / 2 ) - ( $( this ).height() / 2 ) );"
												.						"}"
												.						"$( this ).css( 'top', top + 'px' );"
												.					"},"
												.					"stop: function( e, ui ) {"
												.						"element.parent().siblings( '.cbImageFieldPosition' ).find( 'input' ).val( ( 100 / ( ( $( this ).parent().height() - $( this ).height() ) / ui.position.top ) ).toFixed( 0 ) );"
												.					"}"
												.				"});"
												.			"}"
												.		"}).on( 'cloned.cbimagefield', function() {"
												.			"$( this ).parent().siblings( '.cbImageFieldImage' ).remove();"
												.			"if ( $( this ).parent().siblings( '.cbImageFieldUpload,.cbImageFieldGallery' ).find( 'input.required' ).length ) {"
												.				"$( this ).find( 'option[value=\"\"]' ).remove();"
												.			"}"
												.			"$( this ).find( 'option[value=\"delete\"]' ).remove();"
												.			"$( this ).find( 'option[value=\"position\"]' ).remove();"
												.			"$( this ).off( '.cbimagefield' );"
												.			"$( this ).cbslideImageFile();"
												.		"}).change();"
												.		"return this;"
												.	"};";

				$_CB_framework->outputCbJQuery( $js, 'ui-all' );

				$functOut					=	true;
			}

			$_CB_framework->outputCbJQuery( "$( '#" . addslashes( $fieldName ) . "__choice' ).cbslideImageFile();" );
		} else {
			$return								.=	'<input type="hidden" name="' . htmlspecialchars( $fieldName ) . '__choice" value="' . htmlspecialchars( $choices[0]->value ) . '" />';
		}

		if ( $field->params->get( 'image_allow_uploads', 1 ) ) {
			$validationAttributes				=	array();
			$validationAttributes[]				=	cbValidator::getRuleHtmlAttributes( 'extension', implode( ',', $uploadExtLimit ) );

			if ( $uploadSizeLimitMax ) {
				$validationAttributes[]			=	cbValidator::getRuleHtmlAttributes( 'filesize', array( 0, $uploadSizeLimitMax, 'KB' ) );
			}

			if ( $field->params->get( 'image_client_resize', 1, GetterInterface::INT ) && ( $uploadWidthLimit || $uploadHeightLimit ) ) {
				$validationAttributes[]			=	cbValidator::getRuleHtmlAttributes( 'resize', array( $uploadWidthLimit, $uploadHeightLimit, $this->_getImageFieldParam( $field, 'avatarMaintainRatio', 1 ), $this->_getImageFieldParam( $field, 'avatarResizeAlways', 1 ) ) );
			}

			$return								.=	'<div id="cbimagefile_upload_' . htmlspecialchars( $fieldName ) . '" class="form-group mb-0 cb_form_line' . ( $hasChoices ? ' mt-3 hidden' : null ) . ' cbImageFieldUpload">'
												.		( $restrictions ? '<div class="mb-2">' . implode( ' ', $restrictions ) . '</div>' : null )
												.		'<div>'
												.			CBTxt::T( 'Select image file' ) . ' <input type="file" name="' . htmlspecialchars( $fieldName ) . '__file" value="" class="form-control' . ( $required == 1 ? ' required' : null ) . '"' . implode( ' ', $validationAttributes ) . ( $hasChoices ? ' disabled="disabled"' : null ) . ' />'
												.			( count( $choices ) <= 0 ? $this->_fieldIconsHtml( $field, $user, 'htmledit', $reason, 'select', '', null, '', array(), $displayFieldIcons, $required ) : null )
												.		'</div>'
												.		'<div class="mt-2">';

			if ( $field->params->get( 'image_terms', 0 ) ) {
				$cbUser							=	CBuser::getMyInstance();
				$termsOutput					=	$field->params->get( 'terms_output', 'url' );
				$termsType						=	$cbUser->replaceUserVars( $field->params->get( 'terms_type', 'TERMS_AND_CONDITIONS' ) );
				$termsDisplay					=	$field->params->get( 'terms_display', 'modal' );
				$termsURL						=	cbSef( $cbUser->replaceUserVars( $field->params->get( 'terms_url', null ) ), false );
				$termsText						=	$cbUser->replaceUserVars( $field->params->get( 'terms_text', null ) );
				$termsWidth						=	$field->params->get( 'terms_width', 400 );
				$termsHeight					=	$field->params->get( 'terms_height', 200 );

				if ( ! $termsType ) {
					$termsType					=	CBTxt::T( 'TERMS_AND_CONDITIONS', 'Terms and Conditions' );
				}

				if ( ! $termsWidth ) {
					$termsWidth					=	400;
				}

				if ( ! $termsHeight ) {
					$termsHeight				=	200;
				}

				if ( ( ( $termsOutput == 'url' ) && $termsURL ) || ( ( $termsOutput == 'text' ) && $termsText ) ) {
					if ( $termsDisplay == 'iframe' ) {
						if ( is_numeric( $termsHeight ) ) {
							$termsHeight		.=	'px';
						}

						if ( is_numeric( $termsWidth ) ) {
							$termsWidth			.=	'px';
						}

						if ( $termsOutput == 'url' ) {
							$return				.=	'<div class="embed-responsive mb-2 cbTermsFrameContainer" style="padding-bottom: ' . htmlspecialchars( $termsHeight ) . ';">'
												.		'<iframe class="embed-responsive-item d-block border rounded cbTermsFrameURL" style="width: ' . htmlspecialchars( $termsWidth ) . ';" src="' . htmlspecialchars( $termsURL ) . '"></iframe>'
												.	'</div>';
						} else {
							$return				.=	'<div class="bg-light border rounded p-2 mb-2 cbTermsFrameText" style="height:' . htmlspecialchars( $termsHeight ) . ';width:' . htmlspecialchars( $termsWidth ) . ';overflow:auto;">' . $termsText . '</div>';
						}

						$return					.=			CBTxt::Th( 'BY_UPLOADING_YOU_CERTIFY_THAT_YOU_HAVE_THE_RIGHT_TO_DISTRIBUTE_THIS_IMAGE_FILE_TERMS', 'By uploading, you certify that you have the right to distribute this image and that it does not violate the above [type].', array( '[type]' => $termsType ) );
					} else {
						$attributes				=	' class="cbTermsLink"';

						if ( ( $termsOutput == 'text' ) && ( $termsDisplay == 'window' ) ) {
							$termsDisplay		=	'modal';
						}

						if ( $termsDisplay == 'modal' ) {
							// Tooltip height percentage would be based off window height (including scrolling); lets change it to be based off the viewport height:
							$termsHeight		=	( substr( $termsHeight, -1 ) == '%' ? (int) substr( $termsHeight, 0, -1 ) . 'vh' : $termsHeight );

							if ( $termsOutput == 'url' ) {
								$tooltip		=	'<iframe class="d-block m-0 p-0 border-0 cbTermsModalURL" height="100%" width="100%" src="' . htmlspecialchars( $termsURL ) . '"></iframe>';
							} else {
								$tooltip		=	'<div class="cbTermsModalText" style="height:100%;width:100%;overflow:auto;">' . $termsText . '</div>';
							}

							$url				=	'javascript:void(0);';
							$attributes			.=	' ' . cbTooltip( $_CB_framework->getUi(), $tooltip, $termsType, array( $termsWidth, $termsHeight ), null, null, null, 'data-hascbtooltip="true" data-cbtooltip-modal="true"' );
						} else {
							$url				=	htmlspecialchars( $termsURL );
							$attributes			.=	' target="_blank"';
						}

						$return					.=			CBTxt::Th( 'BY_UPLOADING_YOU_CERTIFY_THAT_YOU_HAVE_THE_RIGHT_TO_DISTRIBUTE_THIS_IMAGE_FILE_URL_TERMS', 'By uploading, you certify that you have the right to distribute this image and that it does not violate the <a href="[url]"[attributes]>[type]</a>', array( '[url]' => $url, '[attributes]' => $attributes, '[type]' => $termsType ) );
					}
				} else {
					$return						.=			CBTxt::Th( 'BY_UPLOADING_YOU_CERTIFY_THAT_YOU_HAVE_THE_RIGHT_TO_DISTRIBUTE_THIS_IMAGE_FILE', 'By uploading, you certify that you have the right to distribute this image.' );
				}
			} else {
				$return							.=			CBTxt::Th( 'BY_UPLOADING_YOU_CERTIFY_THAT_YOU_HAVE_THE_RIGHT_TO_DISTRIBUTE_THIS_IMAGE_FILE', 'By uploading, you certify that you have the right to distribute this image.' );
			}

			$return								.=		'</div>'
												.	'</div>';
		}

		if ( $field->params->get( 'image_allow_gallery', ( in_array( $fieldName, array( 'avatar', 'canvas' ) ) ? 1 : 0 ) ) ) {
			$galleryPath						=	$field->params->getString( 'image_gallery_path' );

			if ( ! $galleryPath ) {
				if ( $fieldName === 'canvas' ) {
					$galleryPath				=	'/images/comprofiler/gallery/canvas';
				} else {
					$galleryPath				=	'/images/comprofiler/gallery';
				}
			}

			$galleryImages						=	$this->displayImagesGallery( $_CB_framework->getCfg( 'absolute_path' ) . $galleryPath );
			$galleryStyle						=	null;

			if ( $fieldName !== 'canvas' ) {
				switch ( $field->params->getString( 'imageStyle', ( $fieldName === 'avatar' ? 'circlebordered' : '' ) ) ) {
					case 'rounded':
						$galleryStyle			=	' rounded';
						break;
					case 'roundedbordered':
						$galleryStyle			=	' img-thumbnail';
						break;
					case 'circle':
						$galleryStyle			=	' rounded-circle';
						break;
					case 'circlebordered':
						$galleryStyle			=	' img-thumbnail rounded-circle';
						break;
				}
			}

			if ( $fieldName === 'avatar' ) {
				$galleryStyle					.=	' cbImgAvatar';
			}

			$return								.=	'<div id="cbimagefile_gallery_' . htmlspecialchars( $fieldName ) . '" class="ml-n2 mr-n2 mb-n3 row no-gutters' . ( $hasChoices ? ' mt-3 hidden' : null ) . ' cbImageFieldGallery">';

			foreach ( $galleryImages as $i => $galleryImage ) {
				$imgName						=	ucfirst( str_replace( '_', ' ', preg_replace( '/^(.*)\..*$/', '\1', preg_replace( '/^tn/', '', $galleryImage ) ) ) );

				if ( $fieldName === 'canvas' ) {
					$return						.=		'<div class="position-relative col-12 col-md-6 pb-3 pl-2 pr-2">'
												.			'<input type="radio" name="' . htmlspecialchars( $fieldName ) . '__gallery" id="' . htmlspecialchars( $fieldName ) . '__gallery_' . (int) $i . '" value="' . htmlspecialchars( preg_replace( '/^tn/', '', $galleryImage ) ) . '" class="sr-only' . ( $required == 1 ? ' required' : null ) . '"' . ( $galleryImage == $value ? ' checked' : null ) . ( $hasChoices ? ' disabled="disabled"' : null ) . ' />'
												.			'<label for="' . htmlspecialchars( $fieldName ) . '__gallery_' . (int) $i . '" class="m-0 p-0 w-100">'
												.				'<div style="height: 100px; background-image: url(' . $_CB_framework->getCfg( 'live_site' ) . $galleryPath . '/' . htmlspecialchars( $galleryImage ) . ');" title="' . htmlspecialchars( $imgName ) . '" class="cbImgCanvas cbThumbCanvas' . htmlspecialchars( $galleryStyle ) . '"></div>'
												.			'</label>'
												.		'</div>';
				} else {
					$return						.=		'<div class="position-relative col-auto pb-3 pl-2 pr-2 text-center">'
												.			'<input type="radio" name="' . htmlspecialchars( $fieldName ) . '__gallery" id="' . htmlspecialchars( $fieldName ) . '__gallery_' . (int) $i . '" value="' . htmlspecialchars( preg_replace( '/^tn/', '', $galleryImage ) ) . '" class="sr-only' . ( $required == 1 ? ' required' : null ) . '"' . ( $galleryImage == $value ? ' checked' : null ) . ( $hasChoices ? ' disabled="disabled"' : null ) . ' />'
												.			'<label for="' . htmlspecialchars( $fieldName ) . '__gallery_' . (int) $i . '" class="m-0 p-0 w-100">'
												.				'<img src="' . $_CB_framework->getCfg( 'live_site' ) . $galleryPath . '/' . htmlspecialchars( $galleryImage ) . '" alt="' . htmlspecialchars( $imgName ) . '" title="' . htmlspecialchars( $imgName ) . '" class="cbImgPict cbThumbPict' . htmlspecialchars( $galleryStyle ) . '" />'
												.			'</label>'
												.		'</div>';
				}
			}

			$return								.=	'</div>';
		}

		if ( $canReposition ) {
			$position							=	$user->get( $fieldName . 'position', 50, GetterInterface::INT );

			if ( $position < 0 ) {
				$position						=	0;
			} elseif ( $position > 100 ) {
				$position						=	100;
			}

			$return								.=	'<div id="cbimagefile_position_' . htmlspecialchars( $fieldName ) . '" class="form-group mb-0 mt-3 cb_form_line cbImageFieldPosition hidden">'
												.		'<div class="cbCanvasReposition">'
												.			'<div class="cbCanvasRepositionSelect"></div>'
												.			'<img src="' . $this->_avatarLivePath( $field, $user, false, 2 ) . '" class="cbCanvasRepositionImage" />'
												.		'</div>'
												.		'<input type="hidden" name="' . htmlspecialchars( $fieldName ) . '__position" value="' . $position . '" disabled="disabled" />'
												.	'</div>';
		}

		return $return;
	}

	/**
	 * This event-driven method is temporary until we get another API for deleting each field:
	 *
	 * @param  UserTable  $user
	 */
	function onBeforeDeleteUser( $user ) {
		global $_CB_framework, $_CB_database;

		$query					=	'SELECT ' . $_CB_database->NameQuote( 'name' )
								.	"\n FROM " . $_CB_database->NameQuote( '#__comprofiler_fields' )
								.	"\n WHERE " . $_CB_database->NameQuote( 'type' ). " = " . $_CB_database->Quote( 'image' );
		$_CB_database->setQuery( $query );
		$imageFields			=	$_CB_database->loadResultArray();

		if ( $imageFields ) {
			$imgPath		=	$_CB_framework->getCfg( 'absolute_path' ) . '/images/comprofiler/';

			foreach ( $imageFields as $imageField ) {
				if ( isset( $user->$imageField ) && ( $user->$imageField != '' ) && ( strpos( $user->$imageField, 'gallery/' ) === false ) ) {
					if ( file_exists( $imgPath . $user->$imageField ) ) {
						@unlink( $imgPath . $user->$imageField );

						if ( file_exists( $imgPath . 'tn' . $user->$imageField ) ) {
							@unlink( $imgPath . 'tn' . $user->$imageField );
						}
					}
				}
			}
		}
	}

	public function loadDefaultImages( $name, $value, $control_name, $basePath = 'avatar' ) {
		$values					=	array();
		$values[]				=	moscomprofilerHTML::makeOption( '', CBTxt::T( 'Normal CB Default' ) );
		$values[]				=	moscomprofilerHTML::makeOption( 'none', CBTxt::T( 'No image' ) );
		$values[]				=	moscomprofilerHTML::makeOption( 'initial', ( $basePath === 'canvas' ? CBTxt::T( 'Unique Color' ) : CBTxt::T( 'First and Last Initial' ) ) );

		if ( is_dir( selectTemplate( 'absolute_path', null, 1 ) . '/images/' . $basePath ) ) {
			foreach ( scandir( selectTemplate( 'absolute_path', null, 1 ) . '/images/' . $basePath ) as $avatar ) {
				if ( ( ! preg_match( '/^tn/', $avatar ) ) && preg_match( '!^[\w-]+[.](jpg|jpeg|png|gif)$!', $avatar ) ) {
					$values[]	=	moscomprofilerHTML::makeOption( $avatar, $avatar );
				}
			}
		}

		return $values;
	}

	public function loadDefaultCanvasImages( $name, $value, $control_name ) {
		return $this->loadDefaultImages( $name, $value, $control_name, 'canvas' );
	}

	/**
	 * Returns array of image files based off path
	 *
	 * @param string $path
	 * @param string $size all: return all images; any: return thumbnail or full size
	 * @return array
	 */
	protected function displayImagesGallery( $path, $size = 'any' ) {
		$dir									=	@opendir( $path );
		$images									=	array();
		$index									=	0;

		while ( true == ( $file = @readdir( $dir ) ) ) {
			if ( ( $file != '.' ) && ( $file != '..' ) && is_file( $path . '/' . $file ) && ( ! is_link( $path. '/' . $file ) ) ) {
				if ( preg_match( '/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $file ) ) {
					if ( $size === 'all' ) {
						$images[$index]			=	$file;
					} elseif ( preg_match( '/^tn/', $file ) ) {
						$full					=	array_search( preg_replace( '/^tn/', '', $file ), $images );

						if ( $full !== false ) {
							unset( $images[$full] );
						}

						$images[$index]			=	$file;
					} else {
						$thumb					=	array_search( 'tn' . $file, $images );

						if ( $thumb === false ) {
							$images[$index]		=	$file;
						}
					}

					$index++;
				}
			}
		}

		@closedir( $dir );

		$images									=	array_values( $images );

		@sort( $images );
		@reset( $images );

		return $images;
	}

	/**
	 * Returns file size formatted from bytes
	 *
	 * @param int $bytes
	 * @return string
	 */
	private function formattedFileSize( $bytes )
	{
		if ( $bytes >= 1099511627776 ) {
			$size	=	CBTxt::Th( 'FILESIZE_FORMATTED_TB', '%%COUNT%% TB|%%COUNT%% TBs', array( '%%COUNT%%' => (float) number_format( $bytes / 1099511627776, 2, '.', '' ) ) );
		} elseif ( $bytes >= 1073741824 ) {
			$size	=	CBTxt::Th( 'FILESIZE_FORMATTED_GB', '%%COUNT%% GB|%%COUNT%% GBs', array( '%%COUNT%%' => (float) number_format( $bytes / 1073741824, 2, '.', '' ) ) );
		} elseif ( $bytes >= 1048576 ) {
			$size	=	CBTxt::Th( 'FILESIZE_FORMATTED_MB', '%%COUNT%% MB|%%COUNT%% MBs', array( '%%COUNT%%' => (float) number_format( $bytes / 1048576, 2, '.', '' ) ) );
		} elseif ( $bytes >= 1024 ) {
			$size	=	CBTxt::Th( 'FILESIZE_FORMATTED_KB', '%%COUNT%% KB|%%COUNT%% KBs', array( '%%COUNT%%' => (float) number_format( $bytes / 1024, 2, '.', '' ) ) );
		} else {
			$size	=	CBTxt::Th( 'FILESIZE_FORMATTED_B', '%%COUNT%% B|%%COUNT%% Bs', array( '%%COUNT%%' => (float) number_format( $bytes, 2, '.', '' ) ) );
		}

		return $size;
	}
}
class CBfield_status extends cbFieldHandler {

	/**
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		global $_CB_framework, $ueConfig;

		$return								=	null;

		if ( ( $ueConfig['allow_onlinestatus'] == 1 ) ) {
			$lastTime						=	$_CB_framework->userOnlineLastTime( $user->id );
			$isOnline						=	( $lastTime != null );

			switch ( $output ) {
				case 'html':
				case 'rss':
					$useLayout				=	true;

					if ( isset( $user ) && $user->id ) {
						if ( $isOnline > 0 ) {
							$value			=	CBTxt::T( 'UE_ISONLINE', 'ONLINE' );
							$icon			=	'circle';
							$class			=	'cb_online text-success';
						} else {
							$value			=	CBTxt::T( 'UE_ISOFFLINE', 'OFFLINE' );
							$icon			=	'circle-o';
							$class			=	'cb_offline text-danger';
						}

						$imgMode			=	$field->get( '_imgMode', null, GetterInterface::INT ); // For B/C

						if ( $imgMode === null ) {
							$imgMode		=	$field->params->get( ( $reason == 'list' ? 'displayModeList' : 'displayMode' ), 2, GetterInterface::INT );
						}

						switch ( $imgMode ) {
							case 0:
								$return		=	'<span class="' . $class . '">' . htmlspecialchars( $value ) . '</span>';
								break;
							case 1:
								$return		=	'<span class="' . $class . '"><span class="fa fa-' . $icon . '" title="' . htmlspecialchars( $value ) . '"></span></span>';
								$useLayout	=	false; // We don't want to use layout for icon only display as we use it externally
								break;
							case 2:
								$return		=	'<span class="' . $class . '"><span class="fa fa-' . $icon . '"></span> ' . htmlspecialchars( $value ) . '</span>';
								break;
						}
					}

					if ( $useLayout ) {
						$return				=	$this->formatFieldValueLayout( $return, $reason, $field, $user );
					}
					break;
				case 'htmledit':
//					if ( $reason == 'search' ) {
//						$choices			=	array();
//						$choices[]			=	moscomprofilerHTML::makeOption( '', CBTxt::T( 'No preference' );
//						$choices[]			=	moscomprofilerHTML::makeOption( '1', CBTxt::T( 'Is Online' ) );
//						$choices[]			=	moscomprofilerHTML::makeOption( '0', CBTxt::T( 'Is Offline' ) );
//
//						$col				=	$field->name;
//						$value				=	$user->$col;
//
//						$return				=	$this->_fieldSearchModeHtml( $field, $user, $this->_fieldEditToHtml( $field, $user, $reason, 'input', 'select', $value, null, $choices ), 'singlechoice', $list_compare_types );
//					}
					break;
				case 'json':
				case 'php':
				case 'xml':
				case 'csvheader':
				case 'fieldslist':
				case 'csv':
				default:
					if ( isset( $user ) && $user->id ) {
						$return				=	$this->_formatFieldOutputIntBoolFloat( $field->name, ( $isOnline > 0 ? 'true' : 'false' ), $output );
					}
					break;
			}
		}

		return $return;
	}

	/**
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 */
	public function prepareFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		$this->_prepareFieldMetaSave( $field, $user, $postdata, $reason );
		// nothing to do, Status fields don't save :-)
	}

	/**
	 * Finder:
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $searchVals  RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata    Typically $_POST (but not necessarily), filtering required.
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @param  string      $reason      'edit' for save profile edit, 'register' for registration, 'search' for searches
	 * @return cbSqlQueryPart[]
	 */
	function bindSearchCriteria( &$field, &$searchVals, &$postdata, $list_compare_types, $reason ) {
		return array(); // Online Status doesn't currently have searching
	}
}
class CBfield_counter extends cbFieldHandler {
	/**
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		$oReturn							=	'';

		if ( is_object( $user ) ) {
			$values							=	array();
			foreach ( $field->getTableColumns() as $col ) {
				$values[]					=	(int) $user->$col;
			}
			$value							=	implode( ', ', $values );

			switch ( $output ) {
				case 'html':
				case 'rss':
					$oReturn				=	$this->formatFieldValueLayout( $value, $reason, $field, $user );
					break;

				case 'htmledit':
					$oReturn				=	null;

					if ( $reason == 'search' ) {
						$minNam				=	$field->name . '__minval';
						$maxNam				=	$field->name . '__maxval';

						$minVal				=	$user->get( $minNam );
						$maxVal				=	$user->get( $maxNam );

						if ( $maxVal === null ) {
							$maxVal			=	99999;
						}

						$choices			=	array();

						for ( $i = 0 ; $i <= 10000 ; ( $i < 5 ? $i += 1 : ( $i < 30 ? $i += 5 : ( $i < 100 ? $i += 10 : ( $i < 1000 ? $i += 100 : $i += 1000 ) ) ) ) ) {
							$choices[]		=	moscomprofilerHTML::makeOption( ( $i == 0 ? 0 : (string) $i ), $i );
						}

						$fieldNameSave		=	$field->name;
						$field->name		=	$minNam;

						$minHtml			=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', 'select', $minVal, null, $choices );

						$field->name		=	$maxNam;

						$choices[]			=	moscomprofilerHTML::makeOption( '99999', 'UE_ANY' ); // CBTxt::T( 'UE_ANY', 'Any' )

						$maxHtml			=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', 'select', $maxVal, null, $choices );

						$field->name		=	$fieldNameSave;

						$oReturn			=	$this->_fieldSearchRangeModeHtml( $field, $user, $output, $reason, $value, $minHtml, $maxHtml, $list_compare_types );
					}
					break;

				case 'json':
				case 'php':
				case 'xml':
				case 'csvheader':
				case 'fieldslist':
				case 'csv':
				default:
					$oReturn				=	$this->_formatFieldOutputIntBoolFloat( $field->name, $value, $output );;
					break;
			}
		}
		return $oReturn;
	}

	/**
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 */
	public function prepareFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		$this->_prepareFieldMetaSave( $field, $user, $postdata, $reason );
		// nothing to do, counter Status fields don't save :-)
	}

	/**
	 * Finder:
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $searchVals  RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata    Typically $_POST (but not necessarily), filtering required.
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @param  string      $reason      'edit' for save profile edit, 'register' for registration, 'search' for searches
	 * @return cbSqlQueryPart[]
	 */
	function bindSearchCriteria( &$field, &$searchVals, &$postdata, $list_compare_types, $reason ) {
		$query							=	array();

		$col							=	$field->name;
		$minNam							=	$col . '__minval';
		$maxNam							=	$col . '__maxval';

		$searchMode						=	$this->_bindSearchRangeMode( $field, $searchVals, $postdata, $minNam, $maxNam, $list_compare_types );

		if ( $searchMode ) {
			$minVal						=	(int) cbGetParam( $postdata, $minNam, 0 );
			$maxVal						=	(int) cbGetParam( $postdata, $maxNam, 0 );

			if ( $minVal && ( $minVal != 0 ) ) {
				$searchVals->$minNam	=	$minVal;

				$operator				=	( $searchMode == 'isnot' ? ( $minVal == $maxVal ? '<' : '<=' ) : '>=' );

				$min					=	$this->_intToSql( $field, $col, $minVal, $operator, $searchMode );
			} else {
				$min					=	null;
			}

			if ( $maxVal && ( $maxVal != 99999 ) ) {
				$searchVals->$maxNam	=	$maxVal;

				$operator				=   ( $searchMode == 'isnot' ? ( $maxVal == $minVal ? '>' : '>=' ) : '<=' );

				$max					=	$this->_intToSql( $field, $col, $maxVal, $operator, $searchMode );
			} else {
				$max					=	null;
			}

			if ( $min && $max ) {
				$sql					=	new cbSqlQueryPart();
				$sql->tag				=	'column';
				$sql->name				=	$col;
				$sql->table				=	$field->table;
				$sql->type				=	'sql:operator';
				$sql->operator			=	( $searchMode == 'isnot' ? 'OR' : 'AND' );
				$sql->searchmode		=	$searchMode;

				$sql->addChildren( array( $min, $max ) );

				$query[]				=	$sql;
			} elseif ( $min ) {
				$query[]				=	$min;
			} elseif ( $max ) {
				$query[]				=	$max;
			}
		}

		return $query;
	}

	/**
	 * Internal function to build SQL request
	 * @access private
	 *
	 * @param  FieldTable      $field
	 * @param  string          $col
	 * @param  int             $value
	 * @param  string          $operator
	 * @param  string          $searchMode
	 * @return cbSqlQueryPart
	 */
	function _intToSql( &$field, $col, $value, $operator, $searchMode ) {
		$value							=	(int) $value;
		// $this->validate( $field, $user, $col, $value, $postdata, $reason );
		$sql							=	new cbSqlQueryPart();
		$sql->tag						=	'column';
		$sql->name						=	$col;
		$sql->table						=	$field->table;
		$sql->type						=	'sql:field';
		$sql->operator					=	$operator;
		$sql->value						=	$value;
		$sql->valuetype					=	'const:int';
		$sql->searchmode				=	$searchMode;
		return $sql;
	}
}

class CBfield_connections extends CBfield_counter {
	/**
	 * Formatter:
	 * Returns a field row in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output      'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $formatting  'tr', 'td', 'div', 'span', 'none',   'table'??
	 * @param  string      $reason      'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'search' for searches
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getFieldRow( &$field, &$user, $output, $formatting, $reason, $list_compare_types ) {
		global $ueConfig;

		if ( $ueConfig['allowConnections'] ) {
			return parent::getFieldRow( $field, $user, $output, $formatting, $reason, $list_compare_types );
		}
		return null;
	}
	/**
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		global $_CB_framework, $ueConfig;

		$oReturn							=	null;

		if ( $ueConfig['allowConnections'] && is_object( $user ) ) {
			$cbCon							=	new cbConnection( $_CB_framework->myId() );
			$value							=	$cbCon->getConnectionsCount( $user->id );

			switch ( $output ) {
				case 'html':
				case 'rss':
					$oReturn				=	$this->formatFieldValueLayout( $value, $reason, $field, $user );
					break;

				case 'htmledit':
					// $oReturn				=	parent::getField( $field, $user, $output, $reason, $list_compare_types );
					$oReturn				=	null;		//TBD for now no searches...not optimal in SQL anyway.
					break;

				case 'json':
				case 'php':
				case 'xml':
				case 'csvheader':
				case 'fieldslist':
				case 'csv':
				default:
					$oReturn				=	$this->_formatFieldOutputIntBoolFloat( $field->name, $value, $output );;
					break;
			}
		}
		return $oReturn;
	}
	/**
	 * Finder:
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $searchVals  RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata    Typically $_POST (but not necessarily), filtering required.
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @param  string      $reason      'edit' for save profile edit, 'register' for registration, 'search' for searches
	 * @return cbSqlQueryPart[]
	 */
	function bindSearchCriteria( &$field, &$searchVals, &$postdata, $list_compare_types, $reason ) {
		global $ueConfig;

		$searchMode						=	$this->_bindSearchMode( $field, $searchVals, $postdata, 'none', $list_compare_types );
		$query							=	array();
		if ( $ueConfig['allowConnections'] && $searchMode ) {
			$col						=	$field->name;
			$minNam						=	$col . '__minval';
			$maxNam						=	$col . '__maxval';
			$minVal						=	(int) cbGetParam( $postdata, $minNam, 0 );
			$maxVal						=	(int) cbGetParam( $postdata, $maxNam, 0 );
			if ( $minVal && ( $minVal != 0 ) ) {
				$searchVals->$minNam	=	$minVal;
				$query[]				=	$this->_intToSql( $field, $col, $minVal, '>=', $searchMode );
			}
			if ( $maxVal && ( $maxVal != 0 ) ) {
				$searchVals->$maxNam	=	$maxVal;
				$query[]				=	$this->_intToSql( $field, $col, $maxVal, '<=', $searchMode );
			}
		}
		return $query;
	}
	/**
	 * Internal function to build SQL request
	 * @access private
		<data name="change_logs" type="sql:count" distinct="id"  table="#__cpay_history" class="cbpaidHistory">
			<joinkeys dogroupby="true">
				<column name="table_name"   operator="=" value="#__cpay_payment_baskets" type="sql:field" valuetype="const:string" />
				<column name="table_key_id" operator="=" value="id" type="sql:field" valuetype="sql:field" />
			</joinkeys>
		</data>

		<where>
			<column name="id"     operator="=" value="plan_id" type="int"       valuetype="sql:formula">
				<data name="plan_id" type="sql:field" table="#__cpay_payment_items" class="cbpaidPayementItem" key="plan_id" value="id" valuetype="sql:field">
					<data name="basket_id" type="sql:field" table="#__cpay_payment_baskets" class="cbpaidPayementBasket" key="id" value="payment_basket_id" valuetype="sql:field">
						<where>
							<column name="payment_status" operator="=" value="Completed" type="sql:field" valuetype="const:string" />
						</where>
					</data>
				</data>
		    </column>
		</where>

		<column name="id"     operator="=" value="plan_id" type="int"       valuetype="sql:formula">
			<data name="plan_id" type="sql:field" table="#__cpay_payment_items" class="cbpaidPayementItem" key="plan_id" value="id" valuetype="sql:field">
				<data name="basket_id" type="sql:field" table="#__cpay_payment_baskets" class="cbpaidPayementBasket" key="id" value="payment_basket_id" valuetype="sql:field">
					<where>
						<column name="payment_status" operator="=" value="Completed" type="sql:field" valuetype="const:string" />
					</where>
				</data>
			</data>
	    </column>

	 * @param  FieldTable      $field
	 * @param  string          $col
	 * @param  int             $value
	 * @param  string          $operator
	 * @param  string          $searchMode
	 * @return cbSqlQueryPart
	 */
	function _intToSql( &$field, $col, $value, $operator, $searchMode ) {
		$value							=	(int) $value;
		// $this->validate( $field, $user, $col, $value, $postdata, $reason );
		$sql							=	new cbSqlQueryPart();
		$sql->tag						=	'column';
		$sql->name						=	$field->name;
		$sql->table						=	'#__comprofiler_members';
		$sql->type						=	'sql:count';
		$sql->distinct					=	'memberid';
		$sql->operator					=	$operator;
		$sql->value						=	$value;
		$sql->valuetype					=	'const:int';
		$sql->searchmode				=	$searchMode;
		$sql->key						=	'id';
		$sql->keyvalue					=	'referenceid';
		return $sql;
	}
}

class CBfield_formatname extends cbFieldHandler {
	/**
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		global $_CB_framework, $ueConfig, $_PLUGINS;

		$oReturn								=	'';
		if ( isset( $user ) && $user->id ) {

		$value									=	$user->getFormattedName();

			switch ( $output ) {
				case 'html':
				case 'rss':
					$profileLink				=	$user->get( '_allowProfileLink', $field->get( '_allowProfileLink', null, GetterInterface::BOOLEAN ), GetterInterface::BOOLEAN ); // For B/C

					if ( $profileLink === null ) {
						$profileLink			=	$field->params->get( 'fieldProfileLink', true, GetterInterface::BOOLEAN );
					}

					if ( $profileLink && ( $reason != 'profile') ) {
						$profileURL				=	$_CB_framework->userProfileUrl( $user->id, false );

						if ( $field->params->get( 'fieldHoverCanvas', false, GetterInterface::BOOLEAN ) ) {
							$canvasPlugins		=	$_PLUGINS->trigger( 'onHoverCanvasDisplay', array( $field, $user, $output, $reason, $list_compare_types ) );
							$canvasContent		=	$field->params->get( 'fieldHoverCanvasContent', null, GetterInterface::HTML );
							$canvasWidth		=	$field->params->get( 'fieldHoverCanvasWidth', 300, GetterInterface::INT );

							if ( ! $canvasWidth ) {
								$canvasWidth	=	300;
							}

							$cbUser				=	CBuser::getInstance( $user->get( 'id', 0, GetterInterface::INT ), false );

							$tooltip			=	'<div class="card no-overflow cbCanvasLayout cbCanvasLayoutSm">'
												.		'<div class="card-header p-0 position-relative cbCanvasLayoutTop">'
												.			'<div class="position-absolute cbCanvasLayoutBackground">'
												.				$cbUser->getField( 'canvas', null, 'html', 'none', 'list', 0, true, array( 'params' => array( 'fieldProfileLink' => false ) ) )
												.			'</div>'
												.		'</div>'
												.		'<div class="position-relative cbCanvasLayoutBottom">'
												.			'<div class="position-absolute cbCanvasLayoutPhoto">'
												.				$cbUser->getField( 'avatar', null, 'html', 'none', 'list', 0, true )
												.			'</div>'
												.		'</div>'
												.		'<div class="card-body p-2 position-relative cbCanvasLayoutBody">';

							if ( $canvasContent ) {
								$tooltip		.=			'<div class="cbCanvasLayoutContent">'
												.				$cbUser->replaceUserVars( $canvasContent )
												.			'</div>';
							} else {
								$tooltip		.=			'<div class="text-truncate cbCanvasLayoutContent">'
												.				$cbUser->getField( 'onlinestatus', null, 'html', 'none', 'profile', 0, true, array( 'params' => array( 'displayMode' => 1 ) ) )
												.				' ' . $cbUser->getField( 'formatname', null, 'html', 'none', 'list', 0, true, array( 'params' => array( 'fieldHoverCanvas' => false ) ) )
												.			'</div>';
							}

							$tooltip			.=			( $canvasPlugins ? '<div class="cbCanvasLayoutContent mt-1">' . implode( '', $canvasPlugins ) . '</div>' : null )
												.		'</div>'
												.	'</div>';

							$value				=	cbTooltip( null, $tooltip, null, $canvasWidth, null, $value, $profileURL, 'data-cbtooltip-closefixed="true" data-cbtooltip-closedelay="200" data-cbtooltip-classes="qtip-canvas"' );
						} else {
							$value				=	'<a href="' . htmlspecialchars( $profileURL ) . '">' . $value . '</a>';
						}
					}

					$oReturn					=	$this->formatFieldValueLayout( $value, $reason, $field, $user );
					break;

				case 'htmledit':
					$oReturn					=	null;
					break;

				case 'json':
				case 'php':
				case 'xml':
				case 'csvheader':
				case 'fieldslist':
				case 'csv':
				default:
					$oReturn					=	$this->_formatFieldOutput( $field->name, $value, $output );;
					break;
			}
		}
		return $oReturn;
	}
	/**
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 */
	public function prepareFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		$this->_prepareFieldMetaSave( $field, $user, $postdata, $reason );
		// nothing to do, Formatted names fields don't save :-)
	}
	/**
	 * Finder:
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 * @return cbSqlQueryPart[]
	 */
	function bindSearchCriteria( &$field, &$user, &$postdata, $list_compare_types, $reason ) {
		return array();
	}
}
class CBfield_delimiter extends cbFieldHandler {
	/**
	 * Returns a DELIMITER field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		$value			=	cbUnHtmlspecialchars( $field->description ); //TBD: unhtml is kept for backwards database compatibility until CB 2.0

		if ( $field->params->get( 'field_content_plugins', 0 ) ) {
			$value		=	Application::Cms()->prepareHtmlContentPlugins( $value, 'field.custom', $user->id );
		}

		$return			=	$this->_formatFieldOutput( $field->name, cbReplaceVars( $value, $user, true, true, array( 'reason' => $reason ) ), $output, false );

		if ( $output == 'htmledit' ) {
			$return		.=	$this->_fieldIconsHtml( $field, $user, $output, $reason, null, null, $value, null, null, false, false );
		}

		return $return;
	}
	/**
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 */
	public function prepareFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		$this->_prepareFieldMetaSave( $field, $user, $postdata, $reason );
		// nothing to do, Delimiter fields don't save :-)
	}
	/**
	 * Finder:
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 * @return cbSqlQueryPart[]
	 */
	function bindSearchCriteria( &$field, &$user, &$postdata, $list_compare_types, $reason ) {
		return array();
	}
}

class CBfield_userparams extends cbFieldHandler
{
	/**
	 * Initializer:
	 * Puts the default value of $field into $user (for registration or new user in backend)
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $reason      'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 */
	public function initFieldToDefault( &$field, &$user, $reason ) {
	}

	/**
	 * Returns a USERPARAMS field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output      'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $formatting  'table', 'td', 'span', 'div', 'none'
	 * @param  string      $reason      'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getFieldRow( &$field, &$user, $output, $formatting, $reason, $list_compare_types )
	{
		global $_CB_framework;

		// Load com_users language files so the pseudo fields will translate:
		JFactory::getLanguage()->load( 'com_users' );

		$clientId									=	Application::Cms()->getClientId();
		$userParams									=	$this->_getUserParams( $user );
		$pseudoFields								=	array();

		if ( $userParams && ( $clientId || Application::Config()->get( 'frontend_userparams', true, GetterInterface::BOOLEAN ) || isset( $userParams['privacyconsent_privacy'] ) || isset( $userParams['terms_terms'] ) ) ) {
			if ( $clientId ) {
				$excludeParams						=	array();
			} else {
				$excludeParams						=	explode( '|*|', $field->params->get( 'hide_userparams', null, GetterInterface::STRING ) );
			}

			// Loop through user params and convert them to psuedo fields:
			foreach ( $userParams as $paramId => $userParam ) {
				// Privacy Consent and Terms are output as params fields through same API so we need to allow them to bypass frontend_userparams setting as it can't block them:
				if ( ( ! Application::Config()->get( 'frontend_userparams', true, GetterInterface::BOOLEAN ) ) && ( ! in_array( $paramId, array( 'privacyconsent_privacy', 'terms_terms' ) ) )
					 || ( $excludeParams && in_array( $paramId, $excludeParams ) )
				) {
					continue;
				}

				$paramName							=	$userParam->name;

				if ( ! $paramName ) {
					continue;
				}

				$paramField							=	new FieldTable( $field->getDbo() );
				$paramField->fieldid				=	$paramId;
				$paramField->name					=	$paramName;
				$paramField->type					=	'param';
				$paramField->title					=	JText::_( $userParam->title );
				$paramField->description			=	JText::_( $userParam->description );
				$paramField->required				=	( $userParam->required !== null ? ( $userParam->required ? 1 : 0 ) : null );
				$paramField->_html					=	$userParam->input;

				// Check if the label has a modal and if it does convert it to a cbtooltip modal:
				if ( preg_match( '/<a href="([^"]+)" class="modal"/i', $userParam->label, $matches ) ) {
					$modalWidth						=	800;
					$modalHeight					=	500;

					if ( preg_match( '/size: {x:(\d+), y:(\d+)}}/i', $userParam->label, $sizes ) ) {
						$modalWidth					=	(int) $sizes[1];
						$modalHeight				=	(int) $sizes[2];
					}

					$modal							=	'<iframe class="d-block m-0 p-0 border-0 cbTermsModalURL" height="100%" width="100%" src="' . $matches[1] . '"></iframe>'; // URL already escaped

					$paramField->title				=	cbTooltip( null, $modal, null, array( $modalWidth, $modalHeight ), null, $paramField->title, 'javascript:void(0);', 'class="cbTermsLink" data-hascbtooltip="true" data-cbtooltip-modal="true"' );
				}

				$description						=	null;

				if ( $paramField->description ) {
					$description					=	cbTooltip( null, $paramField->description, $paramField->title, null, null, null, null, 'data-hascbtooltip="true"' );
				}

				// Ensure text, select, and textarea fields are CB styled:
				if ( ! preg_match( '/<(?:input type="text"|select|textarea)[^>]*class[^>]*>/i', $paramField->_html ) ) {
					$paramField->_html				=	preg_replace( '/<(input type="text"|select|textarea)/i', '<$1 class="form-control"' . $description, $paramField->_html );
				}

				// Remove the helpsite refresh button as it doesn't do anything here:
				if ( $paramId == 'params_helpsite' ) {
					$paramField->_html				=	preg_replace( '%<button.*>.*</button>%si', '', $paramField->_html );
				}

				$pseudoFields[]						=	$paramField;
			}
		}

		// Two factor authentication params (only available to already registered users):
		if ( checkJversion( '3.2+' ) && $user->get( 'id', 0, GetterInterface::INT ) ) {
			require_once ( $_CB_framework->getCfg( 'absolute_path' ) . '/administrator/components/com_users/helpers/users.php' );

			$twoFactorMethods					=	UsersHelper::getTwoFactorMethods();

			if ( count( $twoFactorMethods ) > 1 ) {
				$js								=	"$( '.JoomlaTwoFactorMethod' ).on( 'change', function() {"
												.		"$( this ).nextAll( 'div' ).hide();"
												.		"$( '#twofactor_' + $( this ).val() ).show();"
												.	"});";

				$_CB_framework->outputCbJQuery( $js );

				require_once ( $_CB_framework->getCfg( 'absolute_path' ) . '/components/com_users/models/profile.php' );

				$model							=	new UsersModelProfile();
				$otpConfig						=	$model->getOtpConfig( $user->id );
				$twoFactorForms					=	$model->getTwofactorform( $user->id );
				$twoFactor						=	moscomprofilerHTML::selectList( $twoFactorMethods, 'jform[twofactor][method]', 'class="form-control JoomlaTwoFactorMethod"', 'value', 'text', (string) $otpConfig->method, false, false, false, false );

				foreach ( $twoFactorForms as $twoFactorForm ) {
					$twoFactor					.=	'<div id="twofactor_' . htmlspecialchars( $twoFactorForm['method'] ) . '" style="' . ( $twoFactorForm['method'] == $otpConfig->method ? 'display: block;' : 'display: none;' ) . ' margin-top: 10px;">'
												.		str_replace( 'input-small', 'form-control', $twoFactorForm['form'] )
												.	'</div>';
				}

				$paramField						=	new FieldTable( $field->getDbo() );
				$paramField->fieldid			=	'twofactor';
				$paramField->name				=	null;
				$paramField->title				=	JText::_( 'COM_USERS_PROFILE_TWOFACTOR_LABEL' );
				$paramField->description		=	JText::_( 'COM_USERS_PROFILE_TWOFACTOR_DESC' );
				$paramField->type				=	'param';
				$paramField->_html				=	$twoFactor;

				$pseudoFields[]					=	$paramField;
			}
		}

		if ( $clientId ) {
			$i_am_super_admin				=	Application::MyUser()->isSuperAdmin();
			$canBlockUser					=	Application::MyUser()->isAuthorizedToPerformActionOnAsset( 'core.edit.state', 'com_users' );
			$canEmailEvents					=	( ( $user->id == 0 ) && $canBlockUser )
												|| Application::User( (int) $user->id )->isAuthorizedToPerformActionOnAsset( 'core.edit.state', 'com_users' )
												|| Application::User( (int) $user->id )->canViewAccessLevel( Application::Config()->get( 'moderator_viewaccesslevel', 3, \CBLib\Registry\GetterInterface::INT ) );

			$lists							=	array();

			if ( $canBlockUser ) {

				// ensure user can't add group higher than themselves
				$gtree						=	$_CB_framework->acl->get_groups_below_me();

				if ( ( ! $i_am_super_admin )
					&& $user->id
					&& Application::User( (int) $user->id )->isAuthorizedToPerformActionOnAsset( 'core.manage', 'com_users' )
					&& ( Application::User( (int) $user->id )->isAuthorizedToPerformActionOnAsset( 'core.edit', 'com_users' )
						 ||  Application::User( (int) $user->id )->isAuthorizedToPerformActionOnAsset( 'core.edit.state', 'com_users' )
					   )
				)
				{
					$disabled				=	' disabled="disabled"';
				} else {
					$disabled				=	'';
				}
				if ( $user->id ) {
					$gids					=	cbToArrayOfInt( Application::User( (int) $user->id )->getAuthorisedGroups( false ) );
				} else {
					$gids					=	(int) $_CB_framework->getCfg( 'new_usertype' );
				}
				$lists['gid']				=	moscomprofilerHTML::selectList( $gtree, 'gid[]', 'class="form-control" size="11" multiple="multiple"' . $disabled, 'value', 'text', $gids, 2, false, null, false );

				// build the html select lists:
				$lists['block']					=	moscomprofilerHTML::yesnoSelectList( 'block', 'class="form-control"', (int) $user->block, CBTxt::T( 'No' ), CBTxt::T( 'Yes' ) );

				$list_banned					=	array();
				$list_banned[]					=	moscomprofilerHTML::makeOption( '0', CBTxt::T( 'No' ) );
				$list_banned[]					=	moscomprofilerHTML::makeOption( '1', CBTxt::T( 'Yes' ) );
				$list_banned[]					=	moscomprofilerHTML::makeOption( '2', CBTxt::T( 'Pending' ) );
				$lists['banned']				=	moscomprofilerHTML::selectList( $list_banned, 'banned', 'class="form-control"', 'value', 'text', (int) $user->banned, 2, false, null, false );

				$list_approved					=	array();
				$list_approved[]				=	moscomprofilerHTML::makeOption( '0', CBTxt::T( 'No' ) );
				$list_approved[]				=	moscomprofilerHTML::makeOption( '1', CBTxt::T( 'Yes' ) );
				$list_approved[]				=	moscomprofilerHTML::makeOption( '2', CBTxt::T( 'Rejected' ) );
				$lists['approved']				=	moscomprofilerHTML::selectList( $list_approved, 'approved', 'class="form-control"', 'value', 'text', (int) $user->approved, 2, false, null, false );

				$lists['confirmed']				=	moscomprofilerHTML::yesnoSelectList( 'confirmed', 'class="form-control"', (int) $user->confirmed );

				$lists['sendEmail']				=	moscomprofilerHTML::yesnoSelectList( 'sendEmail', 'class="form-control"', (int) $user->sendEmail );

				if ( checkJversion( '3.2+' ) ) {
					$lists['requireReset']		=	moscomprofilerHTML::yesnoSelectList( 'requireReset', 'class="form-control"', (int) $user->requireReset );
				}

				// build the pseudo field objects:
				$paramField					=	new FieldTable( $field->getDbo() );
				$paramField->title			=	'Group';								// For translation parser:  CBTxt::T( 'Group' );
				$paramField->_html			=	$lists['gid'];
				$paramField->description	=	'';
				$paramField->name			=	'gid';
				$paramField->required		=	1;
				$pseudoFields[]				=	$paramField;

				$paramField					=	new FieldTable( $field->getDbo() );
				$paramField->title			=	'Enabled';							// For translation parser:  CBTxt::T( 'Enabled' );
				$paramField->_html			=	$lists['block'];
				$paramField->description	=	'';
				$paramField->name			=	'block';
				$pseudoFields[]				=	$paramField;

				$paramField					=	new FieldTable( $field->getDbo() );
				$paramField->title			=	'Approved';								// For translation parser:  CBTxt::T( 'Approved' );
				$paramField->_html			=	$lists['approved'];
				$paramField->description	=	'';
				$paramField->name			=	'approved';
				$pseudoFields[]				=	$paramField;

				$paramField					=	new FieldTable( $field->getDbo() );
				$paramField->title			=	'Confirmed';								// For translation parser:  CBTxt::T( 'Confirmed' );
				$paramField->_html			=	$lists['confirmed'];
				$paramField->description	=	'';
				$paramField->name			=	'confirmed';
				$pseudoFields[]				=	$paramField;

				$paramField					=	new FieldTable( $field->getDbo() );
				$paramField->title			=	'Banned';								// For translation parser:  CBTxt::T( 'Banned' );
				$paramField->_html			=	$lists['banned'];
				$paramField->description	=	'';
				$paramField->name			=	'banned';
				$pseudoFields[]				=	$paramField;

				if ( checkJversion( '3.2+' ) ) {
					$paramField					=	new FieldTable( $field->getDbo() );
					$paramField->title			=	'Reset Password';								// For translation parser:  CBTxt::T( 'Reset Password' );
					$paramField->_html			=	$lists['requireReset'];
					$paramField->description	=	'';
					$paramField->name			=	'requireReset';
					$pseudoFields[]				=	$paramField;
				}

				$paramField						=	new FieldTable( $field->getDbo() );
				$paramField->title				=	'Receive Moderator Emails';				// For translation parser:  CBTxt::T( 'Receive Moderator Emails' );
				if ($canEmailEvents || $user->sendEmail) {
					$paramField->_html			=	$lists['sendEmail'];
				} else {
					$paramField->_html			=	CBTxt::T( 'No (User\'s group-level doesn\'t allow this)' )
												.	'<input type="hidden" name="sendEmail" value="0" />';
				}
				$paramField->description		=	'';
				$paramField->name				=	'sendEmail';
				$pseudoFields[]					=	$paramField;
			}

			if( $user->id) {
				$paramField					=	new FieldTable( $field->getDbo() );
				$paramField->title			=	'Register Date';								// For translation parser:  CBTxt::T( 'Register Date' );
				$paramField->_html			=	cbFormatDate( $user->registerDate );
				$paramField->description	=	'';
				$paramField->name			=	'registerDate';
				$pseudoFields[]				=	$paramField;

				$paramField					=	new FieldTable( $field->getDbo() );
				$paramField->title			=	'Last Visit Date';								// For translation parser:  CBTxt::T( 'Last Visit Date' );
				$paramField->_html			=	cbFormatDate( $user->lastvisitDate );
				$paramField->description	=	'';
				$paramField->name			=	'lastvisitDate';
				$pseudoFields[]				=	$paramField;

				$paramField					=	new FieldTable( $field->getDbo() );
				$paramField->title			=	'Last Reset Time';								// For translation parser:  CBTxt::T( 'Last Reset Time' );
				$paramField->_html			=	cbFormatDate( $user->lastResetTime );
				$paramField->description	=	'';
				$paramField->name			=	'lastResetTime';
				$pseudoFields[]				=	$paramField;

				$paramField					=	new FieldTable( $field->getDbo() );
				$paramField->title			=	'Password Reset Count';							// For translation parser:  CBTxt::T( 'Password Reset Count' );
				$paramField->_html			=	(int) $user->resetCount;
				$paramField->description	=	'';
				$paramField->name			=	'resetCount';
				$pseudoFields[]				=	$paramField;
			}
		}

		switch ( $output ) {
			case 'htmledit':
				$return						=	null;

				foreach ( $pseudoFields as $paramField ) {
					$paramField->required	=	( $paramField->required === null ? 0 : $paramField->required ); // if the pseudo field doesn't explicitly have required state then it can't be set required
					$paramField->profile	=	0; // pseudo fields have no display output
					$paramField->params		=	$field->params; // prevents api errors accessing non-registry params object

					$return					.=	parent::getFieldRow( $paramField, $user, $output, $formatting, $reason, $list_compare_types );
				}

				return $return;
				break;
			default:
				return null;
				break;
		}
	}

	/**
	 * Accessor:
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types )
	{
		switch ( $output ) {
			case 'htmledit':
				return $field->_html . $this->_fieldIconsHtml( $field, $user, $output, $reason, 'input', 'text', $field->_html, '', null, true, $this->_isRequired( $field, $user, $reason ) && ! $this->_isReadOnly( $field, $user, $reason ) );
				break;

			default:
				return null;
				break;
		}
	}

	/**
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 */
	public function prepareFieldDataSave( &$field, &$user, &$postdata, $reason )
	{
		$this->_prepareFieldMetaSave( $field, $user, $postdata, $reason );

		global $_CB_framework;

		// Nb. frontend registration setting of usertype, gid, block, sendEmail, confirmed, approved
		// are handled in UserTable::bindSafely() so they are available to other plugins.

		$clientId							=	Application::Cms()->getClientId();
		$userParams							=	$this->_getUserParams( $user );
		$userId								=	$user->get( 'id', null, GetterInterface::INT );

		// this is (for now) handled in the core of CB... except params and block/email/approved/confirmed:

		if ( $_CB_framework->getUi() == 2 ) {
			$canBlockUser					=	Application::MyUser()->isAuthorizedToPerformActionOnAsset( 'core.edit.state', 'com_users' );
			if ( $canBlockUser ) {
				$user->gids					=	cbGetParam( $postdata, 'gid', array( 0 ) );

				if ( isset( $postdata['block'] ) ) {
					$user->block			=	cbGetParam( $postdata, 'block', 0 );
				}
				if ( isset( $postdata['approved'] ) ) {
					$user->approved			=	cbGetParam( $postdata, 'approved', 0 );
				}
				if ( isset( $postdata['confirmed'] ) ) {
					$user->confirmed		=	cbGetParam( $postdata, 'confirmed', 0 );
				}
				if ( isset( $postdata['banned'] ) ) {
					$banned					=	cbGetParam( $postdata, 'banned', 0 );

					if ( $banned != $user->banned ) {
						if ( $banned == 1 ) {
							$user->bannedby			=	(int) $_CB_framework->myId();
							$user->banneddate		=	$_CB_framework->getUTCDate();
						} elseif ( $banned == 0 ) {
							$user->unbannedby		=	(int) $_CB_framework->myId();
							$user->unbanneddate		=	$_CB_framework->getUTCDate();
						}
					}

					$user->banned			=	$banned;
				}
				if ( checkJversion( '3.2+' ) && isset( $postdata['requireReset'] ) ) {
					$user->requireReset		=	cbGetParam( $postdata, 'requireReset', 0 );
				}
				if ( isset( $postdata['sendEmail'] ) ) {
					$user->sendEmail		=	cbGetParam( $postdata, 'sendEmail', 0 );
				}
			}
		}

		// User params storage:
		if ( $userParams && ( $clientId || Application::Config()->get( 'frontend_userparams', true, GetterInterface::BOOLEAN ) ) ) {
			// Load existing user params to avoid wiping out any 3rd party custom params on store:
			$params								=	new Registry( $user->get( 'params', null, GetterInterface::RAW ) );

			if ( $clientId ) {
				$excludeParams					=	array();
			} else {
				$excludeParams					=	explode( '|*|', $field->params->get( 'hide_userparams', null, GetterInterface::STRING ) );
			}

			// Loop through user params and prepare them for storage to ensure we only store what is allowed:
			foreach ( $userParams as $paramId => $userParam ) {
				// Privacy Consent and Terms have separate storage behavior so skip them:
				if ( in_array( $paramId, array( 'privacyconsent_privacy', 'terms_terms' ) ) || ( $excludeParams && in_array( $paramId, $excludeParams ) ) ) {
					continue;
				}

				$paramName						=	$userParam->name;

				if ( ! $paramName ) {
					continue;
				}

				// Just validate all user params to strings to at least ensure they're safe:
				$params->set( str_replace( 'params_', '', $paramId ), $this->input( str_replace( 'params_', 'params.', $paramId ), null, GetterInterface::STRING ) );
			}

			$value								=	$params->asJson();

			if ( ( (string) $user->get( 'params', null, GetterInterface::RAW ) ) !== (string) $value ) {
				$this->_logFieldUpdate( $field, $user, $reason, $user->get( 'params', null, GetterInterface::RAW ), $value );
			}

			$user->set( 'params', $value );
		}

		// Two factor authentication params (only available to already registered users):
		if ( checkJversion( '3.2+' ) && $userId ) {
			require_once ( $_CB_framework->getCfg( 'absolute_path' ) . '/administrator/components/com_users/helpers/users.php' );

			$twoFactorMethods					=	UsersHelper::getTwoFactorMethods();

			if ( count( $twoFactorMethods ) > 1 ) {
				require_once ( $_CB_framework->getCfg( 'absolute_path' ) . '/administrator/components/com_users/models/user.php' );

				$model							=	new UsersModelUser();
				$otpConfig						=	$model->getOtpConfig( $userId );
				$twoFactorMethod				=	$this->input( 'jform.twofactor.method', 'none', GetterInterface::COMMAND );
				$twoFactorSaved					=	false;

				if ( $twoFactorMethod != 'none' ) {
					if ( cbGetParam( $postdata, 'jform[twofactor][' . $twoFactorMethod . '][securitycode]' ) !== null ) {
						FOFPlatform::getInstance()->importPlugin( 'twofactorauth' );

						$otpConfigReplies		=	FOFPlatform::getInstance()->runPlugins( 'onUserTwofactorApplyConfiguration', array( $twoFactorMethod ) );

						// Look for a valid reply
						foreach ( $otpConfigReplies as $reply ) {
							if ( ( ! is_object( $reply ) ) || empty( $reply->method ) || ( $reply->method != $twoFactorMethod ) ) {
								continue;
							}

							$otpConfig->method	=	$reply->method;
							$otpConfig->config	=	$reply->config;

							break;
						}

						// Save OTP configuration.
						$model->setOtpConfig( $userId, $otpConfig );

						// Generate one time emergency passwords if required (depleted or not set)
						if ( empty( $otpConfig->otep ) ) {
							$model->generateOteps( $userId );
						}

						$twoFactorSaved			=	true;
					}
				} else {
					$otpConfig->method			=	'none';
					$otpConfig->config			=	array();

					$model->setOtpConfig( $userId, $otpConfig );

					$twoFactorSaved				=	true;
				}

				if ( $twoFactorSaved ) {
					$jUser						=	JUser::getInstance();

					// Reload the user record with the updated OTP configuration
					$jUser->load( $userId );

					$user->otpKey				=	$jUser->get( 'otpKey' );
					$user->otep					=	$jUser->get( 'otep' );
				}
			}
		}

		// Privacy Policy and Terms validation (frontend registration only; they do not output in CB profile edit and can not be consented by admins):
		if ( ( ! Application::Cms()->getClientId() ) && checkJversion( '3.9+' ) && ( $reason == 'register' ) && ( ! $userId ) ) {
			// Validate privacy:
			if ( JPluginHelper::isEnabled( 'system', 'privacyconsent' ) && ( ! $this->input( 'privacyconsent.privacy', 0, GetterInterface::INT ) ) ) {
				$this->_setValidationError( $field, $user, $reason, JText::_( 'PLG_SYSTEM_PRIVACYCONSENT_FIELD_ERROR' ) );
			}

			// Terms is a user plugin so be sure user plugins have been imported before attempting to check it:
			JPluginHelper::importPlugin( 'user' );

			// Validate terms:
			if ( JPluginHelper::isEnabled( 'user', 'terms' ) && ( ! $this->input( 'terms.terms', 0, GetterInterface::INT ) ) ) {
				$this->_setValidationError( $field, $user, $reason, JText::_( 'PLG_USER_TERMS_FIELD_ERROR' ) );
			}
		}
	}

	/**
	 * Mutator:
	 * Prepares field data commit
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save user edit, 'register' for save registration
	 */
	public function commitFieldDataSave( &$field, &$user, &$postdata, $reason )
	{
		global $_CB_database;

		$userId				=	$user->get( 'id', 0, GetterInterface::INT );

		// Privacy Policy and Terms storage (frontend registration only; they do not output in CB profile edit and can not be consented by admins):
		if ( ( ! Application::Cms()->getClientId() ) && checkJversion( '3.9+' ) && ( $reason == 'register' ) && $userId ) {
			if ( JPluginHelper::isEnabled( 'system', 'privacyconsent' ) && $this->input( 'privacyconsent.privacy', 0, GetterInterface::INT ) ) {
				// Get the user's IP address
				$ip			=	$this->getInput()->getNamespaceRegistry( 'server' )->get( 'REMOTE_ADDR', null, GetterInterface::STRING );

				// Get the user agent string
				$userAgent	=	$this->getInput()->getNamespaceRegistry( 'server' )->get( 'HTTP_USER_AGENT', null, GetterInterface::STRING );

				// Create the user note
				$query		=	"INSERT INTO " . $_CB_database->NameQuote( '#__privacy_consents' )
							.	"\n ("
							.		$_CB_database->NameQuote( 'user_id' )
							.		", " . $_CB_database->NameQuote( 'subject' )
							.		", " . $_CB_database->NameQuote( 'body' )
							.		", " . $_CB_database->NameQuote( 'created' )
							.	")"
							.	"\n VALUES ("
							.		$userId
							.		", " . $_CB_database->Quote( 'PLG_SYSTEM_PRIVACYCONSENT_SUBJECT' )
							.		", " . $_CB_database->Quote( JText::sprintf( 'PLG_SYSTEM_PRIVACYCONSENT_BODY', $ip, $userAgent ) )
							.		", " . $_CB_database->Quote( Application::Database()->getUtcDateTime() )
							.	")";
				$_CB_database->setQuery( $query );
				$_CB_database->query();

				$message	=	array(	'action'		=>	'consent',
										'id'			=>	$userId,
										'title'			=>	$user->get( 'name', null, GetterInterface::STRING ),
										'itemlink'		=>	'index.php?option=com_users&task=user.edit&id=' . $userId,
										'userid'		=>	$userId,
										'username'		=>	$user->get( 'username', null, GetterInterface::STRING ),
										'accountlink'	=>	'index.php?option=com_users&task=user.edit&id=' . $userId
									);

				JModelLegacy::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_actionlogs/models', 'ActionlogsModel' );

				/* @var ActionlogsModelActionlog $model */
				$model		=	JModelLegacy::getInstance( 'Actionlog', 'ActionlogsModel' );

				$model->addLog( array( $message ), 'PLG_SYSTEM_PRIVACYCONSENT_CONSENT', 'plg_system_privacyconsent', $userId );
			}

			// Terms is a user plugin so be sure user plugins have been imported before attempting to check it:
			JPluginHelper::importPlugin( 'user' );

			if ( JPluginHelper::isEnabled( 'user', 'terms' ) && $this->input( 'terms.terms', 0, GetterInterface::INT ) ) {
				$message	=	array(	'action'		=>	'consent',
										'id'			=>	$userId,
										'title'			=>	$user->get( 'name', null, GetterInterface::STRING ),
										'itemlink'		=>	'index.php?option=com_users&task=user.edit&id=' . $userId,
										'userid'		=>	$userId,
										'username'		=>	$user->get( 'username', null, GetterInterface::STRING ),
										'accountlink'	=>	'index.php?option=com_users&task=user.edit&id=' . $userId
									);

				JModelLegacy::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_actionlogs/models', 'ActionlogsModel' );

				/* @var ActionlogsModelActionlog $model */
				$model		=	JModelLegacy::getInstance( 'Actionlog', 'ActionlogsModel' );

				$model->addLog( array( $message ), 'PLG_USER_TERMS_LOGGING_CONSENT_TO_TERMS', 'plg_user_terms', $userId );
			}
		}
	}

	/**
	 * Retrieve joomla standard user parameters so that they can be displayed in user edit mode.
	 *
	 * @param  UserTable $user the user being displayed
	 * @return object[]        of user parameter attributes (title,value)
	 */
	private function _getUserParams( $user )
	{
		global $_CB_framework;

		static $cache					=	array();

		$userId							=	$user->get( 'id', 0, GetterInterface::INT );
		$clientId						=	Application::Cms()->getClientId();

		if ( ! isset( $cache[$userId][$clientId] ) ) {
			$jUser						=	$_CB_framework->_getCmsUserObject( $userId );

			if ( checkJversion( '3.0+' ) ) {
				// Include jQuery
				JHtml::_( 'jquery.framework' );
			}

			$params						=	new JRegistry( $jUser->params );

			$data						=	new stdClass();
			$data->id					=	$userId;
			$data->params				=	$params->toArray();

			$fields						=	array();

			if ( Application::Cms()->getClientId() ) {
				// Backend user edit settings tab:
				// Joomla 4:
				$path					=	JPATH_ADMINISTRATOR . '/components/com_users/forms';
				if ( ! file_exists( $path ) ) {
					// Joomla 3:
					$path				=	JPATH_ADMINISTRATOR . '/components/com_users/models/forms';
				}
				JForm::addFormPath( $path );

				JPluginHelper::importPlugin( 'user' );

				$form					=	JForm::getInstance( 'com_users.params', 'user', array( 'load_data' => true ) );

				$form->bind( $data );

				$settings				=	$form->getFieldset( 'settings' );

				if ( count( $settings ) ) {
					$fields				=	$settings;
				}
			} else {
				// Frontend profile edit params:
				// Joomla 4:
				$path					=	JPATH_ROOT . '/components/com_users/forms';
				if ( ! file_exists( $path ) ) {
					// Joomla 3:
					$path				=	JPATH_ROOT . '/components/com_users/models/forms';
				}
				JForm::addFormPath( $path );

				JPluginHelper::importPlugin( 'user' );

				if ( ! $userId ) {
					$context			=	'com_users.registration';
				} else {
					$context			=	'com_users.profile';
				}

				$form					=	JForm::getInstance( $context, 'frontend' );

				if ( Application::MyUser()->isAuthorizedToPerformActionOnAsset( 'core.login.admin', 'root' ) ) {
					$form->loadFile( 'frontend_admin', false );
				}

				JFactory::getApplication()->triggerEvent( 'onContentPrepareForm', array( $form, $data ) );

				JFactory::getApplication()->triggerEvent( 'onContentPrepareData', array( $context, $data ) );

				$form->bind( $data );

				foreach ( $form->getFieldsets() as $group => $fieldset ) {
					// For now we need to strictly only allow certain groups as this API also gives custom Joomla fields:
					if ( ! in_array( $group, array( 'params', 'privacyconsent', 'terms' ) ) ) {
						continue;
					}

					$fieldsetFields		=	$form->getFieldset( $group );

					if ( ! count( $fieldsetFields ) ) {
						continue;
					}

					$fields				+= $fieldsetFields;
				}
			}

			$result						=	array();

			/** @var JFormField $field */
			foreach ( $fields as $fieldId => $field ) {
				ob_start();
				echo $field->label;
				$fieldLabel				=	ob_get_contents();
				ob_end_clean();

				ob_start();
				echo $field->input;
				$fieldInput				=	ob_get_contents();
				ob_end_clean();

				$cmsField				=	new stdClass();
				$cmsField->id			=	$fieldId;
				$cmsField->name			=	$field->name;
				$cmsField->title		=	$field->title;
				$cmsField->description	=	$field->description;
				$cmsField->label		=	$fieldLabel;
				$cmsField->input		=	$fieldInput;
				$cmsField->required		=	$field->required;

				$result[$fieldId]		=	$cmsField;
			}

			$cache[$userId][$clientId]	=	$result;
		}

		return $cache[$userId][$clientId];
	}
}

class CBfield_file extends cbFieldHandler {

	/**
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		$value					=	$user->get( $field->name );

		switch ( $output ) {
			case 'html':
			case 'rss':
				$return			=	$this->formatFieldValueLayout( $this->_fileLivePath( $field, $user, $reason ), $reason, $field, $user );
				break;
			case 'htmledit':
				if ( $reason == 'search' ) {
					$choices	=	array();
					$choices[]	=	moscomprofilerHTML::makeOption( '', CBTxt::T( 'UE_NO_PREFERENCE', 'No preference' ) );
					$choices[]	=	moscomprofilerHTML::makeOption( '1', CBTxt::T( 'Has a file' ) );
					$choices[]	=	moscomprofilerHTML::makeOption( '0', CBTxt::T( 'Has no file' ) );
					$html		=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', 'select', $value, '', $choices, true, null, false );
					$return		=	$this->_fieldSearchModeHtml( $field, $user, $html, 'singlechoice', $list_compare_types );
				} else {
					$return		=	$this->formatFieldValueLayout( $this->_htmlEditForm( $field, $user, $reason ), $reason, $field, $user );
				}
				break;
			default:
				$fileUrl		=	$this->_fileLivePath( $field, $user, $reason, false );
				$return			=	$this->_formatFieldOutput( $field->name, $fileUrl, $output, false );
				break;
		}

		return $return;
	}

	/**
	 * Direct access to field for custom operations, like for Ajax
	 *
	 * WARNING: direct unchecked access, except if $user is set, then check
	 * that the logged-in user has rights to edit that $user.
	 *
	 * @param FieldTable     $field
	 * @param null|UserTable $user
	 * @param array          $postdata
	 * @param string         $reason 'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'search' for searches (always public!)
	 * @return string                  Expected output.
	 */
	public function fieldClass( &$field, &$user, &$postdata, $reason ) {
		global $_CB_framework;

		if ( ( ! $user ) || ( ! in_array( $reason, array( 'profile', 'edit', 'list' ) ) ) || ( cbGetParam( $_GET, 'function', '' ) != 'download' ) || ( ! $user->id ) ) {
			return null; // wrong reason, wrong function, or user doesn't exist; do nothing
		}

		$col					=	$field->name;
		$file					=	$user->$col;

		if ( ! $file ) {
			return null; // nothing to download; do nothing
		}

		if ( $reason == 'edit' ) {
			$redirect_url		=	$_CB_framework->userProfileEditUrl( $user->id, false );
		} elseif ( $reason == 'list' ) {
			$redirect_url		=	$_CB_framework->userProfilesListUrl( cbGetParam( $_REQUEST, 'listid', 0 ), false );
		} else {
			$redirect_url		=	$_CB_framework->userProfileUrl( $user->id, false );
		}

		$clean_file				=	preg_replace( '/[^-a-zA-Z0-9_.]/u', '', $file );
		$file_path				=	$_CB_framework->getCfg( 'absolute_path' ) . '/images/comprofiler/plug_cbfilefield/' . (int) $user->id . '/' . $clean_file;

		if ( ! file_exists( $file_path ) ) {
			cbRedirect( $redirect_url, CBTxt::T( 'File failed to download! Error: File not found' ), 'error' );
			exit();
		}

		$file_ext				=	strtolower( pathinfo( $clean_file, PATHINFO_EXTENSION ) );

		if ( ! $file_ext ) {
			cbRedirect( $redirect_url, CBTxt::T( 'File failed to download! Error: Unknown extension' ), 'error' );
			exit();
		}

		$file_name				=	substr( rtrim( pathinfo( $clean_file, PATHINFO_BASENAME ), '.' . $file_ext ), 0, -14 );
		$file_name_custom		=	$field->params->get( 'fieldFile_filename' );

		if ( $file_name_custom ) {
			$file_name			=	cbReplaceVars( $file_name_custom, $user, true, false, array( 'filename' => $file_name, 'reason' => $reason ) );
		}

		$file_name				=	$file_name . '.' . $file_ext;

		if ( ! $file_name ) {
			cbRedirect( $redirect_url, CBTxt::T( 'File failed to download! Error: File not found' ), 'error' );
			exit();
		}

		$file_mime				=	cbGetMimeFromExt( $file_ext );

		if ( $file_mime == 'application/octet-stream' ) {
			cbRedirect( $redirect_url, CBTxt::T( 'File failed to download! Error: Unknown MIME' ), 'error' );
			exit();
		}

		$file_size				=	@filesize( $file_path );
		$file_modified			=	$_CB_framework->getUTCDate( 'r', filemtime( $file_path ) );

		while ( @ob_end_clean() );

		if ( ini_get( 'zlib.output_compression' ) ) {
			ini_set( 'zlib.output_compression', 'Off' );
		}

		if ( function_exists( 'apache_setenv' ) ) {
			apache_setenv( 'no-gzip', '1' );
		}

		header( "Content-Type: $file_mime" );
		header( 'Content-Disposition: ' . ( $field->params->get( 'fieldFile_force', 0 ) ? 'attachment' : 'inline' ) . '; filename="' . $file_name . '"; modification-date="' . $file_modified . '"; size=' . $file_size .';' );
		header( "Content-Transfer-Encoding: binary" );
		header( "Expires: 0" );
		header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header( "Pragma: public" );
		header( "Content-Length: $file_size" );

		if ( ! ini_get( 'safe_mode' ) ) {
			@set_time_limit( 0 );
		}

		$handle					=	fopen( $file_path, 'rb' );

		if ( $handle === false ) {
			exit();
		}

		$chunksize				=	( 1 * ( 1024 * 1024 ) );

		while ( ! feof( $handle ) ) {
			$buffer				=	fread( $handle, $chunksize );
			echo $buffer;
			@ob_flush();
			flush();
		}

		fclose( $handle );
		exit();
	}

	/**
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 */
	public function prepareFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		global $_CB_framework, $_CB_database;

		$this->_prepareFieldMetaSave( $field, $user, $postdata, $reason );

		$col					=	$field->name;
		$col_choice				=	$col . '__choice';
		$col_file				=	$col . '__file';
		$choice					=	stripslashes( cbGetParam( $postdata, $col_choice ) );

		switch ( $choice ) {
			case 'upload':
				$value			=	( isset( $_FILES[$col_file] ) ? $_FILES[$col_file] : null );

				$this->validate( $field, $user, $choice, $value, $postdata, $reason );
				break;
			case 'delete':
				if ( $user->id && ( $user->$col != null ) && ( $user->$col != '' ) ) {
					if ( isset( $user->$col ) ) {
						$this->_logFieldUpdate( $field, $user, $reason, $user->$col, '' );
					}

					$this->deleteFiles( $user, $user->$col );

					$user->$col	=	null;

					// This is needed because user store does not save null:
					if ( $field->table ) {
						$query	=	'UPDATE ' . $_CB_database->NameQuote( $field->table )
								.	"\n SET " . $_CB_database->NameQuote( $col ) . " = NULL"
								.	', ' . $_CB_database->NameQuote( 'lastupdatedate' ) . ' = ' . $_CB_database->Quote( $_CB_framework->dateDbOfNow() )
								.	"\n WHERE " . $_CB_database->NameQuote( 'id' ) . " = " . (int) $user->id;
						$_CB_database->setQuery( $query );
						$_CB_database->query();
					}
				}
				break;
			default:
				$value			=	$user->get( $col );

				$this->validate( $field, $user, $choice, $value, $postdata, $reason );
				break;
		}
	}

	/**
	 * Mutator:
	 * Prepares field data commit
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save user edit, 'register' for save registration
	 */
	public function commitFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		global $_CB_framework, $_PLUGINS, $_FILES;

		$col						=	$field->name;
		$col_choice					=	$col . '__choice';
		$col_file					=	$col . '__file';
		$choice						=	stripslashes( cbGetParam( $postdata, $col_choice ) );

		switch ( $choice ) {
			case 'upload':
				$value				=	( isset( $_FILES[$col_file] ) ? $_FILES[$col_file] : null );

				if ( $this->validate( $field, $user, $choice, $value, $postdata, $reason ) ) {
					$_PLUGINS->loadPluginGroup( 'user' );

					$_PLUGINS->trigger( 'onBeforeUserFileUpdate', array( &$user, &$value['tmp_name'] ) );

					if ( $_PLUGINS->is_errors() ) {
						$this->_setErrorMSG( $_PLUGINS->getErrorMSG() );
						return;
					}

					$path			=	$_CB_framework->getCfg( 'absolute_path' );
					$index_path		=	$path . '/components/com_comprofiler/plugin/user/plug_cbfilefield/index.html';
					$files_path		=	$path . '/images/comprofiler/plug_cbfilefield';
					$file_path		=	$files_path . '/' . (int) $user->id;

					if ( ! is_dir( $files_path ) ) {
						$oldmask	=	@umask( 0 );

						if ( @mkdir( $files_path, 0755, true ) ) {
							@umask( $oldmask );
							@chmod( $files_path, 0755 );

							if ( ! file_exists( $files_path . '/index.html' ) ) {
								@copy( $index_path, $files_path . '/index.html' );
								@chmod( $files_path . '/index.html', 0755 );
							}
						} else {
							@umask( $oldmask );
						}
					}

					if ( ! file_exists( $files_path . '/.htaccess' ) ) {
						file_put_contents( $files_path . '/.htaccess', 'deny from all' );
					}

					if ( ! is_dir( $file_path ) ) {
						$oldmask	=	@umask( 0 );

						if ( @mkdir( $file_path, 0755, true ) ) {
							@umask( $oldmask );
							@chmod( $file_path, 0755 );

							if ( ! file_exists( $file_path . '/index.html' ) ) {
								@copy( $index_path, $file_path . '/index.html' );
								@chmod( $file_path . '/index.html', 0755 );
							}
						} else {
							@umask( $oldmask );
						}
					}

					$uploaded_name	=	preg_replace( '/[^-a-zA-Z0-9_]/u', '', pathinfo( $value['name'], PATHINFO_FILENAME ) );
					$uploaded_ext	=	strtolower( preg_replace( '/[^-a-zA-Z0-9_]/u', '', pathinfo( $value['name'], PATHINFO_EXTENSION ) ) );
					$newFileName	=	uniqid( $uploaded_name . '_' ). '.' . $uploaded_ext;

					if ( ! move_uploaded_file( $value['tmp_name'], $file_path . '/'. $newFileName ) ) {
						$this->_setValidationError( $field, $user, $reason, sprintf( CBTxt::T( 'CBFile-failed to upload file: %s' ), $newFileName ) );
						return;
					} else {
						@chmod( $file_path . '/' . $value['tmp_name'], 0755 );
					}

					if ( isset( $user->$col ) ) {
						$this->_logFieldUpdate( $field, $user, $reason, $user->$col, '' );
					}

					if ( isset( $user->$col ) && ( $user->$col != '' ) ) {
						$this->deleteFiles( $user, $user->$col );
					}

					$user->$col		=	$newFileName;

					$_PLUGINS->trigger( 'onAfterUserFileUpdate', array( &$user, $newFileName ) );
				}
				break;
		}
	}

	/**
	 * Mutator:
	 * Prepares field data rollback
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save user edit, 'register' for save registration
	 */
	public function rollbackFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		global $_FILES;

		$col			=	$field->name;
		$col_choice		=	$col . '__choice';
		$col_file		=	$col . '__file';

		$choice			=	stripslashes( cbGetParam( $postdata, $col_choice ) );

		switch ( $choice ) {
			case 'upload':
				$value	=	( isset( $_FILES[$col_file] ) ? $_FILES[$col_file] : null );

				if ( $this->validate( $field, $user, $choice, $value, $postdata, $reason ) ) {
					$this->deleteFiles( $user, $user->$col );
				}
				break;
		}
	}

	/**
	 * outputs a secure list of allowed file extensions
	 *
	 * @param  string  $extensions
	 * @return array
	 */
	function allowedExtensions( $extensions = 'zip,rar,doc,pdf,txt,xls' ) {
		$allowed			=	explode( ',', $extensions );

		if ( $allowed ) {
			$not_allowed	=	array( 'php', 'php3', 'php4', 'php5', 'asp', 'exe', 'py' );

			foreach ( $not_allowed as $extension ) {
				$key		=	array_search( $extension, $allowed );

				if ( $key ) {
					unset( $allowed[$key] );
				}
			}
		}

		return $allowed;
	}

	/**
	 * Validator:
	 * Validates $value for $field->required and other rules
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user        RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  string      $columnName  Column to validate
	 * @param  string      $value       (RETURNED:) Value to validate, Returned Modified if needed !
	 * @param  array       $postdata    Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason      'edit' for save user edit, 'register' for save registration
	 * @return boolean                            True if validate, $this->_setErrorMSG if False
	 */
	public function validate( &$field, &$user, $columnName, &$value, &$postdata, $reason ) {
		$isRequired							=	$this->_isRequired( $field, $user, $reason );

		switch ( $columnName ) {
			case 'upload':
				if ( ! isset( $value['tmp_name'] ) || empty( $value['tmp_name'] ) || ( $value['error'] != 0 ) || ( ! is_uploaded_file( $value['tmp_name'] ) ) ) {
					if ( $isRequired ) {
						$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'Please select a file before uploading' ) );
					}

					return false;
				} else {
					$upload_size_limit_max	=	(int) $field->params->get( 'fieldValidateFile_sizeMax', 1024 );
					$upload_size_limit_min	=	(int) $field->params->get( 'fieldValidateFile_sizeMin', 0 );
					$upload_ext_limit		=	$this->allowedExtensions( $field->params->get( 'fieldValidateFile_types', 'zip,rar,doc,pdf,txt,xls' ) );

					$uploaded_name_empty	=	( '' === pathinfo( $value['name'], PATHINFO_FILENAME ) );

					if ( $uploaded_name_empty ) {
						$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'Please select a file before uploading' ) );
						return false;
					}

					$uploaded_ext			=	strtolower( preg_replace( '/[^-a-zA-Z0-9_]/u', '', pathinfo( $value['name'], PATHINFO_EXTENSION ) ) );

					if ( ( ! $uploaded_ext ) || ( ! in_array( $uploaded_ext, $upload_ext_limit ) ) ) {
						$this->_setValidationError( $field, $user, $reason, sprintf( CBTxt::T( 'Please upload only %s' ), implode( ', ', $upload_ext_limit ) ) );
						return false;
					}

					$uploaded_size			=	$value['size'];

					if ( ( $uploaded_size / 1024 ) > $upload_size_limit_max ) {
						$this->_setValidationError( $field, $user, $reason, sprintf( CBTxt::T( 'The file size exceeds the maximum of %s' ), $this->formattedFileSize( $upload_size_limit_max * 1024 ) ) );
						return false;
					}

					if ( ( $uploaded_size / 1024 ) < $upload_size_limit_min ) {
						$this->_setValidationError( $field, $user, $reason, sprintf( CBTxt::T( 'The file is too small, the minimum is %s' ), $this->formattedFileSize( $upload_size_limit_min * 1024 ) ) );
						return false;
					}
				}
				break;
			default:
				$valCol						=	$field->name;

				if ( $isRequired && ( ( ! $user ) || ( ! isset( $user->$valCol ) ) || ( ! $user->$valCol ) ) ) {
					if ( ! $value ) {
						$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'UE_FIELDREQUIRED', 'This Field is required' ) );
						return false;
					}
				}
				break;
		}

		return true;
	}

	/**	 * Finder:
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $searchVals  RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata    Typically $_POST (but not necessarily), filtering required.
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @param  string      $reason      'edit' for save profile edit, 'register' for registration, 'search' for searches
	 * @return cbSqlQueryPart[]
	 */
	function bindSearchCriteria( &$field, &$searchVals, &$postdata, $list_compare_types, $reason ) {
		$query					=	array();
		$searchMode				=	$this->_bindSearchMode( $field, $searchVals, $postdata, 'none', $list_compare_types );
		$col					=	$field->name;
		$value					=	cbGetParam( $postdata, $col );

		if ( $value === '0' ) {
			$value				=	0;
		} elseif ( $value == '1' ) {
			$value				=	1;
		} else {
			$value				=	null;
		}

		if ( $value !== null ) {
			$searchVals->$col	=	$value;

			// When is not advanced search is used we need to invert our search:
			if ( $searchMode == 'isnot' ) {
				if ( $value === 0 ) {
					$value		=	1;
				} elseif ( $value == 1 ) {
					$value		=	0;
				}
			}

			$sql				=	new cbSqlQueryPart();
			$sql->tag			=	'column';
			$sql->name			=	$col;
			$sql->table			=	$field->table;
			$sql->type			=	'sql:field';
			$sql->operator		=	$value ? 'IS NOT' : 'IS';
			$sql->value			=	'NULL';
			$sql->valuetype		=	'const:null';
			$sql->searchmode	=	$searchMode;

			$query[]			=	$sql;
		}

		return $query;
	}

	/**
	 * Returns full URL of the file
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $reason
	 * @param  bool        $html
	 * @return null|string
	 */
	function _fileLivePath( &$field, &$user, $reason, $html = true ) {
		global $_CB_framework;

		$oValue					=	null;

		if ( $user && $user->id ) {
			$fieldName			=	$field->get( 'name' );
			$value				=	$user->get( $fieldName );
			$fileName			=	null;

			if ( $value != null ) {
				$cleanFile		=	preg_replace( '/[^-a-zA-Z0-9_.]/u', '', $value );
				$fileExt		=	strtolower( pathinfo( $cleanFile, PATHINFO_EXTENSION ) );
				$fileName		=	substr( rtrim( pathinfo( $cleanFile, PATHINFO_BASENAME ), '.' . $fileExt ), 0, -14 );
				$fileNameCustom	=	$field->params->get( 'fieldFile_filename' );

				if ( $fileNameCustom ) {
					$fileName	=	cbReplaceVars( $fileNameCustom, $user, true, false, array( 'filename' => $fileName, 'reason' => $reason ) );
				}

				$fileName		=	$fileName . '.' . $fileExt;
				$oValue			=	'/images/comprofiler/plug_cbfilefield/' . (int) $user->id . '/' . $cleanFile;
			}

			if ( $oValue ) {
				$oValue			=	'index.php?option=com_comprofiler&view=fieldclass&field=' . urlencode( $fieldName ) . '&function=download&user=' . (int) $user->id . '&reason=' . urlencode( $reason );

				if ( $_CB_framework->getUi() == 2 ) {
					$oValue		=	$_CB_framework->backendUrl( $oValue, true );
				} else {
					$oValue		=	cbSef( $oValue, true );
				}

				if ( $html ) {
					$oValue		=	' <a href="' . $oValue . '" title="' . htmlspecialchars( CBTxt::T( 'Click or right-click filename to download' ) ) . '" target="_blank" rel="nofollow noopener noreferrer">' . $fileName . '</a>';
				}
			}
		}

		return $oValue;
	}

	/**
	 *
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $reason             'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  boolean     $displayFieldIcons
	 * @return string                          HTML: <tag type="$type" value="$value" xxxx="xxx" yy="y" />
	 */
	function _htmlEditForm( &$field, &$user, $reason, $displayFieldIcons = true ) {
		global $_CB_framework;

		$fieldName							=	$field->get( 'name' );
		$value								=	$user->get( $fieldName );
		$required							=	$this->_isRequired( $field, $user, $reason );

		$uploadSizeLimitMax					=	$field->params->get( 'fieldValidateFile_sizeMax', 1024 );
		$uploadSizeLimitMin					=	$field->params->get( 'fieldValidateFile_sizeMin', 0 );
		$uploadExtLimit						=	$this->allowedExtensions( $field->params->get( 'fieldValidateFile_types', 'zip,rar,doc,pdf,txt,xls' ) );
		$restrictions						=	array();

		if ( $uploadExtLimit ) {
			$restrictions[]					=	CBTxt::Th( 'FILE_UPLOAD_LIMITS_EXT', 'Your file must be of [ext] type.', array( '[ext]' => implode( ', ', $uploadExtLimit ) ) );
		}

		if ( $uploadSizeLimitMin ) {
			$restrictions[]					=	CBTxt::Th( 'FILE_UPLOAD_LIMITS_MIN', 'Your file should exceed [size].', array( '[size]' => $this->formattedFileSize( $uploadSizeLimitMin * 1024 ) ) );
		}

		if ( $uploadSizeLimitMax ) {
			$restrictions[]					=	CBTxt::Th( 'FILE_UPLOAD_LIMITS_MAX', 'Your file should not exceed [size].', array( '[size]' => $this->formattedFileSize( $uploadSizeLimitMax * 1024 ) ) );
		}

		$existingFile						=	( $user->id ? ( ( $value != null ) ? true : false ) : false );
		$choices							=	array();

		if ( ( $reason == 'register' ) || ( ( $reason == 'edit' ) && ( $user->id == 0 ) ) ) {
			if ( $required == 0 ) {
				$choices[]					=	moscomprofilerHTML::makeOption( '', CBTxt::T( 'No file' ) );
			}
		} else {
			if ( $existingFile || ( $required == 0 ) ) {
				$choices[]					=	moscomprofilerHTML::makeOption( '', CBTxt::T( 'No change of file' ) );
			}
		}

		$choices[]							=	moscomprofilerHTML::makeOption( 'upload', ( $existingFile ? CBTxt::T( 'Upload new file' ) : CBTxt::T( 'Upload file' ) ) );

		if ( $existingFile && ( $required == 0 ) ) {
			$choices[]						=	moscomprofilerHTML::makeOption( 'delete', CBTxt::T( 'Remove file' ) );
		}

		$return								=	null;

		if ( ( $reason != 'register' ) && ( $user->id != 0 ) && $existingFile ) {
			$return							.=	'<div class="row no-gutters mb-3 cbFileFieldDownload">' . $this->_fileLivePath( $field, $user, $reason ) . '</div>';
		}

		$hasChoices							=	( count( $choices ) > 1 );

		if ( $hasChoices ) {
			static $functOut				=	false;

			$additional						=	' class="form-control cbFileFieldChoice"';

			if ( ( $_CB_framework->getUi() == 1 ) && ( $reason == 'edit' ) && $field->readonly ) {
				$additional					.=	' disabled="disabled"';
			}

			$translatedTitle				=	$this->getFieldTitle( $field, $user, 'html', $reason );
			$htmlDescription				=	$this->getFieldDescription( $field, $user, 'htmledit', $reason );
			$trimmedDescription				=	trim( strip_tags( $htmlDescription ) );
			$inputDescription				=	$field->params->get( 'fieldLayoutInputDesc', 1, GetterInterface::INT );

			$tooltip						=	( $trimmedDescription && $inputDescription ? cbTooltip( $_CB_framework->getUi(), $htmlDescription, $translatedTitle, null, null, null, null, $additional ) : $additional );

			$return							.=	'<div class="form-group mb-0 cb_form_line">'
											.		moscomprofilerHTML::selectList( $choices, $fieldName . '__choice', $tooltip, 'value', 'text', null, $required, true, null, false )
											.		$this->_fieldIconsHtml( $field, $user, 'htmledit', $reason, 'select', '', null, '', array(), $displayFieldIcons, $required )
											.	'</div>';

			if ( ! $functOut ) {
				$js							=	"$.fn.cbslideFile = function() {"
											.		"var element = $( this );"
											.		"element.on( 'click.cbfilefield change.cbfilefield', function() {"
											.			"if ( ( $( this ).val() == '' ) || ( $( this ).val() == 'delete' ) ) {"
											.				"element.parent().siblings( '.cbFileFieldUpload' ).addClass( 'hidden' ).find( 'input' ).prop( 'disabled', true );"
											.			"} else if ( $( this ).val() == 'upload' ) {"
											.				"element.parent().siblings( '.cbFileFieldUpload' ).removeClass( 'hidden' ).find( 'input' ).prop( 'disabled', false );"
											.			"}"
											.		"}).on( 'cloned.cbfilefield', function() {"
											.			"$( this ).parent().siblings( '.cbFileFieldDownload' ).remove();"
											.			"if ( $( this ).parent().siblings( '.cbFileFieldUpload' ).find( 'input.required' ).length ) {"
											.				"$( this ).find( 'option[value=\"\"]' ).remove();"
											.			"}"
											.			"$( this ).find( 'option[value=\"delete\"]' ).remove();"
											.			"$( this ).off( '.cbfilefield' );"
											.			"$( this ).cbslideFile();"
											.		"}).change();"
											.		"return this;"
											.	"};";

				$_CB_framework->outputCbJQuery( $js );

				$functOut					=	true;
			}

			$_CB_framework->outputCbJQuery( "$( '#" . addslashes( $fieldName ) . "__choice' ).cbslideFile();" );
		} else {
			$return							.=	'<input type="hidden" name="' . htmlspecialchars( $fieldName ) . '__choice" value="' . htmlspecialchars( $choices[0]->value ) . '" />';
		}

		$validationAttributes				=	array();
		$validationAttributes[]				=	cbValidator::getRuleHtmlAttributes( 'extension', implode( ',', $uploadExtLimit ) );

		if ( $uploadSizeLimitMin || $uploadSizeLimitMax ) {
			$validationAttributes[]			=	cbValidator::getRuleHtmlAttributes( 'filesize', array( $uploadSizeLimitMin, $uploadSizeLimitMax, 'KB' ) );
		}

		$return								.=	'<div id="cbfile_upload_' . htmlspecialchars( $fieldName ) . '" class="form-group mb-0 cb_form_line' . ( $hasChoices ? ' mt-3 hidden' : null ) . ' cbFileFieldUpload">'
											.		( $restrictions ? '<div class="mb-2">' . implode( ' ', $restrictions ) . '</div>' : null )
											.		'<div>'
											.			CBTxt::T( 'Select file' ) . ' <input type="file" name="' . htmlspecialchars( $fieldName ) . '__file" value="" class="form-control' . ( $required == 1 ? ' required' : null ) . '"' . implode( ' ', $validationAttributes ) . ( $hasChoices ? ' disabled="disabled"' : null ) . ' />'
											.			( count( $choices ) <= 0 ? $this->_fieldIconsHtml( $field, $user, 'htmledit', $reason, 'select', '', null, '', array(), $displayFieldIcons, $required ) : null )
											.		'</div>'
											.		'<div class="mt-2">';

		if ( $field->params->get( 'fieldFile_terms', 0 ) ) {
			$cbUser							=	CBuser::getMyInstance();
			$termsOutput					=	$field->params->get( 'terms_output', 'url' );
			$termsType						=	$cbUser->replaceUserVars( $field->params->get( 'terms_type', 'TERMS_AND_CONDITIONS' ) );
			$termsDisplay					=	$field->params->get( 'terms_display', 'modal' );
			$termsURL						=	cbSef( $cbUser->replaceUserVars( $field->params->get( 'terms_url', null ) ), false );
			$termsText						=	$cbUser->replaceUserVars( $field->params->get( 'terms_text', null ) );
			$termsWidth						=	$field->params->get( 'terms_width', 400 );
			$termsHeight					=	$field->params->get( 'terms_height', 200 );

			if ( ! $termsType ) {
				$termsType					=	CBTxt::T( 'TERMS_AND_CONDITIONS', 'Terms and Conditions' );
			}

			if ( ! $termsWidth ) {
				$termsWidth					=	400;
			}

			if ( ! $termsHeight ) {
				$termsHeight				=	200;
			}

			if ( ( ( $termsOutput == 'url' ) && $termsURL ) || ( ( $termsOutput == 'text' ) && $termsText ) ) {
				if ( $termsDisplay == 'iframe' ) {
					if ( is_numeric( $termsHeight ) ) {
						$termsHeight		.=	'px';
					}

					if ( is_numeric( $termsWidth ) ) {
						$termsWidth			.=	'px';
					}

					if ( $termsOutput == 'url' ) {
						$return				.=	'<div class="embed-responsive mb-2 cbTermsFrameContainer" style="padding-bottom: ' . htmlspecialchars( $termsHeight ) . ';">'
											.		'<iframe class="embed-responsive-item d-block border rounded cbTermsFrameURL" style="width: ' . htmlspecialchars( $termsWidth ) . ';" src="' . htmlspecialchars( $termsURL ) . '"></iframe>'
											.	'</div>';
					} else {
						$return				.=	'<div class="bg-light border rounded p-2 mb-2 cbTermsFrameText" style="height:' . htmlspecialchars( $termsHeight ) . ';width:' . htmlspecialchars( $termsWidth ) . ';overflow:auto;">' . $termsText . '</div>';
					}

					$return					.=			CBTxt::Th( 'BY_UPLOADING_YOU_CERTIFY_THAT_YOU_HAVE_THE_RIGHT_TO_DISTRIBUTE_THIS_FILE_TERMS', 'By uploading, you certify that you have the right to distribute this file and that it does not violate the above [type].', array( '[type]' => $termsType ) );
				} else {
					$attributes				=	' class="cbTermsLink"';

					if ( ( $termsOutput == 'text' ) && ( $termsDisplay == 'window' ) ) {
						$termsDisplay		=	'modal';
					}

					if ( $termsDisplay == 'modal' ) {
						// Tooltip height percentage would be based off window height (including scrolling); lets change it to be based off the viewport height:
						$termsHeight		=	( substr( $termsHeight, -1 ) == '%' ? (int) substr( $termsHeight, 0, -1 ) . 'vh' : $termsHeight );

						if ( $termsOutput == 'url' ) {
							$tooltip		=	'<iframe class="d-block m-0 p-0 border-0 cbTermsModalURL" height="100%" width="100%" src="' . htmlspecialchars( $termsURL ) . '"></iframe>';
						} else {
							$tooltip		=	'<div class="cbTermsModalText" style="height:100%;width:100%;overflow:auto;">' . $termsText . '</div>';
						}

						$url				=	'javascript:void(0);';
						$attributes			.=	' ' . cbTooltip( $_CB_framework->getUi(), $tooltip, $termsType, array( $termsWidth, $termsHeight ), null, null, null, 'data-hascbtooltip="true" data-cbtooltip-modal="true"' );
					} else {
						$url				=	htmlspecialchars( $termsURL );
						$attributes			.=	' target="_blank"';
					}

					$return					.=			CBTxt::Th( 'BY_UPLOADING_YOU_CERTIFY_THAT_YOU_HAVE_THE_RIGHT_TO_DISTRIBUTE_THIS_FILE_URL_TERMS', 'By uploading, you certify that you have the right to distribute this file and that it does not violate the <a href="[url]"[attributes]>[type]</a>', array( '[url]' => $url, '[attributes]' => $attributes, '[type]' => $termsType ) );
				}
			} else {
				$return						.=			CBTxt::Th( 'BY_UPLOADING_YOU_CERTIFY_THAT_YOU_HAVE_THE_RIGHT_TO_DISTRIBUTE_THIS_FILE', 'By uploading, you certify that you have the right to distribute this file.' );
			}
		} else {
			$return							.=			CBTxt::Th( 'BY_UPLOADING_YOU_CERTIFY_THAT_YOU_HAVE_THE_RIGHT_TO_DISTRIBUTE_THIS_FILE', 'By uploading, you certify that you have the right to distribute this file.' );
		}

		$return								.=		'</div>'
											.	'</div>';

		return $return;
	}

	/**
	 * Deletes file from users folder
	 *
	 * @param  UserTable  $user
	 * @param  string     $file
	 */
	function deleteFiles( $user, $file = null ) {
		global $_CB_framework;

		if ( ! is_object( $user ) ) {
			return;
		}

		$file_path	=	$_CB_framework->getCfg( 'absolute_path' ) . '/images/comprofiler/plug_cbfilefield/' . (int) $user->id . '/';

		if ( ! is_dir( $file_path ) ) {
			return;
		}

		if ( ! $file ) {
			if ( false !== ( $handle = opendir( $file_path ) ) ) {
				while ( false !== ( $file = readdir( $handle ) ) ) {
					if ( $file && ( ( $file != '.' ) && ( $file != '..' ) ) ) {
						@unlink( $file_path . $file );
					}
				}
				closedir( $handle );
			}

			if ( is_dir( $file_path ) ) {
				@rmdir( $file_path );
			}
		} else {
			if ( file_exists( $file_path . $file ) ) {
				@unlink( $file_path . $file );
			}
		}
	}

	/**
	 * Returns file size formatted from bytes
	 *
	 * @param int $bytes
	 * @return string
	 */
	private function formattedFileSize( $bytes )
	{
		if ( $bytes >= 1099511627776 ) {
			$size	=	CBTxt::Th( 'FILESIZE_FORMATTED_TB', '%%COUNT%% TB|%%COUNT%% TBs', array( '%%COUNT%%' => (float) number_format( $bytes / 1099511627776, 2, '.', '' ) ) );
		} elseif ( $bytes >= 1073741824 ) {
			$size	=	CBTxt::Th( 'FILESIZE_FORMATTED_GB', '%%COUNT%% GB|%%COUNT%% GBs', array( '%%COUNT%%' => (float) number_format( $bytes / 1073741824, 2, '.', '' ) ) );
		} elseif ( $bytes >= 1048576 ) {
			$size	=	CBTxt::Th( 'FILESIZE_FORMATTED_MB', '%%COUNT%% MB|%%COUNT%% MBs', array( '%%COUNT%%' => (float) number_format( $bytes / 1048576, 2, '.', '' ) ) );
		} elseif ( $bytes >= 1024 ) {
			$size	=	CBTxt::Th( 'FILESIZE_FORMATTED_KB', '%%COUNT%% KB|%%COUNT%% KBs', array( '%%COUNT%%' => (float) number_format( $bytes / 1024, 2, '.', '' ) ) );
		} else {
			$size	=	CBTxt::Th( 'FILESIZE_FORMATTED_B', '%%COUNT%% B|%%COUNT%% Bs', array( '%%COUNT%%' => (float) number_format( $bytes, 2, '.', '' ) ) );
		}

		return $size;
	}
}

class CBfield_video extends CBfield_text {

	/**
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		$return						=	null;

		$value						=	$user->get( $field->get( 'name' ) );

		switch ( $output ) {
			case 'html':
			case 'rss':
				if ( $value ) {
					$return			=	$this->getEmbed( $field, $user, $value, $reason );
				}

				$return				=	$this->formatFieldValueLayout( $return, $reason, $field, $user );
				break;
			/** @noinspection PhpMissingBreakStatementInspection */
			case 'htmledit':
					if ( $reason == 'search' ) {
						$choices	=	array();
						$choices[]	=	moscomprofilerHTML::makeOption( '', CBTxt::T( 'UE_NO_PREFERENCE', 'No preference' ) );
						$choices[]	=	moscomprofilerHTML::makeOption( '1', ( $field->params->get( 'video_allow_links', 1 ) ? CBTxt::T( 'Has video file or link' ) : CBTxt::T( 'Has a video file' ) ) );
						$choices[]	=	moscomprofilerHTML::makeOption( '0', ( $field->params->get( 'video_allow_links', 1 ) ? CBTxt::T( 'Has no video file or link' ) : CBTxt::T( 'Has no video file' ) ) );

						$html		=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', 'select', $value, '', $choices, true, null, false );

						$return		=	$this->_fieldSearchModeHtml( $field, $user, $html, 'singlechoice', $list_compare_types );
					} else {
						$return		=	$this->formatFieldValueLayout( $this->_htmlEditForm( $field, $user, $output, $reason ), $reason, $field, $user );
					}
					break;
			default:
				$field->set( 'type', 'text' );

				$return				=	parent::getField( $field, $user, $output, $reason, $list_compare_types );
				break;
		}

		return $return;
	}

	/**
	 * returns video embed based off video url
	 *
	 * @param  FieldTable   $field
	 * @param  UserTable    $user
	 * @param  string       $value
	 * @param  string       $reason
	 * @return null|string
	 */
	public function getEmbed( $field, $user, $value, $reason ) {
		global $_CB_framework;

		$domain						=	preg_replace( '/^(?:(?:\w+\.)*)?(\w+)\..+$/', '\1', parse_url( $value, PHP_URL_HOST ) );

		if ( ! $domain ) {
			$value					=	$_CB_framework->getCfg( 'live_site' ) . '/images/comprofiler/video/' . (int) $user->get( 'id' ) . '/' . urlencode( $value );
		}

		$embed						=	null;

		if ( $value ) {
			$currentScheme			=	( ( isset( $_SERVER['HTTPS'] ) && ( ! empty( $_SERVER['HTTPS'] ) ) && ( $_SERVER['HTTPS'] != 'off' ) ) ? 'https' : 'http' );
			$urlScheme				=	parse_url( $value, PHP_URL_SCHEME );

			if ( ! $urlScheme ) {
				$urlScheme			=	$currentScheme;
			}

			if ( ( $currentScheme == 'https' ) && ( $urlScheme != $currentScheme ) ) {
				$value				=	str_replace( 'http', 'https', $value );
			}

			if ( $reason != 'profile' ) {
				$width				=	(int) $field->params->get( 'video_thumbwidth', 400 );
			} else {
				$width				=	(int) $field->params->get( 'video_width', 400 );
			}

			$embed					=	'<div class="cbVideoField' . ( $reason == 'list' ? ' cbClicksInside' : null ) . '" style="max-width: 100%;">';

			if ( in_array( $domain, array( 'youtube', 'youtu' ) ) ) {
				if ( preg_match( '%(?:(?:watch\?v=)|(?:embed/)|(?:be/))([A-Za-z0-9_-]+)%', $value, $matches ) ) {
					// iframe can't have % height so always calculate it:
					$embed	.=				'<iframe width="' . ( $width ? (int) $width : '640' ) . '" height="' . round( ( $width ? (int) $width : 640 ) / 1.78 ) . '" style="max-width: 100%;" src="https://www.youtube.com/embed/' . htmlspecialchars( $matches[1] ) . '" frameborder="0" allowfullscreen class="cbVideoFieldEmbed"></iframe>';
				}
			} else {
				$embed		.=				'<video' . ( $width ? ' width="' . (int) $width . '" height="' . round( (int) $width / 1.78 ) . '"' : ' width="100%"' ) . ' style="max-width: 100%;" src="' . htmlspecialchars( $value ) . '" type="' . htmlspecialchars( $this->getMimeType( $value ) ) . '" controls="controls" preload="auto" class="cbVideoFieldEmbed"></video>';
			}

			$embed					.=	'</div>';
		}

		return $embed;
	}

	/**
	 *
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output            'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason            'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  boolean     $displayFieldIcons
	 * @return string                          HTML: <tag type="$type" value="$value" xxxx="xxx" yy="y" />
	 */
	function _htmlEditForm( &$field, &$user, $output, $reason, $displayFieldIcons = true ) {
		global $_CB_framework;

		$fieldName							=	$field->get( 'name' );
		$value								=	$user->get( $fieldName );
		$required							=	$this->_isRequired( $field, $user, $reason );

		$uploadSizeLimitMax					=	$field->params->get( 'fieldValidateVideo_sizeMax', 1024 );
		$uploadSizeLimitMin					=	$field->params->get( 'fieldValidateVideo_sizeMin', 0 );
		$uploadExtensionLimit				=	$this->allowedExtensions();
		$restrictions						=	array();

		if ( $uploadExtensionLimit ) {
			$restrictions[]					=	CBTxt::Th( 'VIDEO_FILE_UPLOAD_LIMITS_EXT', 'Your video file must be of [ext] type.', array( '[ext]' => implode( ', ', $uploadExtensionLimit ) ) );
		}

		if ( $uploadSizeLimitMin ) {
			$restrictions[]					=	CBTxt::Th( 'VIDEO_FILE_UPLOAD_LIMITS_MIN', 'Your video file should exceed [size].', array( '[size]' => $this->formattedFileSize( $uploadSizeLimitMin * 1024 ) ) );
		}

		if ( $uploadSizeLimitMax ) {
			$restrictions[]					=	CBTxt::Th( 'VIDEO_FILE_UPLOAD_LIMITS_MAX', 'Your video file should not exceed [size].', array( '[size]' => $this->formattedFileSize( $uploadSizeLimitMax * 1024 ) ) );
		}

		$existingFile						=	( $user->get( 'id' ) ? ( ( $value != null ) ? true : false ) : false );
		$choices							=	array();

		if ( ( $reason == 'register' ) || ( ( $reason == 'edit' ) && ( $user->id == 0 ) ) ) {
			if ( $required == 0 ) {
				$choices[]					=	moscomprofilerHTML::makeOption( '', CBTxt::T( 'No video file' ) );
			}
		} else {
			if ( $existingFile || ( $required == 0 ) ) {
				$choices[]					=	moscomprofilerHTML::makeOption( '', CBTxt::T( 'No change of video file' ) );
			}
		}

		$selected							=	null;

		if ( ( $required == 1 ) && ( ! $existingFile ) ) {
			$selected						=	( $field->params->get( 'video_allow_uploads', 1 ) ? 'upload' : 'link' );
		}

		if ( $field->params->get( 'video_allow_links', 1 ) ) {
			$choices[]						=	moscomprofilerHTML::makeOption( 'link', ( $existingFile ? CBTxt::T( 'Link to new video file' ) : CBTxt::T( 'Link to video file' ) ) );
		}

		if ( $field->params->get( 'video_allow_uploads', 1 ) ) {
		$choices[]							=	moscomprofilerHTML::makeOption( 'upload', ( $existingFile ? CBTxt::T( 'Upload new video file' ) : CBTxt::T( 'Upload video file' ) ) );
		}

		if ( $existingFile && ( $required == 0 ) ) {
			$choices[]						=	moscomprofilerHTML::makeOption( 'delete', CBTxt::T( 'Remove video file' ) );
		}

		$return								=	null;

		if ( ( $reason != 'register' ) && ( $user->id != 0 ) && $existingFile ) {
			$return							.=	'<div class="row no-gutters mb-3 cbVideoFieldEmbed">' . $this->getEmbed( $field, $user, $value, $reason ) . '</div>';
		}

		$hasChoices							=	( count( $choices ) > 1 );

		if ( $hasChoices ) {
			static $functOut				=	false;

			$additional						=	' class="form-control cbVideoFieldChoice"';

			if ( ( $_CB_framework->getUi() == 1 ) && ( $reason == 'edit' ) && $field->get( 'readonly' ) ) {
				$additional					.=	' disabled="disabled"';
			}

			$translatedTitle				=	$this->getFieldTitle( $field, $user, 'html', $reason );
			$htmlDescription				=	$this->getFieldDescription( $field, $user, 'htmledit', $reason );
			$trimmedDescription				=	trim( strip_tags( $htmlDescription ) );
			$inputDescription				=	$field->params->get( 'fieldLayoutInputDesc', 1, GetterInterface::INT );

			$tooltip						=	( $trimmedDescription && $inputDescription ? cbTooltip( $_CB_framework->getUi(), $htmlDescription, $translatedTitle, null, null, null, null, $additional ) : $additional );

			$return							.=	'<div class="form-group mb-0 cb_form_line">'
											.		moscomprofilerHTML::selectList( $choices, $fieldName . '__choice', $tooltip, 'value', 'text', $selected, $required, true, null, false )
											.		$this->_fieldIconsHtml( $field, $user, 'htmledit', $reason, 'select', '', null, '', array(), $displayFieldIcons, $required )
											.	'</div>';

			if ( ! $functOut ) {
				$js							=	"$.fn.cbslideVideoFile = function() {"
											.		"var element = $( this );"
											.		"element.parent().siblings( '.cbVideoFieldLink' ).find( 'input' ).prop( 'disabled', true );"
											.		"element.on( 'click.cbvideofield change.cbvideofield', function() {"
											.			"if ( ( $( this ).val() == '' ) || ( $( this ).val() == 'delete' ) ) {"
											.				"element.parent().siblings( '.cbVideoFieldUpload,.cbVideoFieldLink' ).addClass( 'hidden' ).find( 'input' ).prop( 'disabled', true );"
											.			"} else if ( $( this ).val() == 'upload' ) {"
											.				"element.parent().siblings( '.cbVideoFieldUpload' ).removeClass( 'hidden' ).find( 'input' ).prop( 'disabled', false );"
											.				"element.parent().siblings( '.cbVideoFieldLink' ).addClass( 'hidden' ).find( 'input' ).prop( 'disabled', true );"
											.			"} else if ( $( this ).val() == 'link' ) {"
											.				"element.parent().siblings( '.cbVideoFieldLink' ).removeClass( 'hidden' ).find( 'input' ).prop( 'disabled', false );"
											.				"element.parent().siblings( '.cbVideoFieldUpload' ).addClass( 'hidden' ).find( 'input' ).prop( 'disabled', true );"
											.			"}"
											.		"}).on( 'cloned.cbvideofield', function() {"
											.			"$( this ).parent().siblings( '.cbVideoFieldEmbed' ).remove();"
											.			"if ( $( this ).parent().siblings( '.cbVideoFieldUpload,.cbVideoFieldLink' ).find( 'input.required' ).length ) {"
											.				"$( this ).find( 'option[value=\"\"]' ).remove();"
											.			"}"
											.			"$( this ).find( 'option[value=\"delete\"]' ).remove();"
											.			"$( this ).off( '.cbvideofield' );"
											.			"$( this ).cbslideVideoFile();"
											.		"}).change();"
											.		"return this;"
											.	"};";

				$_CB_framework->outputCbJQuery( $js );

				$functOut					=	true;
			}

			$_CB_framework->outputCbJQuery( "$( '#" . addslashes( $fieldName ) . "__choice' ).cbslideVideoFile();" );
		} else {
			$return							.=	'<input type="hidden" name="' . htmlspecialchars( $fieldName ) . '__choice" value="' . htmlspecialchars( $choices[0]->value ) . '" />';
		}

		if ( $field->params->get( 'video_allow_uploads', 1 ) ) {
			$validationAttributes			=	array();
			$validationAttributes[]			=	cbValidator::getRuleHtmlAttributes( 'extension', implode( ',', $uploadExtensionLimit ) );

			if ( $uploadSizeLimitMin || $uploadSizeLimitMax ) {
				$validationAttributes[]		=	cbValidator::getRuleHtmlAttributes( 'filesize', array( $uploadSizeLimitMin, $uploadSizeLimitMax, 'KB' ) );
			}

			$return							.=	'<div id="cbvideofile_upload_' . htmlspecialchars( $fieldName ) . '" class="form-group mb-0 cb_form_line' . ( $hasChoices ? ' mt-3 hidden' : null ) . ' cbVideoFieldUpload">'
											.		( $restrictions ? '<div class="mb-2">' . implode( ' ', $restrictions ) . '</div>' : null )
											.		'<div>'
											.			CBTxt::T( 'Select video file' ) . ' <input type="file" name="' . htmlspecialchars( $fieldName ) . '__file" value="" class="form-control' . ( $required == 1 ? ' required' : null ) . '"' . implode( ' ', $validationAttributes ) . ( $hasChoices ? ' disabled="disabled"' : null ) . ' />'
											.			( count( $choices ) <= 0 ? $this->_fieldIconsHtml( $field, $user, 'htmledit', $reason, 'select', '', null, '', array(), $displayFieldIcons, $required ) : null )
											.		'</div>'
											.		'<div class="mt-2">';

			if ( $field->params->get( 'video_terms', 0 ) ) {
				$cbUser						=	CBuser::getMyInstance();
				$termsOutput				=	$field->params->get( 'terms_output', 'url' );
				$termsType					=	$cbUser->replaceUserVars( $field->params->get( 'terms_type', 'TERMS_AND_CONDITIONS' ) );
				$termsDisplay				=	$field->params->get( 'terms_display', 'modal' );
				$termsURL					=	cbSef( $cbUser->replaceUserVars( $field->params->get( 'terms_url', null ) ), false );
				$termsText					=	$cbUser->replaceUserVars( $field->params->get( 'terms_text', null ) );
				$termsWidth					=	$field->params->get( 'terms_width', 400 );
				$termsHeight				=	$field->params->get( 'terms_height', 200 );

				if ( ! $termsType ) {
					$termsType				=	CBTxt::T( 'TERMS_AND_CONDITIONS', 'Terms and Conditions' );
				}

				if ( ! $termsWidth ) {
					$termsWidth				=	400;
				}

				if ( ! $termsHeight ) {
					$termsHeight			=	200;
				}

				if ( ( ( $termsOutput == 'url' ) && $termsURL ) || ( ( $termsOutput == 'text' ) && $termsText ) ) {
					if ( $termsDisplay == 'iframe' ) {
						if ( is_numeric( $termsHeight ) ) {
							$termsHeight	.=	'px';
						}

						if ( is_numeric( $termsWidth ) ) {
							$termsWidth		.=	'px';
						}

						if ( $termsOutput == 'url' ) {
							$return			.=	'<div class="embed-responsive mb-2 cbTermsFrameContainer" style="padding-bottom: ' . htmlspecialchars( $termsHeight ) . ';">'
											.		'<iframe class="embed-responsive-item d-block border rounded cbTermsFrameURL" style="width: ' . htmlspecialchars( $termsWidth ) . ';" src="' . htmlspecialchars( $termsURL ) . '"></iframe>'
											.	'</div>';
						} else {
							$return			.=	'<div class="bg-light border rounded p-2 mb-2 cbTermsFrameText" style="height:' . htmlspecialchars( $termsHeight ) . ';width:' . htmlspecialchars( $termsWidth ) . ';overflow:auto;">' . $termsText . '</div>';
						}

						$return				.=			CBTxt::Th( 'BY_UPLOADING_YOU_CERTIFY_THAT_YOU_HAVE_THE_RIGHT_TO_DISTRIBUTE_THIS_VIDEO_FILE_TERMS', 'By uploading, you certify that you have the right to distribute this video file and that it does not violate the above [type].', array( '[type]' => $termsType ) );
					} else {
						$attributes			=	' class="cbTermsLink"';

						if ( ( $termsOutput == 'text' ) && ( $termsDisplay == 'window' ) ) {
							$termsDisplay	=	'modal';
						}

						if ( $termsDisplay == 'modal' ) {
							// Tooltip height percentage would be based off window height (including scrolling); lets change it to be based off the viewport height:
							$termsHeight	=	( substr( $termsHeight, -1 ) == '%' ? (int) substr( $termsHeight, 0, -1 ) . 'vh' : $termsHeight );

							if ( $termsOutput == 'url' ) {
								$tooltip	=	'<iframe class="d-block m-0 p-0 border-0 cbTermsModalURL" height="100%" width="100%" src="' . htmlspecialchars( $termsURL ) . '"></iframe>';
							} else {
								$tooltip	=	'<div class="cbTermsModalText" style="height:100%;width:100%;overflow:auto;">' . $termsText . '</div>';
							}

							$url			=	'javascript:void(0);';
							$attributes		.=	' ' . cbTooltip( $_CB_framework->getUi(), $tooltip, $termsType, array( $termsWidth, $termsHeight ), null, null, null, 'data-hascbtooltip="true" data-cbtooltip-modal="true"' );
						} else {
							$url			=	htmlspecialchars( $termsURL );
							$attributes		.=	' target="_blank"';
						}

						$return				.=			CBTxt::Th( 'BY_UPLOADING_YOU_CERTIFY_THAT_YOU_HAVE_THE_RIGHT_TO_DISTRIBUTE_THIS_VIDEO_FILE_URL_TERMS', 'By uploading, you certify that you have the right to distribute this video file and that it does not violate the <a href="[url]"[attributes]>[type]</a>', array( '[url]' => $url, '[attributes]' => $attributes, '[type]' => $termsType ) );
					}
				} else {
					$return					.=			CBTxt::Th( 'BY_UPLOADING_YOU_CERTIFY_THAT_YOU_HAVE_THE_RIGHT_TO_DISTRIBUTE_THIS_VIDEO_FILE', 'By uploading, you certify that you have the right to distribute this video file.' );
				}
			} else {
				$return						.=			CBTxt::Th( 'BY_UPLOADING_YOU_CERTIFY_THAT_YOU_HAVE_THE_RIGHT_TO_DISTRIBUTE_THIS_VIDEO_FILE', 'By uploading, you certify that you have the right to distribute this video file.' );
			}

			$return							.=		'</div>'
											.	'</div>';
		}

		if ( $field->params->get( 'video_allow_links', 1 ) ) {
			$return							.=	'<div id="cbvideofile_link_' . htmlspecialchars( $fieldName ) . '" class="form-group mb-0 cb_form_line' . ( $hasChoices ? ' mt-3 hidden' : null ) . ' cbVideoFieldLink">';

			$linkField						=	new FieldTable( $field->getDbo() );

			foreach ( array_keys( get_object_vars( $linkField ) ) as $k ) {
				$linkField->set( $k, $field->get( $k ) );
			}

			$linkField->set( 'type', 'text' );
			$linkField->set( 'description', null );

			$user->set( $fieldName, ( ( strpos( $value, '/' ) !== false ) || ( strpos( $value, '\\' ) !== false ) ? $value : null ) );

			$return							.=		parent::getField( $linkField, $user, $output, $reason, 0 );

			$user->set( $fieldName, $value );

			unset( $linkField );

			$return							.=	'</div>';
		}

		return $return;
	}

	/**
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 */
	public function prepareFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		global $_CB_framework, $_CB_database;

		$this->_prepareFieldMetaSave( $field, $user, $postdata, $reason );

		$col					=	$field->get( 'name' );
		$colChoice				=	$col . '__choice';
		$colFile				=	$col . '__file';
		$choice					=	stripslashes( cbGetParam( $postdata, $colChoice ) );

		switch ( $choice ) {
			case 'upload':
				$value			=	( isset( $_FILES[$colFile] ) ? $_FILES[$colFile] : null );

				$this->validate( $field, $user, $choice, $value, $postdata, $reason );
				break;
			case 'link':
				parent::prepareFieldDataSave( $field, $user, $postdata, $reason );
				break;
			case 'delete':
				if ( $user->get( 'id' ) && ( $user->get( $col ) != null ) && ( $user->get( $col ) != '' ) ) {
					if ( isset( $user->$col ) ) {
						$this->_logFieldUpdate( $field, $user, $reason, $user->get( $col ), '' );
					}

					$value		=	$user->get( $col );

					if ( ( strpos( $value, '/' ) === false ) && ( strpos( $value, '\\' ) === false ) ) {
						$this->deleteFiles( $user, $user->get( $col ) );
					}

					$user->set( $col, null );

					// This is needed because user store does not save null:
					if ( $field->get( 'table' ) ) {
						$query	=	'UPDATE ' . $_CB_database->NameQuote( $field->get( 'table' ) )
								.	"\n SET " . $_CB_database->NameQuote( $col ) . " = NULL"
								.	', ' . $_CB_database->NameQuote( 'lastupdatedate' ) . ' = ' . $_CB_database->Quote( $_CB_framework->dateDbOfNow() )
								.	"\n WHERE " . $_CB_database->NameQuote( 'id' ) . " = " . (int) $user->get( 'id' );
						$_CB_database->setQuery( $query );
						$_CB_database->query();
					}
				}
				break;
			default:
					$value		=	$user->get( $col );

					$this->validate( $field, $user, $choice, $value, $postdata, $reason );
				break;
		}
	}

	/**
	 * Mutator:
	 * Prepares field data commit
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save user edit, 'register' for save registration
	 */
	public function commitFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		global $_CB_framework, $_PLUGINS, $_FILES;

		$col						=	$field->get( 'name' );
		$colChoice					=	$col . '__choice';
		$colFile					=	$col . '__file';
		$choice						=	stripslashes( cbGetParam( $postdata, $colChoice ) );

		switch ( $choice ) {
			case 'upload':
				$value				=	( isset( $_FILES[$colFile] ) ? $_FILES[$colFile] : null );

				if ( $this->validate( $field, $user, $choice, $value, $postdata, $reason ) ) {
					$_PLUGINS->loadPluginGroup( 'user' );

					$_PLUGINS->trigger( 'onBeforeUserVideoUpdate', array( &$user, &$value['tmp_name'] ) );

					if ( $_PLUGINS->is_errors() ) {
						$this->_setErrorMSG( $_PLUGINS->getErrorMSG() );
						return;
					}

					$path			=	$_CB_framework->getCfg( 'absolute_path' );
					$indexPath		=	$path . '/components/com_comprofiler/index.html';
					$filesPath		=	$path . '/images/comprofiler/video';
					$filePath		=	$filesPath . '/' . (int) $user->get( 'id' );

					if ( ! is_dir( $filesPath ) ) {
						$oldmask	=	@umask( 0 );

						if ( @mkdir( $filesPath, 0755, true ) ) {
							@umask( $oldmask );
							@chmod( $filesPath, 0755 );

							if ( ! file_exists( $filesPath . '/index.html' ) ) {
								@copy( $indexPath, $filesPath . '/index.html' );
								@chmod( $filesPath . '/index.html', 0755 );
							}
						} else {
							@umask( $oldmask );
						}
					}

					if ( ! is_dir( $filePath ) ) {
						$oldmask	=	@umask( 0 );

						if ( @mkdir( $filePath, 0755, true ) ) {
							@umask( $oldmask );
							@chmod( $filePath, 0755 );

							if ( ! file_exists( $filePath . '/index.html' ) ) {
								@copy( $indexPath, $filePath . '/index.html' );
								@chmod( $filePath . '/index.html', 0755 );
							}
						} else {
							@umask( $oldmask );
						}
					}

					$uploadedExt	=	strtolower( preg_replace( '/[^-a-zA-Z0-9_]/u', '', pathinfo( $value['name'], PATHINFO_EXTENSION ) ) );
					$newFileName	=	$col . '_' . uniqid( $user->id . '_' ) . '.' . $uploadedExt;

					if ( ! move_uploaded_file( $value['tmp_name'], $filePath . '/'. $newFileName ) ) {
						$this->_setValidationError( $field, $user, $reason, sprintf( CBTxt::T( 'CBVideo-failed to upload video file: %s' ), $newFileName ) );
						return;
					} else {
						@chmod( $filePath . '/' . $value['tmp_name'], 0755 );
					}

					if ( isset( $user->$col ) ) {
						$this->_logFieldUpdate( $field, $user, $reason, $user->get( $col ), '' );
					}

					if ( isset( $user->$col ) && ( $user->get( $col ) != '' ) ) {
						$this->deleteFiles( $user, $user->get( $col ) );
					}

					$user->set( $col, $newFileName );

					$_PLUGINS->trigger( 'onAfterUserVideoUpdate', array( &$user, $newFileName ) );
				}
				break;
		}
	}

	/**
	 * Mutator:
	 * Prepares field data rollback
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save user edit, 'register' for save registration
	 */
	public function rollbackFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		global $_FILES;

		$col			=	$field->get( 'name' );
		$colChoice		=	$col . '__choice';
		$colFile		=	$col . '__file';

		$choice			=	stripslashes( cbGetParam( $postdata, $colChoice ) );

		switch ( $choice ) {
			case 'upload':
				$value	=	( isset( $_FILES[$colFile] ) ? $_FILES[$colFile] : null );

				if ( $this->validate( $field, $user, $choice, $value, $postdata, $reason ) ) {
					$this->deleteFiles( $user, $user->get( $col ) );
				}
				break;
		}
	}

	/**
	 * Validator:
	 * Validates $value for $field->required and other rules
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user        RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  string      $columnName  Column to validate
	 * @param  string      $value       (RETURNED:) Value to validate, Returned Modified if needed !
	 * @param  array       $postdata    Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason      'edit' for save user edit, 'register' for save registration
	 * @return boolean                  True if validate, $this->_setErrorMSG if False
	 */
	public function validate( &$field, &$user, $columnName, &$value, &$postdata, $reason ) {
		$isRequired							=	$this->_isRequired( $field, $user, $reason );

		$col								=	$field->get( 'name' );
		$colChoice							=	$col . '__choice';
		$choice								=	stripslashes( cbGetParam( $postdata, $colChoice ) );

		switch ( $choice ) {
			case 'upload':
				if ( ! $field->params->get( 'video_allow_uploads', 1 ) ) {
					$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'UE_NOT_AUTHORIZED', 'You are not authorized to view this page!' ) );
					return false;
				} elseif ( ! isset( $value['tmp_name'] ) || empty( $value['tmp_name'] ) || ( $value['error'] != 0 ) || ( ! is_uploaded_file( $value['tmp_name'] ) ) ) {
					if ( $isRequired ) {
						$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'Please select a video file before uploading' ) );
					}

					return false;
				} else {
					$uploadSizeLimitMax		=	$field->params->get( 'fieldValidateVideo_sizeMax', 1024 );
					$uploadSizeLimitMin		=	$field->params->get( 'fieldValidateVideo_sizeMin', 0 );
					$uploadExtensionLimit	=	$this->allowedExtensions();
					$uploadedExt			=	strtolower( preg_replace( '/[^-a-zA-Z0-9_]/u', '', pathinfo( $value['name'], PATHINFO_EXTENSION ) ) );

					if ( ( ! $uploadedExt ) || ( ! in_array( $uploadedExt, $uploadExtensionLimit ) ) ) {
						$this->_setValidationError( $field, $user, $reason, sprintf( CBTxt::T( 'Please upload only %s' ), implode( ', ', $uploadExtensionLimit ) ) );
						return false;
					}

					$uploadedSize			=	$value['size'];

					if ( ( $uploadedSize / 1024 ) > $uploadSizeLimitMax ) {
						$this->_setValidationError( $field, $user, $reason, sprintf( CBTxt::T( 'The video file size exceeds the maximum of %s' ), $this->formattedFileSize( $uploadSizeLimitMax * 1024 ) ) );
						return false;
					}

					if ( ( $uploadedSize / 1024 ) < $uploadSizeLimitMin ) {
						$this->_setValidationError( $field, $user, $reason, sprintf( CBTxt::T( 'The video file is too small, the minimum is %s' ), $this->formattedFileSize( $uploadSizeLimitMin * 1024 ) ) );
						return false;
					}
				}
				break;
			case 'link':
				if ( ! $field->params->get( 'video_allow_links', 1 ) ) {
					$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'UE_NOT_AUTHORIZED', 'You are not authorized to view this page!' ) );
					return false;
				}

				$validated					=	parent::validate( $field, $user, $columnName, $value, $postdata, $reason );

				if ( $validated && ( $value !== '' ) && ( $value !== null ) ) {
					$domain					=	preg_replace( '/^(?:(?:\w+\.)*)?(\w+)\..+$/', '\1', parse_url( $value, PHP_URL_HOST ) );

					if ( ! in_array( $domain, array( 'youtube', 'youtu' ) ) ) {
						$linkExists			=	false;

						try {
							$request		=	new \GuzzleHttp\Client();

							$header			=	$request->head( $value );

							if ( ( $header !== false ) && ( $header->getStatusCode() == 200 ) ) {
								$linkExists	=	true;
							}
						} catch( Exception $e ) {}

						if ( ! $linkExists ) {
							$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'Please input a video file url before linking' ) );
							return false;
						}

						$linkExtLimit		=	$this->allowedExtensions();
						$linkExt			=	strtolower( pathinfo( $value, PATHINFO_EXTENSION ) );

						if ( ( ! $linkExt ) || ( ! in_array( $linkExt, $linkExtLimit ) ) ) {
							$linkExtLimit[]	=	'youtube';

							$this->_setValidationError( $field, $user, $reason, sprintf( CBTxt::T( 'Please link only %s' ), implode( ', ', $linkExtLimit ) ) );
							return false;
						}
					}
				}

				return $validated;
				break;
			default:
				$valCol						=	$field->get( 'name' );

				if ( $isRequired && ( ( ! $user ) || ( ! isset( $user->$valCol ) ) || ( ! $user->get( $valCol ) ) ) ) {
					if ( ! $value ) {
						$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'UE_FIELDREQUIRED', 'This Field is required' ) );
						return false;
					}
				}
				break;
		}

		return true;
	}

	/**
	 * Deletes file from users folder
	 *
	 * @param  UserTable  $user
	 * @param  string     $file
	 */
	function deleteFiles( $user, $file = null ) {
		global $_CB_framework;

		if ( ! is_object( $user ) ) {
			return;
		}

		$filePath	=	$_CB_framework->getCfg( 'absolute_path' ) . '/images/comprofiler/video/' . (int) $user->id . '/';

		if ( ! is_dir( $filePath ) ) {
			return;
		}

		if ( ! $file ) {
			if ( false !== ( $handle = opendir( $filePath ) ) ) {
				while ( false !== ( $file = readdir( $handle ) ) ) {
					if ( $file && ( ( $file != '.' ) && ( $file != '..' ) ) ) {
						@unlink( $filePath . $file );
					}
				}
				closedir( $handle );
			}

			if ( is_dir( $filePath ) ) {
				@rmdir( $filePath );
			}
		} else {
			if ( file_exists( $filePath . $file ) ) {
				@unlink( $filePath . $file );
			}
		}
	}

	/**
	 * returns the mimetype of the supplied file or link
	 *
	 * @param  string  $value
	 * @return string
	 */
	function getMimeType( $value ) {
		$domain			=	preg_replace( '/^(?:(?:\w+\.)*)?(\w+)\..+$/', '\1', parse_url( $value, PHP_URL_HOST ) );

		if ( $domain && in_array( $domain, array( 'youtube', 'youtu' ) ) ) {
			return 'video/youtube';
		}

		$extension		=	strtolower( pathinfo( ( $domain ? $value : preg_replace( '/[^-a-zA-Z0-9_.]/u', '', $value ) ), PATHINFO_EXTENSION ) );

		return ( $extension == 'm4v' ? 'video/mp4' : cbGetMimeFromExt( $extension ) );
	}

	/**
	 * outputs a secure list of allowed file extensions
	 *
	 * @return array
	 */
	private function allowedExtensions() {
		return array( 'mp4', 'ogv', 'ogg', 'webm', 'm4v' );
	}

	/**
	 * Returns file size formatted from bytes
	 *
	 * @param int $bytes
	 * @return string
	 */
	private function formattedFileSize( $bytes )
	{
		if ( $bytes >= 1099511627776 ) {
			$size	=	CBTxt::Th( 'FILESIZE_FORMATTED_TB', '%%COUNT%% TB|%%COUNT%% TBs', array( '%%COUNT%%' => (float) number_format( $bytes / 1099511627776, 2, '.', '' ) ) );
		} elseif ( $bytes >= 1073741824 ) {
			$size	=	CBTxt::Th( 'FILESIZE_FORMATTED_GB', '%%COUNT%% GB|%%COUNT%% GBs', array( '%%COUNT%%' => (float) number_format( $bytes / 1073741824, 2, '.', '' ) ) );
		} elseif ( $bytes >= 1048576 ) {
			$size	=	CBTxt::Th( 'FILESIZE_FORMATTED_MB', '%%COUNT%% MB|%%COUNT%% MBs', array( '%%COUNT%%' => (float) number_format( $bytes / 1048576, 2, '.', '' ) ) );
		} elseif ( $bytes >= 1024 ) {
			$size	=	CBTxt::Th( 'FILESIZE_FORMATTED_KB', '%%COUNT%% KB|%%COUNT%% KBs', array( '%%COUNT%%' => (float) number_format( $bytes / 1024, 2, '.', '' ) ) );
		} else {
			$size	=	CBTxt::Th( 'FILESIZE_FORMATTED_B', '%%COUNT%% B|%%COUNT%% Bs', array( '%%COUNT%%' => (float) number_format( $bytes, 2, '.', '' ) ) );
		}

		return $size;
	}
}

class CBfield_audio extends CBfield_text {

	/**
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		$return						=	null;

		$value						=	$user->get( $field->get( 'name' ) );

		switch ( $output ) {
			case 'html':
			case 'rss':
				if ( $value ) {
					$return			=	$this->getEmbed( $field, $user, $value, $reason );
				}

				$return				=	$this->formatFieldValueLayout( $return, $reason, $field, $user );
				break;
			/** @noinspection PhpMissingBreakStatementInspection */
			case 'htmledit':
					if ( $reason == 'search' ) {
						$choices	=	array();
						$choices[]	=	moscomprofilerHTML::makeOption( '', CBTxt::T( 'UE_NO_PREFERENCE', 'No preference' ) );
						$choices[]	=	moscomprofilerHTML::makeOption( '1', ( $field->params->get( 'audio_allow_links', 1 ) ? CBTxt::T( 'Has audio file or link' ) : CBTxt::T( 'Has a audio file' ) ) );
						$choices[]	=	moscomprofilerHTML::makeOption( '0', ( $field->params->get( 'audio_allow_links', 1 ) ? CBTxt::T( 'Has no audio file or link' ) : CBTxt::T( 'Has no audio file' ) ) );

						$html		=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', 'select', $value, '', $choices, true, null, false );

						$return		=	$this->_fieldSearchModeHtml( $field, $user, $html, 'singlechoice', $list_compare_types );
					} else {
						$return		=	$this->formatFieldValueLayout( $this->_htmlEditForm( $field, $user, $output, $reason ), $reason, $field, $user );
					}
					break;
			default:
				$field->set( 'type', 'text' );

				$return				=	parent::getField( $field, $user, $output, $reason, $list_compare_types );
				break;
		}

		return $return;
	}

	/**
	 * returns audio embed based off audio url
	 *
	 * @param  FieldTable   $field
	 * @param  UserTable    $user
	 * @param  string       $value
	 * @param  string       $reason
	 * @return null|string
	 */
	public function getEmbed( $field, $user, $value, $reason ) {
		global $_CB_framework;

		$domain						=	preg_replace( '/^(?:(?:\w+\.)*)?(\w+)\..+$/', '\1', parse_url( $value, PHP_URL_HOST ) );

		if ( ! $domain ) {
			$value					=	$_CB_framework->getCfg( 'live_site' ) . '/images/comprofiler/audio/' . (int) $user->get( 'id' ) . '/' . urlencode( $value );
		}

		$embed						=	null;

		if ( $value ) {
			$currentScheme			=	( ( isset( $_SERVER['HTTPS'] ) && ( ! empty( $_SERVER['HTTPS'] ) ) && ( $_SERVER['HTTPS'] != 'off' ) ) ? 'https' : 'http' );
			$urlScheme				=	parse_url( $value, PHP_URL_SCHEME );

			if ( ! $urlScheme ) {
				$urlScheme			=	$currentScheme;
			}

			if ( ( $currentScheme == 'https' ) && ( $urlScheme != $currentScheme ) ) {
				$value				=	str_replace( 'http', 'https', $value );
			}

			if ( $reason != 'profile' ) {
				$width				=	(int) $field->params->get( 'audio_thumbwidth', 400 );
			} else {
				$width				=	(int) $field->params->get( 'audio_width', 400 );
			}

			$embed					=	'<div class="cbAudioField' . ( $reason == 'list' ? ' cbClicksInside' : null ) . '">'
									.		'<audio style="width: ' . ( $width ? (int) $width . 'px' : '100%' ) . '; max-width: 100%;" src="' . htmlspecialchars( $value ) . '" type="' . htmlspecialchars( $this->getMimeType( $value ) ) . '" controls="controls" preload="auto" class="cbAudioFieldEmbed"></audio>'
									.	'</div>';
		}

		return $embed;
	}

	/**
	 *
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output             'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason             'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  boolean     $displayFieldIcons
	 * @return string                          HTML: <tag type="$type" value="$value" xxxx="xxx" yy="y" />
	 */
	function _htmlEditForm( &$field, &$user, $output, $reason, $displayFieldIcons = true ) {
		global $_CB_framework;

		$fieldName							=	$field->get( 'name' );
		$value								=	$user->get( $fieldName );
		$required							=	$this->_isRequired( $field, $user, $reason );

		$uploadSizeLimitMax					=	$field->params->get( 'fieldValidateAudio_sizeMax', 1024 );
		$uploadSizeLimitMin					=	$field->params->get( 'fieldValidateAudio_sizeMin', 0 );
		$uploadExtensionLimit				=	$this->allowedExtensions();
		$restrictions						=	array();

		if ( $uploadExtensionLimit ) {
			$restrictions[]					=	CBTxt::Th( 'AUDIO_FILE_UPLOAD_LIMITS_EXT', 'Your audio file must be of [ext] type.', array( '[ext]' => implode( ', ', $uploadExtensionLimit ) ) );
		}

		if ( $uploadSizeLimitMin ) {
			$restrictions[]					=	CBTxt::Th( 'AUDIO_FILE_UPLOAD_LIMITS_MIN', 'Your audio file should exceed [size].', array( '[size]' => $this->formattedFileSize( $uploadSizeLimitMin * 1024 ) ) );
		}

		if ( $uploadSizeLimitMax ) {
			$restrictions[]					=	CBTxt::Th( 'AUDIO_FILE_UPLOAD_LIMITS_MAX', 'Your audio file should not exceed [size].', array( '[size]' => $this->formattedFileSize( $uploadSizeLimitMax * 1024 ) ) );
		}

		$existingFile						=	( $user->get( 'id' ) ? ( ( $value != null ) ? true : false ) : false );
		$choices							=	array();

		if ( ( $reason == 'register' ) || ( ( $reason == 'edit' ) && ( $user->id == 0 ) ) ) {
			if ( $required == 0 ) {
				$choices[]					=	moscomprofilerHTML::makeOption( '', CBTxt::T( 'No audio file' ) );
			}
		} else {
			if ( $existingFile || ( $required == 0 ) ) {
				$choices[]					=	moscomprofilerHTML::makeOption( '', CBTxt::T( 'No change of audio file' ) );
			}
		}

		$selected							=	null;

		if ( ( $required == 1 ) && ( ! $existingFile ) ) {
			$selected						=	( $field->params->get( 'audio_allow_uploads', 1 ) ? 'upload' : 'link' );
		}

		if ( $field->params->get( 'audio_allow_links', 1 ) ) {
			$choices[]						=	moscomprofilerHTML::makeOption( 'link', ( $existingFile ? CBTxt::T( 'Link to new audio file' ) : CBTxt::T( 'Link to audio file' ) ) );
		}

		if ( $field->params->get( 'audio_allow_uploads', 1 ) ) {
		$choices[]							=	moscomprofilerHTML::makeOption( 'upload', ( $existingFile ? CBTxt::T( 'Upload new audio file' ) : CBTxt::T( 'Upload audio file' ) ) );
		}

		if ( $existingFile && ( $required == 0 ) ) {
			$choices[]						=	moscomprofilerHTML::makeOption( 'delete', CBTxt::T( 'Remove audio file' ) );
		}

		$return								=	null;

		if ( ( $reason != 'register' ) && ( $user->id != 0 ) && $existingFile ) {
			$return							.=	'<div class="row no-gutters mb-3 cbAudioFieldEmbed">' . $this->getEmbed( $field, $user, $value, $reason ) . '</div>';
		}

		$hasChoices							=	( count( $choices ) > 1 );

		if ( $hasChoices ) {
			static $functOut			=	false;

			$additional						=	' class="form-control cbAudioFieldChoice"';

			if ( ( $_CB_framework->getUi() == 1 ) && ( $reason == 'edit' ) && $field->get( 'readonly' ) ) {
				$additional					.=	' disabled="disabled"';
			}

			$translatedTitle				=	$this->getFieldTitle( $field, $user, 'html', $reason );
			$htmlDescription				=	$this->getFieldDescription( $field, $user, 'htmledit', $reason );
			$trimmedDescription				=	trim( strip_tags( $htmlDescription ) );
			$inputDescription				=	$field->params->get( 'fieldLayoutInputDesc', 1, GetterInterface::INT );

			$tooltip						=	( $trimmedDescription && $inputDescription ? cbTooltip( $_CB_framework->getUi(), $htmlDescription, $translatedTitle, null, null, null, null, $additional ) : $additional );

			$return							.=	'<div class="form-group mb-0 cb_form_line">'
											.		moscomprofilerHTML::selectList( $choices, $fieldName . '__choice', $tooltip, 'value', 'text', $selected, $required, true, null, false )
											.		$this->_fieldIconsHtml( $field, $user, 'htmledit', $reason, 'select', '', null, '', array(), $displayFieldIcons, $required )
											.	'</div>';

			if ( ! $functOut ) {
				$js							=	"$.fn.cbslideAudioFile = function() {"
											.		"var element = $( this );"
											.		"element.parent().siblings( '.cbAudioFieldLink' ).find( 'input' ).prop( 'disabled', true );"
											.		"element.on( 'click.cbaudiofield change.cbaudiofield', function() {"
											.			"if ( ( $( this ).val() == '' ) || ( $( this ).val() == 'delete' ) ) {"
											.				"element.parent().siblings( '.cbAudioFieldUpload,.cbAudioFieldLink' ).addClass( 'hidden' ).find( 'input' ).prop( 'disabled', true );"
											.			"} else if ( $( this ).val() == 'upload' ) {"
											.				"element.parent().siblings( '.cbAudioFieldUpload' ).removeClass( 'hidden' ).find( 'input' ).prop( 'disabled', false );"
											.				"element.parent().siblings( '.cbAudioFieldLink' ).addClass( 'hidden' ).find( 'input' ).prop( 'disabled', true );"
											.			"} else if ( $( this ).val() == 'link' ) {"
											.				"element.parent().siblings( '.cbAudioFieldLink' ).removeClass( 'hidden' ).find( 'input' ).prop( 'disabled', false );"
											.				"element.parent().siblings( '.cbAudioFieldUpload' ).addClass( 'hidden' ).find( 'input' ).prop( 'disabled', true );"
											.			"}"
											.		"}).on( 'cloned.cbaudiofield', function() {"
											.			"$( this ).parent().siblings( '.cbAudioFieldEmbed' ).remove();"
											.			"if ( $( this ).parent().siblings( '.cbAudioFieldUpload,.cbAudioFieldLink' ).find( 'input.required' ).length ) {"
											.				"$( this ).find( 'option[value=\"\"]' ).remove();"
											.			"}"
											.			"$( this ).find( 'option[value=\"delete\"]' ).remove();"
											.			"$( this ).off( '.cbaudiofield' );"
											.			"$( this ).cbslideAudioFile();"
											.		"}).change();"
											.		"return this;"
											.	"};";

				$_CB_framework->outputCbJQuery( $js );

				$functOut					=	true;
			}

			$_CB_framework->outputCbJQuery( "$( '#" . addslashes( $fieldName ) . "__choice' ).cbslideAudioFile();" );
		} else {
			$return							.=	'<input type="hidden" name="' . htmlspecialchars( $fieldName ) . '__choice" value="' . htmlspecialchars( $choices[0]->value ) . '" />';
		}

		if ( $field->params->get( 'audio_allow_uploads', 1 ) ) {
			$validationAttributes			=	array();
			$validationAttributes[]			=	cbValidator::getRuleHtmlAttributes( 'extension', implode( ',', $uploadExtensionLimit ) );

			if ( $uploadSizeLimitMin || $uploadSizeLimitMax ) {
				$validationAttributes[]		=	cbValidator::getRuleHtmlAttributes( 'filesize', array( $uploadSizeLimitMin, $uploadSizeLimitMax, 'KB' ) );
			}

			$return							.=	'<div id="cbaudiofile_upload_' . htmlspecialchars( $fieldName ) . '" class="form-group mb-0 cb_form_line' . ( $hasChoices ? ' mt-3 hidden' : null ) . ' cbAudioFieldUpload">'
											.		( $restrictions ? '<div class="mb-2">' . implode( ' ', $restrictions ) . '</div>' : null )
											.		'<div>'
											.			CBTxt::T( 'Select audio file' ) . ' <input type="file" name="' . htmlspecialchars( $fieldName ) . '__file" value="" class="form-control' . ( $required == 1 ? ' required' : null ) . '"' . implode( ' ', $validationAttributes ) . ( $hasChoices ? ' disabled="disabled"' : null ) . ' />'
											.			( count( $choices ) <= 0 ? $this->_fieldIconsHtml( $field, $user, 'htmledit', $reason, 'select', '', null, '', array(), $displayFieldIcons, $required ) : null )
											.		'</div>'
											.		'<div class="mt-2">';

			if ( $field->params->get( 'audio_terms', 0 ) ) {
				$cbUser						=	CBuser::getMyInstance();
				$termsOutput				=	$field->params->get( 'terms_output', 'url' );
				$termsType					=	$cbUser->replaceUserVars( $field->params->get( 'terms_type', 'TERMS_AND_CONDITIONS' ) );
				$termsDisplay				=	$field->params->get( 'terms_display', 'modal' );
				$termsURL					=	cbSef( $cbUser->replaceUserVars( $field->params->get( 'terms_url', null ) ), false );
				$termsText					=	$cbUser->replaceUserVars( $field->params->get( 'terms_text', null ) );
				$termsWidth					=	$field->params->get( 'terms_width', 400 );
				$termsHeight				=	$field->params->get( 'terms_height', 200 );

				if ( ! $termsType ) {
					$termsType				=	CBTxt::T( 'TERMS_AND_CONDITIONS', 'Terms and Conditions' );
				}

				if ( ! $termsWidth ) {
					$termsWidth				=	400;
				}

				if ( ! $termsHeight ) {
					$termsHeight			=	200;
				}

				if ( ( ( $termsOutput == 'url' ) && $termsURL ) || ( ( $termsOutput == 'text' ) && $termsText ) ) {
					if ( $termsDisplay == 'iframe' ) {
						if ( is_numeric( $termsHeight ) ) {
							$termsHeight	.=	'px';
						}

						if ( is_numeric( $termsWidth ) ) {
							$termsWidth		.=	'px';
						}

						if ( $termsOutput == 'url' ) {
							$return			.=	'<div class="embed-responsive mb-2 cbTermsFrameContainer" style="padding-bottom: ' . htmlspecialchars( $termsHeight ) . ';">'
											.		'<iframe class="embed-responsive-item d-block border rounded cbTermsFrameURL" style="width: ' . htmlspecialchars( $termsWidth ) . ';" src="' . htmlspecialchars( $termsURL ) . '"></iframe>'
											.	'</div>';
						} else {
							$return			.=	'<div class="bg-light border rounded p-2 mb-2 cbTermsFrameText" style="height:' . htmlspecialchars( $termsHeight ) . ';width:' . htmlspecialchars( $termsWidth ) . ';overflow:auto;">' . $termsText . '</div>';
						}

						$return				.=			CBTxt::Th( 'BY_UPLOADING_YOU_CERTIFY_THAT_YOU_HAVE_THE_RIGHT_TO_DISTRIBUTE_THIS_AUDIO_FILE_TERMS', 'By uploading, you certify that you have the right to distribute this audio file and that it does not violate the above [type].', array( '[type]' => $termsType ) );
					} else {
						$attributes			=	' class="cbTermsLink"';

						if ( ( $termsOutput == 'text' ) && ( $termsDisplay == 'window' ) ) {
							$termsDisplay	=	'modal';
						}

						if ( $termsDisplay == 'modal' ) {
							// Tooltip height percentage would be based off window height (including scrolling); lets change it to be based off the viewport height:
							$termsHeight	=	( substr( $termsHeight, -1 ) == '%' ? (int) substr( $termsHeight, 0, -1 ) . 'vh' : $termsHeight );

							if ( $termsOutput == 'url' ) {
								$tooltip	=	'<iframe class="d-block m-0 p-0 border-0 cbTermsModalURL" height="100%" width="100%" src="' . htmlspecialchars( $termsURL ) . '"></iframe>';
							} else {
								$tooltip	=	'<div class="cbTermsModalText" style="height:100%;width:100%;overflow:auto;">' . $termsText . '</div>';
							}

							$url			=	'javascript:void(0);';
							$attributes		.=	' ' . cbTooltip( $_CB_framework->getUi(), $tooltip, $termsType, array( $termsWidth, $termsHeight ), null, null, null, 'data-hascbtooltip="true" data-cbtooltip-modal="true"' );
						} else {
							$url			=	htmlspecialchars( $termsURL );
							$attributes		.=	' target="_blank"';
						}

						$return				.=			CBTxt::Th( 'BY_UPLOADING_YOU_CERTIFY_THAT_YOU_HAVE_THE_RIGHT_TO_DISTRIBUTE_THIS_AUDIO_FILE_URL_TERMS', 'By uploading, you certify that you have the right to distribute this audio file and that it does not violate the <a href="[url]"[attributes]>[type]</a>', array( '[url]' => $url, '[attributes]' => $attributes, '[type]' => $termsType ) );
					}
				} else {
					$return					.=			CBTxt::Th( 'BY_UPLOADING_YOU_CERTIFY_THAT_YOU_HAVE_THE_RIGHT_TO_DISTRIBUTE_THIS_AUDIO_FILE', 'By uploading, you certify that you have the right to distribute this audio file.' );
				}
			} else {
				$return						.=			CBTxt::Th( 'BY_UPLOADING_YOU_CERTIFY_THAT_YOU_HAVE_THE_RIGHT_TO_DISTRIBUTE_THIS_AUDIO_FILE', 'By uploading, you certify that you have the right to distribute this audio file.' );
			}

			$return							.=		'</div>'
											.	'</div>';
		}

		if ( $field->params->get( 'audio_allow_links', 1 ) ) {
			$return							.=	'<div id="cbaudiofile_link_' . htmlspecialchars( $fieldName ) . '" class="form-group mb-0 cb_form_line' . ( $hasChoices ? ' mt-3 hidden' : null ) . ' cbAudioFieldLink">';

			$linkField						=	new FieldTable( $field->getDbo() );

			foreach ( array_keys( get_object_vars( $linkField ) ) as $k ) {
				$linkField->set( $k, $field->get( $k ) );
			}

			$linkField->set( 'type', 'text' );
			$linkField->set( 'description', null );

			$user->set( $fieldName, ( ( strpos( $value, '/' ) !== false ) || ( strpos( $value, '\\' ) !== false ) ? $value : null ) );

			$return							.=		parent::getField( $linkField, $user, $output, $reason, 0 );

			$user->set( $fieldName, $value );

			unset( $linkField );

			$return							.=	'</div>';
		}

		return $return;
	}

	/**
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 */
	public function prepareFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		global $_CB_framework, $_CB_database;

		$this->_prepareFieldMetaSave( $field, $user, $postdata, $reason );

		$col					=	$field->get( 'name' );
		$colChoice				=	$col . '__choice';
		$colFile				=	$col . '__file';
		$choice					=	stripslashes( cbGetParam( $postdata, $colChoice ) );

		switch ( $choice ) {
			case 'upload':
				$value			=	( isset( $_FILES[$colFile] ) ? $_FILES[$colFile] : null );

				$this->validate( $field, $user, $choice, $value, $postdata, $reason );
				break;
			case 'link':
				parent::prepareFieldDataSave( $field, $user, $postdata, $reason );
				break;
			case 'delete':
				if ( $user->get( 'id' ) && ( $user->get( $col ) != null ) && ( $user->get( $col ) != '' ) ) {
					if ( isset( $user->$col ) ) {
						$this->_logFieldUpdate( $field, $user, $reason, $user->get( $col ), '' );
					}

					$value		=	$user->get( $col );

					if ( ( strpos( $value, '/' ) === false ) && ( strpos( $value, '\\' ) === false ) ) {
						$this->deleteFiles( $user, $user->get( $col ) );
					}

					$user->set( $col, null );

					// This is needed because user store does not save null:
					if ( $field->get( 'table' ) ) {
						$query	=	'UPDATE ' . $_CB_database->NameQuote( $field->get( 'table' ) )
								.	"\n SET " . $_CB_database->NameQuote( $col ) . " = NULL"
								.	', ' . $_CB_database->NameQuote( 'lastupdatedate' ) . ' = ' . $_CB_database->Quote( $_CB_framework->dateDbOfNow() )
								.	"\n WHERE " . $_CB_database->NameQuote( 'id' ) . " = " . (int) $user->get( 'id' );
						$_CB_database->setQuery( $query );
						$_CB_database->query();
					}
				}
				break;
			default:
					$value		=	$user->get( $col );

					$this->validate( $field, $user, $choice, $value, $postdata, $reason );
				break;
		}
	}

	/**
	 * Mutator:
	 * Prepares field data commit
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save user edit, 'register' for save registration
	 */
	public function commitFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		global $_CB_framework, $_PLUGINS, $_FILES;

		$col						=	$field->get( 'name' );
		$colChoice					=	$col . '__choice';
		$colFile					=	$col . '__file';
		$choice						=	stripslashes( cbGetParam( $postdata, $colChoice ) );

		switch ( $choice ) {
			case 'upload':
				$value				=	( isset( $_FILES[$colFile] ) ? $_FILES[$colFile] : null );

				if ( $this->validate( $field, $user, $choice, $value, $postdata, $reason ) ) {
					$_PLUGINS->loadPluginGroup( 'user' );

					$_PLUGINS->trigger( 'onBeforeUserAudioUpdate', array( &$user, &$value['tmp_name'] ) );

					if ( $_PLUGINS->is_errors() ) {
						$this->_setErrorMSG( $_PLUGINS->getErrorMSG() );
						return;
					}

					$path			=	$_CB_framework->getCfg( 'absolute_path' );
					$indexPath		=	$path . '/components/com_comprofiler/index.html';
					$filesPath		=	$path . '/images/comprofiler/audio';
					$filePath		=	$filesPath . '/' . (int) $user->get( 'id' );

					if ( ! is_dir( $filesPath ) ) {
						$oldmask	=	@umask( 0 );

						if ( @mkdir( $filesPath, 0755, true ) ) {
							@umask( $oldmask );
							@chmod( $filesPath, 0755 );

							if ( ! file_exists( $filesPath . '/index.html' ) ) {
								@copy( $indexPath, $filesPath . '/index.html' );
								@chmod( $filesPath . '/index.html', 0755 );
							}
						} else {
							@umask( $oldmask );
						}
					}

					if ( ! is_dir( $filePath ) ) {
						$oldmask	=	@umask( 0 );

						if ( @mkdir( $filePath, 0755, true ) ) {
							@umask( $oldmask );
							@chmod( $filePath, 0755 );

							if ( ! file_exists( $filePath . '/index.html' ) ) {
								@copy( $indexPath, $filePath . '/index.html' );
								@chmod( $filePath . '/index.html', 0755 );
							}
						} else {
							@umask( $oldmask );
						}
					}

					$uploadedExt	=	strtolower( preg_replace( '/[^-a-zA-Z0-9_]/u', '', pathinfo( $value['name'], PATHINFO_EXTENSION ) ) );
					$newFileName	=	$col . '_' . uniqid( $user->id . '_' ) . '.' . $uploadedExt;

					if ( ! move_uploaded_file( $value['tmp_name'], $filePath . '/'. $newFileName ) ) {
						$this->_setValidationError( $field, $user, $reason, sprintf( CBTxt::T( 'CBAudio-failed to upload audio file: %s' ), $newFileName ) );
						return;
					} else {
						@chmod( $filePath . '/' . $value['tmp_name'], 0755 );
					}

					if ( isset( $user->$col ) ) {
						$this->_logFieldUpdate( $field, $user, $reason, $user->get( $col ), '' );
					}

					if ( isset( $user->$col ) && ( $user->get( $col ) != '' ) ) {
						$this->deleteFiles( $user, $user->get( $col ) );
					}

					$user->set( $col, $newFileName );

					$_PLUGINS->trigger( 'onAfterUserAudioUpdate', array( &$user, $newFileName ) );
				}
				break;
		}
	}

	/**
	 * Mutator:
	 * Prepares field data rollback
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save user edit, 'register' for save registration
	 */
	public function rollbackFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		global $_FILES;

		$col			=	$field->get( 'name' );
		$colChoice		=	$col . '__choice';
		$colFile		=	$col . '__file';

		$choice			=	stripslashes( cbGetParam( $postdata, $colChoice ) );

		switch ( $choice ) {
			case 'upload':
				$value	=	( isset( $_FILES[$colFile] ) ? $_FILES[$colFile] : null );

				if ( $this->validate( $field, $user, $choice, $value, $postdata, $reason ) ) {
					$this->deleteFiles( $user, $user->get( $col ) );
				}
				break;
		}
	}

	/**
	 * Validator:
	 * Validates $value for $field->required and other rules
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user        RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  string      $columnName  Column to validate
	 * @param  string      $value       (RETURNED:) Value to validate, Returned Modified if needed !
	 * @param  array       $postdata    Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason      'edit' for save user edit, 'register' for save registration
	 * @return boolean                  True if validate, $this->_setErrorMSG if False
	 */
	public function validate( &$field, &$user, $columnName, &$value, &$postdata, $reason ) {
		$isRequired							=	$this->_isRequired( $field, $user, $reason );

		$col								=	$field->get( 'name' );
		$colChoice							=	$col . '__choice';
		$choice								=	stripslashes( cbGetParam( $postdata, $colChoice ) );

		switch ( $choice ) {
			case 'upload':
				if ( ! $field->params->get( 'audio_allow_uploads', 1 ) ) {
					$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'UE_NOT_AUTHORIZED', 'You are not authorized to view this page!' ) );
					return false;
				} elseif ( ! isset( $value['tmp_name'] ) || empty( $value['tmp_name'] ) || ( $value['error'] != 0 ) || ( ! is_uploaded_file( $value['tmp_name'] ) ) ) {
					if ( $isRequired ) {
						$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'Please select a audio file before uploading' ) );
					}

					return false;
				} else {
					$uploadSizeLimitMax		=	$field->params->get( 'fieldValidateAudio_sizeMax', 1024 );
					$uploadSizeLimitMin		=	$field->params->get( 'fieldValidateAudio_sizeMin', 0 );
					$uploadExtensionLimit	=	$this->allowedExtensions();
					$uploadedExt			=	strtolower( preg_replace( '/[^-a-zA-Z0-9_]/u', '', pathinfo( $value['name'], PATHINFO_EXTENSION ) ) );

					if ( ( ! $uploadedExt ) || ( ! in_array( $uploadedExt, $uploadExtensionLimit ) ) ) {
						$this->_setValidationError( $field, $user, $reason, sprintf( CBTxt::T( 'Please upload only %s' ), implode( ', ', $uploadExtensionLimit ) ) );
						return false;
					}

					$uploadedSize			=	$value['size'];

					if ( ( $uploadedSize / 1024 ) > $uploadSizeLimitMax ) {
						$this->_setValidationError( $field, $user, $reason, sprintf( CBTxt::T( 'The audio file size exceeds the maximum of %s' ), $this->formattedFileSize( $uploadSizeLimitMax * 1024 ) ) );
						return false;
					}

					if ( ( $uploadedSize / 1024 ) < $uploadSizeLimitMin ) {
						$this->_setValidationError( $field, $user, $reason, sprintf( CBTxt::T( 'The audio file is too small, the minimum is %s' ), $this->formattedFileSize( $uploadSizeLimitMin * 1024 ) ) );
						return false;
					}
				}
				break;
			case 'link':
				if ( ! $field->params->get( 'audio_allow_links', 1 ) ) {
					$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'UE_NOT_AUTHORIZED', 'You are not authorized to view this page!' ) );
					return false;
				}

				$validated					=	parent::validate( $field, $user, $columnName, $value, $postdata, $reason );

				if ( $validated && ( $value !== '' ) && ( $value !== null ) ) {
					$linkExists				=	false;

					try {
						$request			=	new \GuzzleHttp\Client();

						$header				=	$request->head( $value );

						if ( ( $header !== false ) && ( $header->getStatusCode() == 200 ) ) {
							$linkExists		=	true;
						}
					} catch( Exception $e ) {}

					if ( ! $linkExists ) {
						$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'Please input a audio file url before linking' ) );
						return false;
					}

					$linkExtLimit			=	$this->allowedExtensions();
					$linkExt				=	strtolower( pathinfo( $value, PATHINFO_EXTENSION ) );

					if ( ( ! $linkExt ) || ( ! in_array( $linkExt, $linkExtLimit ) ) ) {
						$this->_setValidationError( $field, $user, $reason, sprintf( CBTxt::T( 'Please link only %s' ), implode( ', ', $linkExtLimit ) ) );
						return false;
					}
				}

				return $validated;
				break;
			default:
				$valCol						=	$field->get( 'name' );

				if ( $isRequired && ( ( ! $user ) || ( ! isset( $user->$valCol ) ) || ( ! $user->get( $valCol ) ) ) ) {
					if ( ! $value ) {
						$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'UE_FIELDREQUIRED', 'This Field is required' ) );
						return false;
					}
				}
				break;
		}

		return true;
	}

	/**
	 * Deletes file from users folder
	 *
	 * @param  UserTable  $user
	 * @param  string     $file
	 */
	function deleteFiles( $user, $file = null ) {
		global $_CB_framework;

		if ( ! is_object( $user ) ) {
			return;
		}

		$filePath	=	$_CB_framework->getCfg( 'absolute_path' ) . '/images/comprofiler/audio/' . (int) $user->id . '/';

		if ( ! is_dir( $filePath ) ) {
			return;
		}

		if ( ! $file ) {
			if ( false !== ( $handle = opendir( $filePath ) ) ) {
				while ( false !== ( $file = readdir( $handle ) ) ) {
					if ( $file && ( ( $file != '.' ) && ( $file != '..' ) ) ) {
						@unlink( $filePath . $file );
					}
				}
				closedir( $handle );
			}

			if ( is_dir( $filePath ) ) {
				@rmdir( $filePath );
			}
		} else {
			if ( file_exists( $filePath . $file ) ) {
				@unlink( $filePath . $file );
			}
		}
	}

	/**
	 * returns the mimetype of the supplied file or link
	 *
	 * @param  string  $value
	 * @return string
	 */
	function getMimeType( $value ) {
		$domain			=	preg_replace( '/^(?:(?:\w+\.)*)?(\w+)\..+$/', '\1', parse_url( $value, PHP_URL_HOST ) );
		$extension		=	strtolower( pathinfo( ( $domain ? $value : preg_replace( '/[^-a-zA-Z0-9_.]/u', '', $value ) ), PATHINFO_EXTENSION ) );

		if ( $extension == 'mp3' ) {
			return 'audio/mp3';
		}

		if ( $extension == 'm4a' ) {
			return 'audio/mp4';
		}

		return cbGetMimeFromExt( $extension );
	}

	/**
	 * outputs a secure list of allowed file extensions
	 *
	 * @return array
	 */
	private function allowedExtensions() {
		return array( 'mp3', 'oga', 'ogg', 'weba', 'wav', 'm4a' );
	}

	/**
	 * Returns file size formatted from bytes
	 *
	 * @param int $bytes
	 * @return string
	 */
	private function formattedFileSize( $bytes )
	{
		if ( $bytes >= 1099511627776 ) {
			$size	=	CBTxt::Th( 'FILESIZE_FORMATTED_TB', '%%COUNT%% TB|%%COUNT%% TBs', array( '%%COUNT%%' => (float) number_format( $bytes / 1099511627776, 2, '.', '' ) ) );
		} elseif ( $bytes >= 1073741824 ) {
			$size	=	CBTxt::Th( 'FILESIZE_FORMATTED_GB', '%%COUNT%% GB|%%COUNT%% GBs', array( '%%COUNT%%' => (float) number_format( $bytes / 1073741824, 2, '.', '' ) ) );
		} elseif ( $bytes >= 1048576 ) {
			$size	=	CBTxt::Th( 'FILESIZE_FORMATTED_MB', '%%COUNT%% MB|%%COUNT%% MBs', array( '%%COUNT%%' => (float) number_format( $bytes / 1048576, 2, '.', '' ) ) );
		} elseif ( $bytes >= 1024 ) {
			$size	=	CBTxt::Th( 'FILESIZE_FORMATTED_KB', '%%COUNT%% KB|%%COUNT%% KBs', array( '%%COUNT%%' => (float) number_format( $bytes / 1024, 2, '.', '' ) ) );
		} else {
			$size	=	CBTxt::Th( 'FILESIZE_FORMATTED_B', '%%COUNT%% B|%%COUNT%% Bs', array( '%%COUNT%%' => (float) number_format( $bytes, 2, '.', '' ) ) );
		}

		return $size;
	}
}

class CBfield_rating extends cbFieldHandler {

	/**
	 * Checks if user has vote access to this field
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  null|int    $myId
	 * @return boolean
	 */
	private function getVoteAccess( &$field, &$user, $myId = null ) {
		global $_CB_framework;

		static $cache					=	array();

		if ( $myId === null ) {
			$myId						=	(int) $_CB_framework->myId();
		} else {
			$myId						=	(int) $myId;
		}

		$userId							=	(int) $user->get( 'id' );
		$fieldId						=	(int) $field->get( 'fieldid' );

		$cacheId						=	$myId . $userId . $fieldId;

		if ( ! isset( $cache[$cacheId] ) ) {
			$ratingAccess				=	(int) $field->params->get( 'rating_access', 1 );
			$excludeSelf				=	(int) $field->params->get( 'rating_access_exclude', 0 );
			$includeSelf				=	(int) $field->params->get( 'rating_access_include', 0 );
			$viewAccessLevel			=	(int) $field->params->get( 'rating_access_custom', 1 );
			$access						=	false;

			switch ( $ratingAccess ) {
				case 8:
					if ( Application::MyUser()->canViewAccessLevel( $viewAccessLevel ) && ( ( ( $userId == $myId ) && ( ! $excludeSelf ) ) || ( $userId != $myId ) ) ) {
						$access			=	true;
					}
					break;
				case 7:
					if ( Application::MyUser()->isModeratorFor( Application::User( (int) $userId ) ) && ( ( ( $userId == $myId ) && ( ! $excludeSelf ) ) || ( $userId != $myId ) ) ) {
						$access			=	true;
					}
					break;
				case 6:
					if ( $userId != $myId ) {
						$cbConnection	=	new cbConnection( $userId );

						if ( $cbConnection->getConnectionDetails( $userId, $myId ) !== false ) {
							$access		=	true;
						}
					} elseif ( ( $userId == $myId ) && $includeSelf ) {
						$access			=	true;
					}
					break;
				case 5:
					if ( ( $myId == 0 ) && ( $userId != $myId ) || ( ( $userId == $myId ) && $includeSelf ) ) {
						$access			=	true;
					}
					break;
				case 4:
					if ( ( $myId > 0 ) && ( ( ( $userId == $myId ) && ( ! $excludeSelf ) ) || ( $userId != $myId ) ) ) {
						$access			=	true;
					}
					break;
				case 3:
					if ( $userId != $myId ) {
						$access			=	true;
					}
					break;
				case 2:
					if ( $userId == $myId ) {
						$access			=	true;
					}
					break;
				case 1:
				default:
					if ( ( ( $userId == $myId ) && ( ! $excludeSelf ) ) || ( $userId != $myId ) ) {
						$access			=	true;
					}
					break;
			}

			$cache[$cacheId]			=	$access;
		}

		return $cache[$cacheId];
	}

	/**
	 * Get viewing users current vote
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  null|int    $myId
	 * @return int
	 */
	private function getCurrentVote( &$field, &$user, $myId = null ) {
		global $_CB_database, $_CB_framework;

		static $cache				=	array();

		if ( $myId === null ) {
			$myId					=	(int) $_CB_framework->myId();
		} else {
			$myId					=	(int) $myId;
		}

		$userId						=	(int) $user->get( 'id' );
		$fieldId					=	(int) $field->get( 'fieldid' );
		$ipAddresses				=	cbGetIParray();
		$ipAddress					=	trim( array_shift( $ipAddresses ) );

		$cacheId					=	md5( ( $myId == 0 ? $ipAddress : $myId ) . $userId . $fieldId );

		if ( ! isset( $cache[$cacheId] ) ) {
			$query					=	'SELECT ' . $_CB_database->NameQuote( 'rating' )
									.	"\n FROM " . $_CB_database->NameQuote( '#__comprofiler_ratings' )
									.	"\n WHERE " . $_CB_database->NameQuote( 'type' ) . " = " . $_CB_database->Quote( 'field' )
									.	"\n AND " . $_CB_database->NameQuote( 'item' ) . " = " . $fieldId
									.	"\n AND " . $_CB_database->NameQuote( 'target' ) . " = " . $userId
									.	"\n AND " . $_CB_database->NameQuote( 'user_id' ) . " = " . $myId;
			if ( $myId == 0 ) {
				$query				.=	"\n AND " . $_CB_database->NameQuote( 'ip_address' ) . " = " . $_CB_database->Quote( $ipAddress );
			}
			$_CB_database->setQuery( $query );
			$cache[$cacheId]		=	$_CB_database->loadResult();
		}

		return $cache[$cacheId];
	}

	/**
	 * Inserts a new vote into the database
	 *
	 * @param  float       $value
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  null|int    $myId
	 * @return float
	 */
	private function insertVote( $value, &$field, &$user, $myId = null ) {
		global $_CB_database, $_CB_framework;

		if ( $myId === null ) {
			$myId			=	(int) $_CB_framework->myId();
		} else {
			$myId			=	(int) $myId;
		}

		$userId				=	(int) $user->get( 'id' );
		$fieldId			=	(int) $field->get( 'fieldid' );
		$ipAddresses		=	cbGetIParray();
		$ipAddress			=	trim( array_shift( $ipAddresses ) );

		if ( ! $value ) {
			$query			=	'DELETE'
							.	"\n FROM " . $_CB_database->NameQuote( '#__comprofiler_ratings' )
							.	"\n WHERE " . $_CB_database->NameQuote( 'type' ) . " = " . $_CB_database->Quote( 'field' )
							.	"\n AND " . $_CB_database->NameQuote( 'item' ) . " = " . $fieldId
							.	"\n AND " . $_CB_database->NameQuote( 'target' ) . " = " . $userId
							.	"\n AND " . $_CB_database->NameQuote( 'user_id' ) . " = " . $myId;
			if ( $myId == 0 ) {
				$query		.=	"\n AND " . $_CB_database->NameQuote( 'ip_address' ) . " = " . $_CB_database->Quote( $ipAddress );
			}
			$_CB_database->setQuery( $query );
			$_CB_database->query();
		} else {
			$query			=	'SELECT ' . $_CB_database->NameQuote( 'id' )
							.	"\n FROM " . $_CB_database->NameQuote( '#__comprofiler_ratings' )
							.	"\n WHERE " . $_CB_database->NameQuote( 'type' ) . " = " . $_CB_database->Quote( 'field' )
							.	"\n AND " . $_CB_database->NameQuote( 'item' ) . " = " . $fieldId
							.	"\n AND " . $_CB_database->NameQuote( 'target' ) . " = " . $userId
							.	"\n AND " . $_CB_database->NameQuote( 'user_id' ) . " = " . $myId;
			if ( $myId == 0 ) {
				$query		.=	"\n AND " . $_CB_database->NameQuote( 'ip_address' ) . " = " . $_CB_database->Quote( $ipAddress );
			}
			$_CB_database->setQuery( $query );
			$ratingId		=	$_CB_database->loadResult();

			if ( $ratingId ) {
				$query		=	'UPDATE ' . $_CB_database->NameQuote( '#__comprofiler_ratings' )
							.	"\n SET " . $_CB_database->NameQuote( 'rating' ) . " = " . (float) $value
							.	', ' . $_CB_database->NameQuote( 'ip_address' ) . " = " . $_CB_database->Quote( $ipAddress )
							.	', ' . $_CB_database->NameQuote( 'date' ) . ' = ' . $_CB_database->Quote( $_CB_framework->getUTCDate() )
							.	"\n WHERE " . $_CB_database->NameQuote( 'id' ) . " = " . (int) $ratingId;
				$_CB_database->setQuery( $query );
				$_CB_database->query();
			} else {
				$query		=	'INSERT INTO ' . $_CB_database->NameQuote( '#__comprofiler_ratings' )
							.	"\n ("
							.		$_CB_database->NameQuote( 'user_id' )
							.		', ' . $_CB_database->NameQuote( 'type' )
							.		', ' . $_CB_database->NameQuote( 'item' )
							.		', ' . $_CB_database->NameQuote( 'target' )
							.		', ' . $_CB_database->NameQuote( 'rating' )
							.		', ' . $_CB_database->NameQuote( 'ip_address' )
							.		', ' . $_CB_database->NameQuote( 'date' )
							.	')'
							.	"\n VALUES ("
							.		$myId
							.		', ' . $_CB_database->Quote( 'field' )
							.		', ' . $fieldId
							.		', ' . $userId
							.		', ' . (float) $value
							.		', ' . $_CB_database->Quote( $ipAddress )
							.		', ' . $_CB_database->Quote( $_CB_framework->getUTCDate() )
							.	')';
				$_CB_database->setQuery( $query );
				$_CB_database->query();
			}
		}

		$query				=	'SELECT ROUND( AVG( ' . $_CB_database->NameQuote( 'rating' ) . ' ), 1 )'
							.	"\n FROM " . $_CB_database->NameQuote( '#__comprofiler_ratings' )
							.	"\n WHERE " . $_CB_database->NameQuote( 'type' ) . " = " . $_CB_database->Quote( 'field' )
							.	"\n AND " . $_CB_database->NameQuote( 'item' ) . " = " . $fieldId
							.	"\n AND " . $_CB_database->NameQuote( 'target' ) . " = " . $userId;
		$_CB_database->setQuery( $query );

		return $_CB_database->loadResult();
	}

	/**
	 * Get the number of a fields votes
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @return mixed
	 */
	private function getVoteCount( &$field, &$user ) {
		global $_CB_database;

		static $cache				=	array();

		$userId						=	(int) $user->get( 'id' );
		$fieldId					=	(int) $field->get( 'fieldid' );

		$cacheId					=	$userId . $fieldId;

		if ( ! isset( $cache[$cacheId] ) ) {
			$query					=	'SELECT COUNT(*)'
									.	"\n FROM " . $_CB_database->NameQuote( '#__comprofiler_ratings' )
									.	"\n WHERE " . $_CB_database->NameQuote( 'type' ) . " = " . $_CB_database->Quote( 'field' )
									.	"\n AND " . $_CB_database->NameQuote( 'item' ) . " = " . $fieldId
									.	"\n AND " . $_CB_database->NameQuote( 'target' ) . " = " . $userId;
			$_CB_database->setQuery( $query );
			$cache[$cacheId]		=	(int) $_CB_database->loadResult();
		}

		return $cache[$cacheId];
	}

	/**
	 * output rating field html display
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $reason
	 * @return string
	 */
	private function getRatingHTML( &$field, &$user, $reason ) {
		global $_CB_framework;

		static $JS_loaded			=	0;

		$userId						=	(int) $user->get( 'id' );
		$fieldName					=	$field->get( 'name' );

		if ( in_array( $reason, array( 'edit', 'register' ) ) && ( (int) $_CB_framework->myId() != $userId ) ) {
			$myId					=	$userId;
		} else {
			$myId					=	null;
		}

		$value						=	(float) $user->get( $fieldName );

		$readOnly					=	$this->_isReadOnly( $field, $user, $reason );
		$required					=	$this->_isRequired( $field, $user, $reason );

		$maxRating					=	(int) $field->params->get( 'rating_number', 5 );
		$voteCount					=	(int) $field->params->get( 'rating_votes', 0 );
		$voteNumerical				=	(int) $field->params->get( 'rating_numerical', 0 );
		$ratingStep					=	(float) number_format( $field->params->get( 'rating_step', '1.0' ), 1, '.', '' );
		$forceWhole					=	(int) $field->params->get( 'rating_whole', 0 );
		$userlistVote				=	(int) $field->params->get( 'rating_list', 0 );
		$userlistAccess				=	false;

		if ( ! $ratingStep ) {
			$ratingStep				=	(float) '1.0';
		}

		if ( $reason == 'list' ) {
			$fieldName				=	$fieldName . $userId;

			if ( $userlistVote ) {
				$userlistAccess		=	true;
			}
		}

		$canVote					=	( ( ! $readOnly ) && $this->getVoteAccess( $field, $user, $myId ) && ( ( ( $reason == 'list' ) && $userlistAccess ) || ( $reason != 'list' ) ) );

		if ( $forceWhole ) {
			$value					=	(float) round( $value );
		}

		if ( $value > $maxRating ) {
			$value					=	(float) $maxRating;
		} elseif ( $value < 0 ) {
			$value					=	(float) '0';
		}

		$return						=	null;

		if ( ( ! in_array( $reason, array( 'edit', 'register' ) ) ) && ( $value || ( ( ! $value ) && ( ! $canVote ) ) ) ) {
			$return					.=		'<div id="' . $fieldName . 'Total" class="cbRatingFieldTotal">'
									.			'<div class="rateit" data-rateit-value="' . $value . '" data-rateit-ispreset="true" data-rateit-readonly="true" data-rateit-min="0" data-rateit-max="' . $maxRating . '"></div>';

			if ( $voteNumerical && $value ) {
				$return				.=			' <span class="cbRatingFieldNumerical" title="' . htmlspecialchars( CBTxt::T( 'Rating' ) ) . '"><small>(' . $value . ')</small></span>';
			}

			if ( $voteCount ) {
				$count				=	$this->getVoteCount( $field, $user );

				if ( $count ) {
					$return			.=			' <span class="cbRatingFieldCount" title="' . htmlspecialchars( CBTxt::T( 'Number of Votes' ) ) . '"><small>(' . $count . ')</small></span>';
				}
			}

			$return					.=		'</div>';
		}

		if ( in_array( $reason, array( 'edit', 'register' ) ) && ( (int) $_CB_framework->myId() != $userId ) ) {
			$myId					=	$userId;
		} else {
			$myId					=	null;
		}

		if ( $canVote ) {
			$rating					=	(float) $this->getCurrentVote( $field, $user, $myId );

			if ( $rating > $maxRating ) {
				$rating				=	(float) $maxRating;
			} elseif ( $rating < 0 ) {
				$rating				=	(float) '0';
			}

			$return					.=		'<div id="' . $fieldName . 'Rating" class="cbRatingFieldRating">'
									.			'<input type="hidden" id="' . $fieldName . '" name="' . $fieldName . '" value="' . $rating . '" />'
									.			'<div class="rateit" data-field="' . $field->get( 'name' ) . '" data-target="' . $userId . '" data-rateit-backingfld="#' . $fieldName . '" data-rateit-step="' . $ratingStep . '" data-rateit-value="' . $rating . '" data-rateit-ispreset="true" data-rateit-resetable="' . ( $required ? 'false' : 'true' ) . '" data-rateit-min="0" data-rateit-max="' . $maxRating . '"></div>'
									.		'</div>';
		}

		if ( $return ) {
			$return					=	'<div id="' . $fieldName . 'Container" class="cbRatingField' . ( ! in_array( $reason, array( 'edit', 'register' ) ) ? ' cbRatingFieldToggle' : null ) . ( $userlistAccess ? ' cbClicksInside' : null ) . '">'
									.		$return
									.	'</div>';
		}

		$js							=	null;

		if ( ! in_array( $reason, array( 'edit', 'register' ) ) ) {
			if ( ! $JS_loaded++ ) {
				cbGetRegAntiSpamInputTag();

				$cbGetRegAntiSpams	=	cbGetRegAntiSpams();

				$js					=	"$( '.cbRatingFieldToggle' ).on( 'rated reset', '.rateit', function ( e ) {"
									.		"var rating = $( this ).parents( '.cbRatingField' );"
									.		"var vote = $( this ).rateit( 'value' );"
									.		"var field = $( this ).data( 'field' );"
									.		"var target = $( this ).data( 'target' );"
									.		"$.ajax({"
									.			"type: 'POST',"
									.			"url: '" . addslashes( cbSef( 'index.php?option=com_comprofiler&view=fieldclass&function=savevalue&reason=' . urlencode( $reason ), false, 'raw' ) ) . "',"
									.			"data: {"
									.				"field: field,"
									.				"user: target,"
									.				"value: vote,"
									.				cbSpoofField() . ": '" . addslashes( cbSpoofString( null, 'fieldclass' ) ) . "',"
									.				cbGetRegAntiSpamFieldName() . ": '" . addslashes( $cbGetRegAntiSpams[0] ) . "'"
									.			"}"
									.		"}).done( function( data, textStatus, jqXHR ) {"
									.			"rating.find( '.cbRatingFieldTotal,.alert' ).remove();"
									.			"rating.prepend( data );"
									.			"rating.find( '.cbRatingFieldTotal .rateit' ).rateit();"
									.		"});"
									.	"});";
			}
		}

		// Still need the plugin loaded so the rating stars get styled:
		$_CB_framework->outputCbJQuery( $js, 'rateit' );

		return $return;
	}

	/**
	 * Direct access to field for custom operations, like for Ajax
	 *
	 * WARNING: direct unchecked access, except if $user is set, then check well for the $reason ...
	 *
	 * @param FieldTable     $field
	 * @param null|UserTable $user
	 * @param array          $postdata
	 * @param string         $reason 'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'search' for searches (always public!)
	 * @return string                  Expected output.
	 */
	public function fieldClass( &$field, &$user, &$postdata, $reason ) {
		parent::fieldClass( $field, $user, $postdata, $reason ); // Performs spoof check

		if ( ! $user ) {
			return null;
		}

		$userId							=	(int) $user->get( 'id' );

		if ( ( ! in_array( $reason, array( 'profile', 'list' ) ) ) || ( cbGetParam( $_GET, 'function', '' ) != 'savevalue' ) || ( ! $userId ) || $this->_isReadOnly( $field, $user, $reason ) || ( ! $this->getVoteAccess( $field, $user ) ) ) {
			return null; // wrong reason, wrong function, user doesn't exist, field is read only, or user has no vote access; do nothing
		}

		$fieldName						=	$field->get( 'name' );
		$maxRating						=	(int) $field->params->get( 'rating_number', 5 );
		$voteCount						=	(int) $field->params->get( 'rating_votes', 0 );
		$voteNumerical					=	(int) $field->params->get( 'rating_numerical', 0 );
		$forceWhole						=	(int) $field->params->get( 'rating_whole', 0 );
		$value							=	(float) stripslashes( cbGetParam( $postdata, 'value' ) );

		if ( $value > $maxRating ) {
			$value						=	(float) $maxRating;
		} elseif ( $value < 0 ) {
			$value						=	(float) '0';
		}

		$postdata[$fieldName]			=	$value;

		if ( $this->validate( $field, $user, $fieldName, $value, $postdata, $reason ) && ( (float) $this->getCurrentVote( $field, $user ) !== (float) $value ) ) {
			$value						=	(float) $this->insertVote( $value, $field, $user );

			if ( $user->storeDatabaseValue( $fieldName, $value ) ) {
				$this->_logFieldUpdate( $field, $user, $reason, (float) $user->get( $fieldName ), $value );

				$user->set( $fieldName, $value );
			}
		}

		$value							=	(float) $user->get( $fieldName );

		if ( $reason == 'list' ) {
			$fieldName					=	$fieldName . $userId;
		}

		if ( $forceWhole ) {
			$value						=	(float) round( $value );
		}

		if ( $value > $maxRating ) {
			$value						=	(float) $maxRating;
		} elseif ( $value < 0 ) {
			$value						=	(float) '0';
		}

		$return							=	null;

		if ( $value ) {
			$return						.=	'<div id="' . $fieldName . 'Total" class="cbRatingFieldTotal">'
										.		'<div class="rateit" data-rateit-value="' . $value . '" data-rateit-ispreset="true" data-rateit-readonly="true" data-rateit-min="0" data-rateit-max="' . $maxRating . '"></div>';

			if ( $voteNumerical && $value ) {
				$return					.=		' <span class="cbRatingFieldNumerical" title="' . htmlspecialchars( CBTxt::T( 'Rating' ) ) . '"><small>(' . $value . ')</small></span>';
			}

			if ( $voteCount ) {
				$count					=	$this->getVoteCount( $field, $user );

				if ( $count ) {
					$return				.=		' <span class="cbRatingFieldCount" title="' . htmlspecialchars( CBTxt::T( 'Number of Votes' ) ) . '"><small>(' . $count . ')</small></span>';
				}
			}

			$return						.=	'</div>';
		}

		return $return;
	}

	/**
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		$return						=	null;

		switch ( $output ) {
			case 'html':
			case 'htmledit':
				if ( $reason == 'search' ) {
					$fieldName		=	$field->get( 'name' );
					$minNam			=	$fieldName . '__minval';
					$maxNam			=	$fieldName . '__maxval';

					$minVal			=	$user->get( $minNam );
					$maxVal			=	$user->get( $maxNam );

					$field->set( 'name', $minNam );

					$minHtml		=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', 'text', $minVal, null );

					$field->set( 'name', $maxNam );

					$maxHtml		=	$this->_fieldEditToHtml( $field, $user, $reason, 'input', 'text', $maxVal, null );

					$field->set( 'name', $fieldName );

					$return			=	$this->_fieldSearchRangeModeHtml( $field, $user, $output, $reason, null, $minHtml, $maxHtml, $list_compare_types );
				} else {
					$return			=	$this->formatFieldValueLayout( $this->getRatingHTML( $field, $user, $reason ), $reason, $field, $user );
				}
				break;
			default:
				$return				=	parent::getField( $field, $user, $output, $reason, $list_compare_types );
				break;
		}

		return $return;
	}

	/**
	 * Mutator:
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save user edit, 'register' for save registration
	 */
	public function prepareFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		$maxRating				=	(int) $field->params->get( 'rating_number', 5 );

		$this->_prepareFieldMetaSave( $field, $user, $postdata, $reason );

		foreach ( $field->getTableColumns() as $col ) {
			$value				=	cbGetParam( $postdata, $col );

			if ( ( $value !== null ) && ( ! is_array( $value ) ) ) {
				$value			=	(float) stripslashes( $value );

				if ( $value > $maxRating ) {
					$value		=	(float) $maxRating;
				} elseif ( $value < 0 ) {
					$value		=	(float) '0';
				}

				$this->validate( $field, $user, $col, $value, $postdata, $reason );
			}
		}
	}

	/**
	 * Mutator:
	 * Prepares field data commit
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save user edit, 'register' for save registration
	 */
	public function commitFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		global $_CB_framework;

		$maxRating				=	(int) $field->params->get( 'rating_number', 5 );

		foreach ( $field->getTableColumns() as $col ) {
			$value				=	cbGetParam( $postdata, $col );

			if ( ( $value !== null ) && ( ! is_array( $value ) ) ) {
				$value			=	(float) stripslashes( $value );

				if ( $value > $maxRating ) {
					$value		=	(float) $maxRating;
				} elseif ( $value < 0 ) {
					$value		=	(float) '0';
				}

				if ( $this->validate( $field, $user, $col, $value, $postdata, $reason ) && ( (float) $this->getCurrentVote( $field, $user ) !== (float) $value ) ) {
					$userId		=	(int) $user->get( 'id' );

					if ( in_array( $reason, array( 'edit', 'register' ) ) && ( (int) $_CB_framework->myId() != $userId ) ) {
						$myId	=	$userId;
					} else {
						$myId	=	null;
					}

					$rating		=	(float) $this->insertVote( $value, $field, $user, $myId );

					$this->_logFieldUpdate( $field, $user, $reason, $user->get( $col ), $rating );

					$user->set( $col, $rating );
				}
			}
		}
	}

	/**
	 * Finder:
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $searchVals  RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata    Typically $_POST (but not necessarily), filtering required.
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @param  string      $reason      'edit' for save profile edit, 'register' for registration, 'search' for searches
	 * @return cbSqlQueryPart[]
	 */
	public function bindSearchCriteria( &$field, &$searchVals, &$postdata, $list_compare_types, $reason ) {
		$query								=	array();

		foreach ( $field->getTableColumns() as $col ) {
			$minNam							=	$col . '__minval';
			$maxNam							=	$col . '__maxval';
			$searchMode						=	$this->_bindSearchRangeMode( $field, $searchVals, $postdata, $minNam, $maxNam, $list_compare_types );

			if ( $searchMode ) {
				$minVal						=	(float) cbGetParam( $postdata, $minNam, 0 );
				$maxVal						=	(float) cbGetParam( $postdata, $maxNam, 0 );

				if ( $minVal && ( cbGetParam( $postdata, $minNam, '' ) !== '' ) ) {
					$searchVals->$minNam	=	$minVal;
					$operator				=	( $searchMode == 'isnot' ? ( $minVal == $maxVal ? '<' : '<=' ) : '>=' );
					$min					=	$this->_floatToSql( $field, $col, $minVal, $operator, $searchMode );
				} else {
					$min					=	null;
				}

				if ( $maxVal && ( cbGetParam( $postdata, $maxNam, '' ) !== '' ) ) {
					$searchVals->$maxNam	=	$maxVal;
					$operator				=	( $searchMode == 'isnot' ? ( $maxVal == $minVal ? '>' : '>=' ) : '<=' );
					$max					=	$this->_floatToSql( $field, $col, $maxVal, $operator, $searchMode );
				} else {
					$max					=	null;
				}

				if ( $min && $max ) {
					$sql					=	new cbSqlQueryPart();
					$sql->tag				=	'column';
					$sql->name				=	$col;
					$sql->table				=	$field->table;
					$sql->type				=	'sql:operator';
					$sql->operator			=	( $searchMode == 'isnot' ? 'OR' : 'AND' );
					$sql->searchmode		=	$searchMode;

					$sql->addChildren( array( $min, $max ) );

					$query[]				=	$sql;
				} elseif ( $min ) {
					$query[]				=	$min;
				} elseif ( $max ) {
					$query[]				=	$max;
				}
			}
		}

		return $query;
	}

	/**
	 * Internal function to create an SQL query part based on a comparison operator
	 *
	 * @param  FieldTable      $field
	 * @param  string          $col
	 * @param  int             $value
	 * @param  string          $operator
	 * @param  string          $searchMode
	 * @return cbSqlQueryPart
	 */
	protected function _floatToSql( &$field, $col, $value, $operator, $searchMode ) {
		$value				=	(float) $value;

		$sql				=	new cbSqlQueryPart();
		$sql->tag			=	'column';
		$sql->name			=	$col;
		$sql->table			=	$field->table;
		$sql->type			=	'sql:field';
		$sql->operator		=	$operator;
		$sql->value			=	$value;
		$sql->valuetype		=	'const:float';
		$sql->searchmode	=	$searchMode;

		return $sql;
	}
}

class CBfield_points extends CBfield_integer
{
	/**
	 * Checks if user has increment access to this field
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @return boolean
	 */
	private function getIncrementAccess( &$field, &$user ) {
		global $_CB_framework, $_CB_database;

		static $cache					=	array();

		$myId							=	(int) $_CB_framework->myId();
		$userId							=	(int) $user->get( 'id' );
		$fieldId						=	(int) $field->get( 'fieldid' );
		$ipAddresses					=	cbGetIParray();
		$ipAddress						=	trim( array_shift( $ipAddresses ) );

		$incrementDelay					=	$field->params->get( 'points_inc_delay', null );
		$customDelay					=	$field->params->get( 'points_inc_delay_custom', null );

		$cacheId						=	$myId . $userId . $fieldId;

		if ( ! isset( $cache[$cacheId] ) ) {
			$ratingAccess				=	(int) $field->params->get( 'points_access', 1 );
			$excludeSelf				=	(int) $field->params->get( 'points_access_exclude', 0 );
			$includeSelf				=	(int) $field->params->get( 'points_access_include', 0 );
			$viewAccessLevel			=	(int) $field->params->get( 'points_access_custom', 1 );
			$access						=	false;

			switch ( $ratingAccess ) {
				case 8:
					if ( Application::MyUser()->canViewAccessLevel( $viewAccessLevel ) && ( ( ( $userId == $myId ) && ( ! $excludeSelf ) ) || ( $userId != $myId ) ) ) {
						$access			=	true;
					}
					break;
				case 7:
					if ( Application::MyUser()->isModeratorFor( Application::User( (int) $userId ) ) && ( ( ( $userId == $myId ) && ( ! $excludeSelf ) ) || ( $userId != $myId ) ) ) {
						$access			=	true;
					}
					break;
				case 6:
					if ( $userId != $myId ) {
						$cbConnection	=	new cbConnection( $userId );

						if ( $cbConnection->getConnectionDetails( $userId, $myId ) !== false ) {
							$access		=	true;
						}
					} else if ( ( $userId == $myId ) && $includeSelf ) {
						$access			=	true;
					}
					break;
				case 5:
					if ( ( $myId == 0 ) && ( $userId != $myId ) || ( ( $userId == $myId ) && $includeSelf ) ) {
						$access			=	true;
					}
					break;
				case 4:
					if ( ( $myId > 0 ) && ( ( ( $userId == $myId ) && ( ! $excludeSelf ) ) || ( $userId != $myId ) ) ) {
						$access			=	true;
					}
					break;
				case 3:
					if ( $userId != $myId ) {
						$access			=	true;
					}
					break;
				case 2:
					if ( $userId == $myId ) {
						$access			=	true;
					}
					break;
				case 1:
				default:
					if ( ( ( $userId == $myId ) && ( ! $excludeSelf ) ) || ( $userId != $myId ) ) {
						$access			=	true;
					}
					break;
			}

			$cache[$cacheId]			=	$access;
		}

		$canAccess						=	$cache[$cacheId];

		if ( $canAccess && $incrementDelay ) {
			$query						=	'SELECT ' . $_CB_database->NameQuote( 'date' )
										.	"\n FROM " . $_CB_database->NameQuote( '#__comprofiler_ratings' )
										.	"\n WHERE " . $_CB_database->NameQuote( 'type' ) . " = " . $_CB_database->Quote( 'field' )
										.	"\n AND " . $_CB_database->NameQuote( 'item' ) . " = " . $fieldId
										.	"\n AND " . $_CB_database->NameQuote( 'target' ) . " = " . $userId
										.	"\n AND " . $_CB_database->NameQuote( 'user_id' ) . " = " . $myId;
			if ( $myId == 0 ) {
				$query					.=	"\n AND " . $_CB_database->NameQuote( 'ip_address' ) . " = " . $_CB_database->Quote( $ipAddress );
			}
			$query						.=	"\n ORDER BY " . $_CB_database->NameQuote( 'date' ) . " DESC";
			$_CB_database->setQuery( $query, 0, 1 );
			$incrementDate				=	$_CB_database->loadResult();

			if ( $incrementDate ) {
				if ( $incrementDelay == 'FOREVER' ) {
					$canAccess			=	false;
				} elseif ( $incrementDelay == 'CUSTOM' ) {
					if ( $customDelay && ( $_CB_framework->getUTCTimestamp( strtoupper( $customDelay ), $_CB_framework->getUTCTimestamp( $incrementDate ) ) >= $_CB_framework->getUTCNow() ) ) {
						$canAccess		=	false;
					}
				} elseif ( $_CB_framework->getUTCTimestamp( $incrementDelay, $_CB_framework->getUTCTimestamp( $incrementDate ) ) >= $_CB_framework->getUTCNow() ) {
					$canAccess			=	false;
				}
			}
		}

		return $canAccess;
	}

	/**
	 * output points field html display
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $reason
	 * @param  boolean     $ajax
	 * @return string
	 */
	private function getPointsHTML( &$field, &$user, $reason, $ajax = false ) {
		global $_CB_framework;

		static $JS_loaded				=	0;

		$userId							=	(int) $user->get( 'id' );
		$fieldName						=	$field->get( 'name' );
		$value							=	(int) $user->get( $fieldName );

		$readOnly						=	$this->_isReadOnly( $field, $user, $reason );

		$maxPoints						=	(int) $field->params->get( 'integer_maximum', '1000000' );
		$pointsLayout					=	$field->params->get( 'points_layout', '' );
		$userlistIncrement				=	(int) $field->params->get( 'points_list', 0 );
		$userlistAccess					=	false;

		if ( $reason == 'list' ) {
			$fieldName					=	$fieldName . $userId;

			if ( $userlistIncrement ) {
				$userlistAccess			=	true;
			}
		}


		$canIncrement					=	( ( ! $readOnly ) && $this->getIncrementAccess( $field, $user ) && ( ( ( $reason == 'list' ) && $userlistAccess ) || ( $reason != 'list' ) ) );

		if ( $canIncrement ) {
			$plusCSS					=	$field->params->get( 'points_plus_class', '' );
			$minusCSS					=	$field->params->get( 'points_minus_class', '' );

			$plusIcon					=	'<span class="' . ( $plusCSS ? htmlspecialchars( $plusCSS ) : 'fa fa-plus-circle fa-lg' ) . '"></span>';
			$minusIcon					=	'<span class="' . ( $minusCSS ? htmlspecialchars( $minusCSS ) : 'fa fa-minus-circle fa-lg' ) . '"></span>';

			$replace					=	array(	'[plus]' => ( $value < $maxPoints ? '<span class="cbPointsFieldIncrement cbPointsFieldIncrementPlus" data-value="plus" data-field="' . $field->get( 'name' ) . '" data-target="' . $userId . '">' . $plusIcon . '</span>' : null ),
													'[minus]' => ( $value > 0 ? '<span class="cbPointsFieldIncrement cbPointsFieldIncrementMinus" data-value="minus" data-field="' . $field->get( 'name' ) . '" data-target="' . $userId . '">' . $minusIcon . '</span>' : null ),
													'[value]' => '<span class="cbPointsFieldValue">' . $value . '</span>',
												);

			if ( $pointsLayout ) {
				$pointsLayout			=	CBTxt::Th( $pointsLayout, null, $replace );
			} else {
				$pointsLayout			=	CBTxt::Th( 'POINTS_FIELD_LAYOUT_VALUE_PLUS_MINUS', '[value] [plus] [minus]', $replace );
			}

			if ( $ajax ) {
				$return					=	$pointsLayout;
			} else {
				$return					=	'<span id="' . $fieldName . 'Container" class="cbPointsField' . ( ! in_array( $reason, array( 'edit', 'register' ) ) ? ' cbPointsFieldToggle' : null ) . ( $userlistAccess ? ' cbClicksInside' : null ) . '">'
										.		$pointsLayout
										.	'</span>';

				if ( ! in_array( $reason, array( 'edit', 'register' ) ) ) {
					if ( ! $JS_loaded++ ) {
						cbGetRegAntiSpamInputTag();

						$cbGetRegAntiSpams	=	cbGetRegAntiSpams();

							$js				=	"$( '.cbPointsFieldToggle' ).on( 'click', '.cbPointsFieldIncrement', function ( e ) {"
											.		"var points = $( this ).parents( '.cbPointsField' );"
											.		"var increment = $( this ).data( 'value' );"
											.		"var field = $( this ).data( 'field' );"
											.		"var target = $( this ).data( 'target' );"
											.		"$.ajax({"
											.			"type: 'POST',"
											.			"url: '" . addslashes( cbSef( 'index.php?option=com_comprofiler&view=fieldclass&function=savevalue&reason=' . urlencode( $reason ), false, 'raw' ) ) . "',"
											.			"data: {"
											.				"field: field,"
											.				"user: target,"
											.				"value: increment,"
											.				cbSpoofField() . ": '" . addslashes( cbSpoofString( null, 'fieldclass' ) ) . "',"
											.				cbGetRegAntiSpamFieldName() . ": '" . addslashes( $cbGetRegAntiSpams[0] ) . "'"
											.			"}"
											.		"}).done( function( data, textStatus, jqXHR ) {"
											.			"points.html( data );"
											.		"});"
											.	"});";

						$_CB_framework->outputCbJQuery( $js );
					}
				}
			}
		} else {
			$return						=	parent::getField( $field, $user, 'html', $reason, 0 );
		}

		return $return;
	}

	/**
	 * Direct access to field for custom operations, like for Ajax
	 *
	 * WARNING: direct unchecked access, except if $user is set, then check well for the $reason ...
	 *
	 * @param FieldTable     $field
	 * @param null|UserTable $user
	 * @param array          $postdata
	 * @param string         $reason 'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'search' for searches (always public!)
	 * @return string                  Expected output.
	 */
	public function fieldClass( &$field, &$user, &$postdata, $reason ) {
		global $_CB_framework, $_CB_database;

		parent::fieldClass( $field, $user, $postdata, $reason ); // Performs spoof check

		if ( ! $user ) {
			return null;
		}

		$userId							=	(int) $user->get( 'id' );

		if ( ( ! in_array( $reason, array( 'profile', 'list' ) ) ) || ( cbGetParam( $_GET, 'function', '' ) != 'savevalue' ) || ( ! $userId ) || $this->_isReadOnly( $field, $user, $reason ) || ( ! $this->getIncrementAccess( $field, $user ) ) ) {
			return null; // wrong reason, wrong function, user doesn't exist, field is read only, or user has no increment access; do nothing
		}

		$myId							=	(int) $_CB_framework->myId();
		$fieldId						=	(int) $field->get( 'fieldid' );
		$ipAddresses					=	cbGetIParray();
		$ipAddress						=	trim( array_shift( $ipAddresses ) );
		$fieldName						=	$field->get( 'name' );

		$direction						=	stripslashes( cbGetParam( $postdata, 'value' ) );
		$value							=	(int) $user->get( $fieldName );

		if ( $direction == 'plus' ) {
			$increment					=	(int) $field->params->get( 'points_inc_plus', 1 );
			$value						+=	( $increment && ( $increment > 0 ) ? $increment : 0 );
		} elseif ( $direction == 'minus' ) {
			$increment					=	(int) $field->params->get( 'points_inc_minus', 1 );
			$value						-=	( $increment && ( $increment > 0 ) ? $increment : 0 );
			$increment					=	( $increment ? -$increment : 0 );
		} else {
			$increment					=	0;
		}

		$postdata[$fieldName]			=	$value;

		if ( $this->validate( $field, $user, $fieldName, $value, $postdata, $reason ) && $increment && ( (int) $user->get( $fieldName ) != $value ) ) {
			$query						=	'INSERT INTO ' . $_CB_database->NameQuote( '#__comprofiler_ratings' )
										.	"\n ("
										.		$_CB_database->NameQuote( 'user_id' )
										.		', ' . $_CB_database->NameQuote( 'type' )
										.		', ' . $_CB_database->NameQuote( 'item' )
										.		', ' . $_CB_database->NameQuote( 'target' )
										.		', ' . $_CB_database->NameQuote( 'rating' )
										.		', ' . $_CB_database->NameQuote( 'ip_address' )
										.		', ' . $_CB_database->NameQuote( 'date' )
										.	')'
										.	"\n VALUES ("
										.		$myId
										.		', ' . $_CB_database->Quote( 'field' )
										.		', ' . $fieldId
										.		', ' . $userId
										.		', ' . (float) $increment
										.		', ' . $_CB_database->Quote( $ipAddress )
										.		', ' . $_CB_database->Quote( $_CB_framework->getUTCDate() )
										.	')';
			$_CB_database->setQuery( $query );
			$_CB_database->query();

			if ( $user->storeDatabaseValue( $fieldName, (int) $value ) ) {
				$this->_logFieldUpdate( $field, $user, $reason, (int) $user->get( $fieldName ), (int) $value );

				$user->set( $fieldName, (int) $value );
			}
		}

		return $this->getPointsHTML( $field, $user, $reason, true );
	}

	/**
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types ) {
		$return					=	null;

		switch ( $output ) {
			case 'html':
				$return			=	$this->formatFieldValueLayout( $this->getPointsHTML( $field, $user, $reason ), $reason, $field, $user );
				break;
			case 'htmledit':
				if ( ( $reason == 'search' ) || $this->getIncrementAccess( $field, $user ) ) {
					$return		=	parent::getField( $field, $user, $output, $reason, $list_compare_types );
				}
				break;
			default:
				$return			=	parent::getField( $field, $user, $output, $reason, $list_compare_types );
				break;
		}

		return $return;
	}
}

class CBfield_terms extends CBfield_checkbox
{
	/**
	 * Initializer:
	 * Puts the default value of $field into $user (for registration or new user in backend)
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $reason      'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'search' for searches
	 */
	public function initFieldToDefault( &$field, &$user, $reason )
	{
		foreach ( $field->getTableColumns() as $col ) {
			if ( ( $reason == 'search' ) || ( strpos( $col, 'consent' ) !== false ) ) {
				$user->$col							=	null;
			} else {
				$user->$col							=	$field->default;
			}
		}
	}
	/**
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output  'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason  'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'list' for user-lists
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types )
	{
		global $_CB_framework;

		$fieldName						=	$field->get( 'name', null, GetterInterface::STRING );
		$expired						=	$this->getConsentExpired( $field, $user );

		// Reset the value to unaccepted if consent has expired:
		if ( $expired ) {
			$user->set( $fieldName, 0 );
		}

		$value							=	$user->get( $fieldName, 0, GetterInterface::INT );
		$consent						=	$user->get( $fieldName . 'consent', null, GetterInterface::STRING );
		$return							=	null;

		if ( ( $output == 'htmledit' ) && ( $reason != 'search' ) ) {
			if ( Application::MyUser()->getUserId() != $user->get( 'id', 0, GetterInterface::INT ) ) {
				// Terms and Conditions should never be required to be accepted by a user other than the profile owner:
				$field->set( 'required', 0 );
			}

			// If consent has expired lets add a CSS class to the field for potential styling of expired consent fields:
			if ( $expired ) {
				$field->set( 'cssclass', trim( $field->get( 'cssclass', null, GetterInterface::STRING ) . ' cbTermsConsentExpired' ) );
			}

			$cbUser						=	CBuser::getMyInstance();
			$termsOutput				=	$field->params->get( 'terms_output', 'url' );
			$termsType					=	$cbUser->replaceUserVars( $field->params->get( 'terms_type', 'TERMS_AND_CONDITIONS' ) );
			$termsDisplay				=	$field->params->get( 'terms_display', 'modal' );
			$termsURL					=	cbSef( $cbUser->replaceUserVars( $field->params->get( 'terms_url', null ) ), false );
			$termsText					=	$cbUser->replaceUserVars( $field->params->get( 'terms_text', null ) );
			$termsWidth					=	$field->params->get( 'terms_width', 400 );
			$termsHeight				=	$field->params->get( 'terms_height', 200 );

			if ( ( ( $termsOutput == 'url' ) && ( ! $termsURL ) ) || ( ( $termsOutput == 'text' ) && ( ! $termsText ) ) ) {
				return parent::getField( $field, $user, $output, $reason, $list_compare_types );
			}

			if ( ! $termsType ) {
				$termsType				=	CBTxt::T( 'TERMS_AND_CONDITIONS', 'Terms and Conditions' );
			}

			if ( ! $termsWidth ) {
				$termsWidth				=	400;
			}

			if ( ! $termsHeight ) {
				$termsHeight			=	200;
			}

			if ( $termsDisplay == 'iframe' ) {
				if ( is_numeric( $termsHeight ) ) {
					$termsHeight		.=	'px';
				}

				if ( is_numeric( $termsWidth ) ) {
					$termsWidth			.=	'px';
				}

				if ( $termsOutput == 'url' ) {
					$return				.=	'<div class="embed-responsive mb-2 cbTermsFrameContainer" style="padding-bottom: ' . htmlspecialchars( $termsHeight ) . ';">'
										.		'<iframe class="embed-responsive-item d-block border rounded cbTermsFrameURL" style="width: ' . htmlspecialchars( $termsWidth ) . ';" src="' . htmlspecialchars( $termsURL ) . '"></iframe>'
										.	'</div>';
				} else {
					$return				.=	'<div class="bg-light border rounded p-2 mb-2 cbTermsFrameText" style="height:' . htmlspecialchars( $termsHeight ) . ';width:' . htmlspecialchars( $termsWidth ) . ';overflow:auto;">' . $termsText . '</div>';
				}

											// CBTxt::Th( 'TERMS_FIELD_I_AGREE_ON_THE_ABOVE_CONDITIONS', 'I Agree to the above [type].', array( '[type]' => $termsType ) )
				$label					=	CBTxt::Th( 'FIELD_' . $field->get( 'fieldid', 0, GetterInterface::INT ) . '_TERMS_FIELD_I_AGREE_ON_THE_ABOVE_CONDITIONS TERMS_FIELD_I_AGREE_ON_THE_ABOVE_CONDITIONS', 'I Agree to the above [type].', array( '[type]' => $termsType ) );
			} else {
				$attributes				=	' class="cbTermsLink"';

				if ( ( $termsOutput == 'text' ) && ( $termsDisplay == 'window' ) ) {
					$termsDisplay		=	'modal';
				}

				if ( $termsDisplay == 'modal' ) {
					// Tooltip height percentage would be based off window height (including scrolling); lets change it to be based off the viewport height:
					$termsHeight		=	( substr( $termsHeight, -1 ) == '%' ? (int) substr( $termsHeight, 0, -1 ) . 'vh' : $termsHeight );

					if ( $termsOutput == 'url' ) {
						$tooltip		=	'<iframe class="d-block m-0 p-0 border-0 cbTermsModalURL" height="100%" width="100%" src="' . htmlspecialchars( $termsURL ) . '"></iframe>';
					} else {
						$tooltip		=	'<div class="cbTermsModalText" style="height:100%;width:100%;overflow:auto;">' . $termsText . '</div>';
					}

					$url				=	'javascript:void(0);';
					$attributes			.=	' ' . cbTooltip( $_CB_framework->getUi(), $tooltip, $termsType, array( $termsWidth, $termsHeight ), null, null, null, 'data-hascbtooltip="true" data-cbtooltip-modal="true"' );
				} else {
					$url				=	htmlspecialchars( $termsURL );
					$attributes			.=	' target="_blank"';
				}

											// CBTxt::Th( 'TERMS_FIELD_ACCEPT_URL_CONDITIONS', 'Accept <!--suppress HtmlUnknownTarget --><a href="[url]"[attributes]>[type]</a>', array( '[url]' => $url, '[attributes]' => $attributes, '[type]' => $termsType ) )
				$label					=	CBTxt::Th( 'FIELD_' . $field->get( 'fieldid', 0, GetterInterface::INT ) . '_TERMS_FIELD_ACCEPT_URL_CONDITIONS TERMS_FIELD_ACCEPT_URL_CONDITIONS', 'Accept <!--suppress HtmlUnknownTarget --><a href="[url]"[attributes]>[type]</a>', array( '[url]' => $url, '[attributes]' => $attributes, '[type]' => $termsType ) );
			}

			$inputName					=	$field->name;
			$translatedTitle			=	$this->getFieldTitle( $field, $user, 'html', $reason );
			$htmlDescription			=	$this->getFieldDescription( $field, $user, 'htmledit', $reason );
			$trimmedDescription			=	trim( strip_tags( $htmlDescription ) );
			$inputDescription			=	$field->params->get( 'fieldLayoutInputDesc', 1, GetterInterface::INT );

			$attributes					=	null;

			if ( $this->_isRequired( $field, $user, $reason ) ) {
				$attributes				.=	' class="form-check-input required"';
			} else {
				$attributes				.=	' class="form-check-input"';
			}

			$attributes					.=	( $trimmedDescription && $inputDescription ? cbTooltip( $_CB_framework->getUi(), $htmlDescription, $translatedTitle, null, null, null, null, 'data-hascbtooltip="true"' ) : null );

			$return						.=	'<div class="cbSnglCtrlLbl form-check form-check-inline">'
										.		'<input type="checkbox" id="' . htmlspecialchars( $inputName ) . '" name="' . htmlspecialchars( $inputName ) . '" value="1"' . ( $value == 1 ? ' checked="checked"' : null ) . $attributes . ' />'
										.		'<label for="' . htmlspecialchars( $inputName ) . '" class="form-check-label">'
										.			$label
										.		'</label>'
										.	'</div>'
										.	$this->_fieldIconsHtml( $field, $user, $output, $reason, null, $field->type, $value, 'input', null, true, $this->_isRequired( $field, $user, $reason ) && ! $this->_isReadOnly( $field, $user, $reason ) );

			// Display the consent datetime or if expired the last datetime they consented:
			if ( $consent && ( $consent != '0000-00-00 00:00:00' ) ) {
				$return					.=	'<div class="text-small text-muted cbTermsConsented">';

				if ( $expired ) {
												// CBTxt::Th( 'TERMS_FIELD_LAST_ACCEPTED_ON', 'Last accepted on [consent].', array( '[consent]' => cbFormatDate( $consent ) ) )
					$return				.=		CBTxt::Th( 'FIELD_' . $field->get( 'fieldid', 0, GetterInterface::INT ) . '_TERMS_FIELD_LAST_ACCEPTED_ON TERMS_FIELD_LAST_ACCEPTED_ON', 'Last accepted on [consent]', array( '[consent]' => cbFormatDate( $consent ) ) );
				} else {
												// CBTxt::Th( 'TERMS_FIELD_ACCEPTED_ON', 'Accepted on [consent].', array( '[consent]' => cbFormatDate( $consent ) ) )
					$return				.=		CBTxt::Th( 'FIELD_' . $field->get( 'fieldid', 0, GetterInterface::INT ) . '_TERMS_FIELD_ACCEPTED_ON TERMS_FIELD_ACCEPTED_ON', 'Accepted on [consent]', array( '[consent]' => cbFormatDate( $consent ) ) );
				}

				$return					.=	'</div>';
			}
		} else {
			$return						=	parent::getField( $field, $user, $output, $reason, $list_compare_types );

			// If the user is a moderator and we're outputting search lets also output the consent date fields:
			if ( ( $output == 'htmledit' ) && ( $reason == 'search' ) && Application::MyUser()->isGlobalModerator() ) {
				$minNam					=	$fieldName . 'consent__minval';
				$maxNam					=	$fieldName . 'consent__maxval';

				$minVal					=	$user->get( $minNam, null, GetterInterface::STRING );
				$maxVal					=	$user->get( $maxNam, null, GetterInterface::STRING );
				$yMax					=	Application::Date( 'now', 'UTC' )->format( 'Y' );

				$calendars				=	new cbCalendars( ( Application::Cms()->getClientId() ? 2 : 1 ) );
				$minHtml				=	$this->formatFieldValueLayout( $calendars->cbAddCalendar( $minNam, CBTxt::Th( 'UE_SEARCH_FROM', 'Between' ) . ' ' . $this->getFieldTitle( $field, $user, 'text', $reason ), false, $minVal, false, true, $yMax, ( $yMax - 30 ) ), $reason, $field, $user );
				$maxHtml				=	$this->formatFieldValueLayout( $calendars->cbAddCalendar( $maxNam, CBTxt::Th( 'UE_SEARCH_TO', 'and' ) . ' ' . $this->getFieldTitle( $field, $user, 'text', $reason ), false, $maxVal, false, true, $yMax, ( $yMax - 30 ) ), $reason, $field, $user );

				$return					.=	$this->_fieldSearchRangeModeHtml( $field, $user, $output, $reason, $value, $minHtml, $maxHtml, $list_compare_types, 'mt-2' );
			}
		}

		return $return;
	}

	/**
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save profile edit, 'register' for registration, 'search' for searches
	 */
	public function prepareFieldDataSave( &$field, &$user, &$postdata, $reason ) {
		$this->_prepareFieldMetaSave( $field, $user, $postdata, $reason );

		$fieldName		=	$field->get( 'name', null, GetterInterface::STRING );
		$value			=	Get::get( $postdata, $fieldName, 0, GetterInterface::INT );

		// Reset the value to unaccepted if consent has expired to trigger field update, but only for the profile owner:
		if ( Application::MyUser()->getUserId() == $user->get( 'id', 0, GetterInterface::INT ) ) {
			if ( $this->getConsentExpired( $field, $user ) ) {
				$user->set( $fieldName, 0 );
			}
		}

		if ( $this->validate( $field, $user, $fieldName, $value, $postdata, $reason ) && ( $user->get( $fieldName, 0, GetterInterface::INT ) !== $value ) ) {
			$this->_logFieldUpdate( $field, $user, $reason, $user->get( $fieldName, 0, GetterInterface::INT ), $value );

			// Only the profile owner can actually give consent:
			if ( Application::MyUser()->getUserId() == $user->get( 'id', 0, GetterInterface::INT ) ) {
				if ( $value ) {
					$user->set( $fieldName . 'consent', Application::Database()->getUtcDateTime() );
				} else {
					$user->set( $fieldName . 'consent', '0000-00-00 00:00:00' );
				}
			}
		}

		$user->set( $fieldName, $value );
	}

	/**
	 * Validator:
	 * Validates $value for $field->required and other rules
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user        RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  string      $columnName  Column to validate
	 * @param  string      $value       (RETURNED:) Value to validate, Returned Modified if needed !
	 * @param  array       $postdata    Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason      'edit' for save user edit, 'register' for save registration
	 * @return boolean                  True if validate, $this->_setErrorMSG if False
	 */
	public function validate( &$field, &$user, /** @noinspection PhpUnusedParameterInspection */ $columnName, &$value, /** @noinspection PhpUnusedParameterInspection */ &$postdata, $reason )
	{
		if ( Application::MyUser()->getUserId() != $user->get( 'id', 0, GetterInterface::INT ) ) {
			// Terms and Conditions should never be required to be accepted by a user other than the profile owner:
			$field->set( 'required', 0 );
		}

		return parent::validate( $field, $user, $columnName, $value, $postdata, $reason );
	}

	/**
	 * Checks if consent has expired
	 *
	 * @param FieldTable $field
	 * @param UserTable  $user
	 * @return bool
	 */
	private function getConsentExpired( $field, $user )
	{
		$fieldName				=	$field->get( 'name', null, GetterInterface::STRING );

		if ( ( ! $user->get( $fieldName, 0, GetterInterface::INT ) ) || ( ! $user->get( 'id', 0, GetterInterface::INT ) ) ) {
			// If not accepted or not registered then consent can't or hasn't been given so it can't be expired:
			return false;
		}

		$termsDuration			=	$field->params->get( 'terms_duration', 'forever', GetterInterface::STRING );

		if ( $termsDuration == 'custom' ) {
			$termsDuration		=	$field->params->get( 'terms_duration_custom', '+1 YEAR', GetterInterface::STRING );
		}

		if ( ( ! $termsDuration ) || ( $termsDuration == 'forever' ) ) {
			// Consent doesn't expire:
			return false;
		}

		$consent				=	$user->get( $fieldName . 'consent', null, GetterInterface::STRING );

		if ( ( ! $consent ) || ( $consent == '0000-00-00 00:00:00' ) ) {
			// Consent was never given so respond as expired:
			return true;
		} elseif (  Application::Date( 'now', 'UTC' )->getTimestamp() >= Application::Date( $consent, 'UTC' )->modify( strtoupper( $termsDuration ) )->getTimestamp() ) {
			// Consent was given, but current datetime is past the expiration date:
			return true;
		}

		return false;
	}

	/**
	 * Finder:
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $searchVals  RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata    Typically $_POST (but not necessarily), filtering required.
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @param  string      $reason      'edit' for save profile edit, 'register' for registration, 'search' for searches
	 * @return cbSqlQueryPart[]
	 */
	function bindSearchCriteria( &$field, &$searchVals, &$postdata, $list_compare_types, $reason ) {
		$query										=	array();

		$fieldName									=	$field->get( 'name', null, GetterInterface::STRING );
		$searchMode									=	$this->_bindSearchMode( $field, $searchVals, $postdata, 'none', $list_compare_types );

		if ( $searchMode ) {
			$value									=	Get::get( $postdata, $fieldName, null, GetterInterface::STRING );

			if ( $value === '0' ) {
				$value								=	0;
			} elseif ( $value === '1' ) {
				$value								=	1;
			} else {
				return $query;
			}

			$searchVals->$fieldName					=	$value;

			$sql									=	new cbSqlQueryPart();
			$sql->tag								=	'column';
			$sql->name								=	$fieldName;
			$sql->table								=	$field->get( 'table', null, GetterInterface::STRING );
			$sql->type								=	'sql:field';
			$sql->operator							=	'=';
			$sql->value								=	$value;
			$sql->valuetype							=	'const:int';
			$sql->searchmode						=	$searchMode;

			$query[]								=	$sql;
		}

		if ( ! Application::MyUser()->isGlobalModerator() ) {
			// Only moderators can search consent dates:
			return $query;
		}

		$minNam										=	$fieldName . 'consent__minval';
		$maxNam										=	$fieldName . 'consent__maxval';
		$searchMode									=	$this->_bindSearchRangeMode( $field, $searchVals, $postdata, $minNam, $maxNam, $list_compare_types );

		if ( $searchMode ) {
			$minVal									=	Get::get( $postdata, $minNam, null, GetterInterface::STRING );
			$maxVal									=	Get::get( $postdata, $maxNam, null, GetterInterface::STRING );
			$minValIn								=	$minVal;
			$maxValIn								=	$maxVal;

			if ( $field->type == 'datetime' ) {
				$minSearch							=	( $minVal && ( $minVal !== '0000-00-00 00:00:00' ) );
				$maxSearch							=	( $maxVal && ( $maxVal !== '0000-00-00 00:00:00' ) );
			} elseif ( $field->type == 'time' ) {
				$minSearch							=	( $minVal && ( $minVal !== '00:00:00' ) );
				$maxSearch							=	( $maxVal && ( $maxVal !== '00:00:00' ) );
			} else {
				$minSearch							=	( $minVal && ( $minVal !== '0000-00-00' ) );
				$maxSearch							=	( $maxVal && ( $maxVal !== '0000-00-00' ) );
			}

			$forceMin								=	( ( ! $minSearch ) && $maxSearch && ( ! in_array( $field->name, array( 'lastupdatedate', 'lastvisitDate' ) ) ) );

			if ( $minSearch || $forceMin ) {
				$min								=	new cbSqlQueryPart();
				$min->tag							=	'column';
				$min->name							=	$fieldName . 'consent';
				$min->table							=	$field->get( 'table', null, GetterInterface::STRING );
				$min->type							=	'sql:field';
				$min->operator						=	( ! $forceMin ? ( $searchMode == 'isnot' ? '<=' : '>=' ) : '>' );

				if ( $field->type == 'datetime' ) {
					$min->value						=	( ! $forceMin ? $minVal : '0000-00-00 00:00:00' );
					$min->valuetype					=	'const:datetime';
				} elseif ( $field->type == 'time' ) {
					$min->value						=	( ! $forceMin ? $minVal : '00:00:00' );
					$min->valuetype					=	'const:time';
				} else {
					$min->value						=	( ! $forceMin ? $minVal : '0000-00-00' );
					$min->valuetype					=	'const:date';
				}

				$min->searchmode					=	$searchMode;

				if ( ! $forceMin ) {
					if ( ( ! $maxVal ) && $maxValIn ) {
						$searchVals->$maxNam		=	$maxValIn;
					}

					$searchVals->$minNam			=	$minValIn;
				}
			}

			if ( $maxSearch ) {
				$max								=	new cbSqlQueryPart();
				$max->tag							=	'column';
				$max->name							=	$fieldName . 'consent';
				$max->table							=	$field->get( 'table', null, GetterInterface::STRING );
				$max->type							=	'sql:field';
				$max->operator						=	( $searchMode == 'isnot' ? '>=' : '<=' );
				$max->value							=	$maxVal;

				if ( $field->type == 'datetime' ) {
					$max->valuetype					=	'const:datetime';
				} elseif ( $field->type == 'time' ) {
					$max->valuetype					=	'const:time';
				} else {
					$max->valuetype					=	'const:date';
				}

				$max->searchmode					=	$searchMode;

				if ( ( ! $minVal ) && $minValIn ) {
					$searchVals->$minNam			=	$minValIn;
				}

				$searchVals->$maxNam				=	$maxValIn;
			}

			if ( isset( $min ) && isset( $max ) ) {
				$sql								=	new cbSqlQueryPart();
				$sql->tag							=	'column';
				$sql->name							=	$fieldName . 'consent';
				$sql->table							=	$field->get( 'table', null, GetterInterface::STRING );
				$sql->type							=	'sql:operator';
				$sql->operator						=	( $searchMode == 'isnot' ? 'OR' : 'AND' );
				$sql->searchmode					=	$searchMode;

				$sql->addChildren( array( $min, $max ) );

				$query[]							=	$sql;
			} elseif ( isset( $min ) ) {
				$query[]							=	$min;
			} elseif ( isset( $max ) ) {
				$query[]							=	$max;
			}
		}

		return $query;
	}
}

class CBfield_color extends cbFieldHandler
{

	/**
	 * Accessor:
	 * Returns a field in specified format
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user
	 * @param  string      $output               'html', 'xml', 'json', 'php', 'csvheader', 'csv', 'rss', 'fieldslist', 'htmledit'
	 * @param  string      $reason               'profile' for user profile view, 'edit' for profile edit, 'register' for registration, 'search' for searches
	 * @param  int         $list_compare_types   IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @return mixed
	 */
	public function getField( &$field, &$user, $output, $reason, $list_compare_types )
	{
		if ( ( $output == 'htmledit' ) && ( $reason == 'search' ) ) {
			// Searching a color field isn't very practical so for now just disable searching:
			return null;
		}

		if ( $output == 'html' ) {
			$valuesArray		=	array();

			foreach ( $field->getTableColumns() as $col ) {
				$valuesArray[]	=	$user->get( $col, null, GetterInterface::STRING );
			}

			$value				=	strtoupper( implode( ', ', $valuesArray ) );

			if ( $value && preg_match( '/^#[0-9A-Fa-f]{3,6}$/i', $value ) ) {
				$value			=	'<span class="d-inline-block border cbColorField">'
								.		'<div class="pl-5 pr-5 pt-3 pb-3 cbColorFieldSample" style="background-color: ' . htmlspecialchars( $value ) . ';"></div>'
								.		'<div class="border-top text-center user-select-all cbColorFieldColor">' . htmlspecialchars( $value ) . '</div>'
								.	'</span>';
			}

			return $this->formatFieldValueLayout( $this->_formatFieldOutput( $field->get( 'name', null, GetterInterface::STRING ), $value, $output, false ), $reason, $field, $user );
		}

		return parent::getField( $field, $user, $output, $reason, $list_compare_types );
	}

	/**
	 * Mutator:
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user      RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata  Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason    'edit' for save user edit, 'register' for save registration
	 */
	public function prepareFieldDataSave( &$field, &$user, &$postdata, $reason )
	{
		$this->_prepareFieldMetaSave( $field, $user, $postdata, $reason );

		foreach ( $field->getTableColumns() as $col ) {
			$value						=	Get::get( $postdata, $col, null, GetterInterface::STRING );

			if ( $value !== null ) {
				$value					=	strtoupper( $value );

				if ( $this->validate( $field, $user, $col, $value, $postdata, $reason ) ) {
					if ( $user->get( $col, '', GetterInterface::STRING ) !== $value ) {
						$this->_logFieldUpdate( $field, $user, $reason, $user->get( $col, '', GetterInterface::STRING ), $value );
					}
				}

				$user->set( $col, $value );
			}
		}
	}

	/**
	 * Validator:
	 * Validates $value for $field->required and other rules
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $user        RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  string      $columnName  Column to validate
	 * @param  string      $value       (RETURNED:) Value to validate, Returned Modified if needed !
	 * @param  array       $postdata    Typically $_POST (but not necessarily), filtering required.
	 * @param  string      $reason      'edit' for save profile edit, 'register' for registration, 'search' for searches
	 * @return boolean                  True if validate, $this->_setErrorMSG if False
	 */
	public function validate( &$field, &$user, $columnName, &$value, &$postdata, $reason )
	{
		if ( ! parent::validate( $field, $user, $columnName, $value, $postdata, $reason ) ) {
			return false;
		}

		if ( ( $value !== '' ) && ( $value !== null ) && ( ! preg_match( '/^#[0-9A-Fa-f]{3,6}$/i', $value ) ) ) {
			$this->_setValidationError( $field, $user, $reason, CBTxt::T( 'Not a color' ) );

			return false;
		}

		return true;
	}

	/**
	 * Finder:
	 * Prepares field data for saving to database (safe transfer from $postdata to $user)
	 * Override
	 *
	 * @param  FieldTable  $field
	 * @param  UserTable   $searchVals          RETURNED populated: touch only variables related to saving this field (also when not validating for showing re-edit)
	 * @param  array       $postdata            Typically $_POST (but not necessarily), filtering required.
	 * @param  int         $list_compare_types  IF reason == 'search' : 0 : simple 'is' search, 1 : advanced search with modes, 2 : simple 'any' search
	 * @param  string      $reason              'edit' for save user edit, 'register' for save registration
	 * @return cbSqlQueryPart[]
	 */
	public function bindSearchCriteria( &$field, &$searchVals, &$postdata, $list_compare_types, /** @noinspection PhpUnusedParameterInspection */ $reason )
	{
		// Searching a color field isn't very practical so for now just disable searching:
		return array();
	}
}

/**
* Tab Class for User Stats display
*/
class getStatsTab extends cbTabHandler
{
}

/**
* Tab Class for Canvas display
*/
class getCanvasTab extends cbTabHandler
{
	/**
	 * Generates the HTML to display the user profile tab
	 *
	 * @param  TabTable   $tab       the tab database entry
	 * @param  UserTable  $user      the user being displayed
	 * @param  int        $ui        1 for front-end, 2 for back-end
	 * @return mixed                 Either string HTML for tab content, or false if ErrorMSG generated
	 */
	public function getEditTab( $tab, $user, $ui )
	{
		return $this->_writeTabDescription( $tab, $user, null, 'edit' );
	}
}

/**
* Tab Class for User Profile Page title display
*/
class getPageTitleTab  extends cbTabHandler
{
	/**
	 * Generates the HTML to display the user profile tab
	 *
	 * @param  TabTable   $tab       the tab database entry
	 * @param  UserTable  $user      the user being displayed
	 * @param  int        $ui        1 for front-end, 2 for back-end
	 * @return mixed                 Either string HTML for tab content, or false if ErrorMSG generated
	 */
	public function getDisplayTab( $tab,$user,$ui )
	{
		$params	=	$this->params;
		$title	=	cbReplaceVars( $params->get( 'title', '_UE_PROFILE_TITLE_TEXT' ), $user );
		$name	=	$user->getFormattedName();

		$return	=	( sprintf( $title, $name ) ? '<div class="mb-3 border-bottom cb-page-header cbProfileTitle"><h3 class="m-0 p-0 mb-2 cb-page-header-title">' . sprintf( $title, $name ) . '</h3></div>' : null )
				.	$this->_writeTabDescription( $tab, $user );

		return $return;
	}
}

/**
* Tab Class for User Profile Portrait/Avatar display
*/
class getPortraitTab extends cbTabHandler
{
	/**
	 * Generates the HTML to display the user profile tab
	 *
	 * @param  TabTable   $tab       the tab database entry
	 * @param  UserTable  $user      the user being displayed
	 * @param  int        $ui        1 for front-end, 2 for back-end
	 * @return mixed                 Either string HTML for tab content, or false if ErrorMSG generated
	 */
	public function getEditTab( $tab, $user, $ui )
	{
		return $this->_writeTabDescription( $tab, $user, null, 'edit' );
	}
}

/**
* Tab Class for User Profile EDIT Contacts special fields display
*/
class getContactTab extends cbTabHandler {
	/**
	 * Generates the HTML to display the user edit tab
	 *
	 * @param  TabTable   $tab       the tab database entry
	 * @param  UserTable  $user      the user being displayed
	 * @param  int        $ui        1 for front-end, 2 for back-end
	 * @return mixed                 either string HTML for tab content, or false if ErrorMSG generated
	 */
	public function getEditTab( $tab, $user, $ui )
	{
		return $this->_writeTabDescription( $tab, $user, null, 'edit' );
	}
}
