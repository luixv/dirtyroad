<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

 if ( ! class_exists( 'Freemius_Exception' ) ) { class Freemius_Exception extends Exception { protected $_result; protected $_type; protected $_code; public function __construct( $result ) { $this->_result = $result; $code = 0; $message = 'Unknown error, please check GetResult().'; $type = ''; if ( isset( $result['error'] ) && is_array( $result['error'] ) ) { if ( isset( $result['error']['code'] ) ) { $code = $result['error']['code']; } if ( isset( $result['error']['message'] ) ) { $message = $result['error']['message']; } if ( isset( $result['error']['type'] ) ) { $type = $result['error']['type']; } } $this->_type = $type; $this->_code = $code; parent::__construct( $message, is_numeric( $code ) ? $code : 0 ); } public function getResult() { return $this->_result; } public function getStringCode() { return $this->_code; } public function getType() { return $this->_type; } public function __toString() { $str = $this->getType() . ': '; if ( $this->code != 0 ) { $str .= $this->getStringCode() . ': '; } return $str . $this->getMessage(); } } }