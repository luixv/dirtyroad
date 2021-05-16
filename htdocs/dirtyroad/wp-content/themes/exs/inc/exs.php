<?php

if ( ! class_exists( 'ExS' ) ) :

	class ExS {
		public $state;
		/**
		 * Singleton
		 */
		public static function instance() {
			static $instance = null;
			if( null === $instance ) {
				$instance = new static();
			}

			return $instance;
		}

		private function __construct() {}
		private function __clone() {}
		private function __sleep() {}
		private function __wakeup() {}

		public function set( $key, $value ) {
			$this->state[ $key ] = $value;
		}

		public function get( $key ) {
			return ! empty( $this->state[ $key ] ) ? $this->state[ $key ] : false;
		}

	}

endif; //class exists
