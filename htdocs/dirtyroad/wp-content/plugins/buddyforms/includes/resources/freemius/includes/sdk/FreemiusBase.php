<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

 if ( ! defined( 'FS_API__VERSION' ) ) { define( 'FS_API__VERSION', '1' ); } if ( ! defined( 'FS_SDK__PATH' ) ) { define( 'FS_SDK__PATH', dirname( __FILE__ ) ); } if ( ! defined( 'FS_SDK__EXCEPTIONS_PATH' ) ) { define( 'FS_SDK__EXCEPTIONS_PATH', FS_SDK__PATH . '/Exceptions/' ); } if ( ! function_exists( 'json_decode' ) ) { throw new Exception( 'Freemius needs the JSON PHP extension.' ); } $exceptions = array( 'Exception', 'InvalidArgumentException', 'ArgumentNotExistException', 'EmptyArgumentException', 'OAuthException' ); foreach ( $exceptions as $e ) { require_once FS_SDK__EXCEPTIONS_PATH . $e . '.php'; } if ( class_exists( 'Freemius_Api_Base' ) ) { return; } abstract class Freemius_Api_Base { const VERSION = '1.0.4'; const FORMAT = 'json'; protected $_id; protected $_public; protected $_secret; protected $_scope; protected $_isSandbox; public function Init( $pScope, $pID, $pPublic, $pSecret, $pIsSandbox = false ) { $this->_id = $pID; $this->_public = $pPublic; $this->_secret = $pSecret; $this->_scope = $pScope; $this->_isSandbox = $pIsSandbox; } public function IsSandbox() { return $this->_isSandbox; } function CanonizePath( $pPath ) { $pPath = trim( $pPath, '/' ); $query_pos = strpos( $pPath, '?' ); $query = ''; if ( false !== $query_pos ) { $query = substr( $pPath, $query_pos ); $pPath = substr( $pPath, 0, $query_pos ); } $format_length = strlen( '.' . self::FORMAT ); $start = $format_length * ( - 1 ); if ( substr( strtolower( $pPath ), $start ) === ( '.' . self::FORMAT ) ) { $pPath = substr( $pPath, 0, strlen( $pPath ) - $format_length ); } switch ( $this->_scope ) { case 'app': $base = '/apps/' . $this->_id; break; case 'developer': $base = '/developers/' . $this->_id; break; case 'user': $base = '/users/' . $this->_id; break; case 'plugin': $base = '/plugins/' . $this->_id; break; case 'install': $base = '/installs/' . $this->_id; break; default: throw new Freemius_Exception( 'Scope not implemented.' ); } return '/v' . FS_API__VERSION . $base . ( ! empty( $pPath ) ? '/' : '' ) . $pPath . ( ( false === strpos( $pPath, '.' ) ) ? '.' . self::FORMAT : '' ) . $query; } abstract function MakeRequest( $pCanonizedPath, $pMethod = 'GET', $pParams = array() ); private function _Api( $pPath, $pMethod = 'GET', $pParams = array() ) { $pMethod = strtoupper( $pMethod ); try { $result = $this->MakeRequest( $pPath, $pMethod, $pParams ); } catch ( Freemius_Exception $e ) { $result = (object) $e->getResult(); } catch ( Exception $e ) { $result = (object) array( 'error' => (object) array( 'type' => 'Unknown', 'message' => $e->getMessage() . ' (' . $e->getFile() . ': ' . $e->getLine() . ')', 'code' => 'unknown', 'http' => 402 ) ); } return $result; } public function Api( $pPath, $pMethod = 'GET', $pParams = array() ) { return $this->_Api( $this->CanonizePath( $pPath ), $pMethod, $pParams ); } protected static function Base64UrlDecode( $input ) { $fn = 'base64' . '_decode'; return $fn( strtr( $input, '-_', '+/' ) ); } protected static function Base64UrlEncode( $input ) { $fn = 'base64' . '_encode'; $str = strtr( $fn( $input ), '+/', '-_' ); $str = str_replace( '=', '', $str ); return $str; } } 