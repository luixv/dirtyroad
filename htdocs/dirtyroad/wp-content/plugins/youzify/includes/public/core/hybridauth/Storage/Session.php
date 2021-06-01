<?php
/*!
* Hybridauth
* https://hybridauth.github.io | https://github.com/hybridauth/hybridauth
*  (c) 2017 Hybridauth authors | https://hybridauth.github.io/license.html
*/

namespace Hybridauth\Storage;

use Hybridauth\Exception\RuntimeException;

/**
 * Hybridauth storage manager
 */
class Session implements StorageInterface
{
    /**
     * Namespace
     *
     * @var string
     */
    protected $storeNamespace = 'HYBRIDAUTH::STORAGE';

    /**
     * Key prefix
     *
     * @var string
     */
    private $sessionName = 'Youzify_Social_Login_Session';

    protected $keyPrefix = '';

    protected $sessionId = null;

    protected $data = array();

    /**
     * Initiate a new session
     *
     * @throws RuntimeException
     */
    public function __construct() {

        $this->sessionName = youzify_get_social_login_session_name();

    }

    /**
     * {@inheritdoc}
     */
    public function get( $key ) {

        // Load Data.
        $this->load();

        if ( isset( $this->data[ $key ] ) ) {
            return $this->data[ $key ];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function set( $key, $value ) {

        // Load Data.
        $this->load( true );

        // Set Data.
        $this->data[ $key ] = $value;

        // Store Data In Database
        $this->store();

    }

    /**
     * {@inheritdoc}
     */
    public function clear() {

        // Clear Data.
        $this->data = array();

        // Delete Browser Data
        $this->store();

        // Delete Database Data
        $this->delete();

    }

    protected function load( $createSession = false) {

        static $isLoaded = false;

        if ( $this->sessionId === null ) {

            if ( isset( $_COOKIE[ $this->sessionName ] ) ) {
                $this->sessionId = 'youzify_social_login_persistent_' . md5( SECURE_AUTH_KEY . $_COOKIE[ $this->sessionName ] );
            } else if ( $createSession ) {
                $unique = uniqid( 'youzify_social_login', true );

                $this->setCookie( $unique, apply_filters( 'youzify_social_login_session_cookie_expiration', 0 ), apply_filters( 'youzify_social_login_session_use_secure_cookie', false ) );

                $this->sessionId = 'youzify_social_login_persistent_' . md5(SECURE_AUTH_KEY . $unique);

                $isLoaded = true;

            }

        }

        if ( ! $isLoaded ) {
            if ( $this->sessionId !== null ) {
                $data = maybe_unserialize( get_site_transient( $this->sessionId ) );
                if ( is_array( $data ) ) {
                    $this->data = $data;
                }
                $isLoaded = true;
            }
        }

    }

    /**
     * {@inheritdoc}
     */
    public function delete( $key ) {

        $this->load();

        if ( isset( $this->data[ $key ] ) ) {
            unset( $this->data[$key ] );
            $this->store();
        }

        if ( $this->sessionId ) {
            $this->setCookie( $this->sessionId, time() - YEAR_IN_SECONDS, apply_filters('youzify_social_login_session_use_secure_cookie', false ) );
            add_action( 'shutdown', array( $this, 'delete_site_transient' ) );
        }

    }


    public function delete_site_transient() {

        $sessionID = $this->sessionId;

        if ( $sessionID ) {
            delete_site_transient( 'youzify_social_login_' . $sessionID );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deleteMatch( $key )
    {
        $key = $this->keyPrefix . strtolower( $key );


        if (isset( $_COOKIE[ $this->sessionName ] ) && count( $_COOKIE[ $this->sessionName ] ) ) {

            $tmp = $_COOKIE[ $this->sessionName ];
            $stored_session = maybe_unserialize( get_site_transient( $this->sessionId ) );

            foreach ($tmp as $k => $v) {
                if (strstr( $k, $key ) ) {
                    unset( $tmp[$k] );
                }
            }

            if ( ! empty( $stored_session) ) {

                foreach ($stored_session as $k => $v) {
                    if (strstr( $k, $key ) ) {
                        unset( $stored_session[$k] );
                    }
                }

                $this->data = $stored_session;
                $this->store();

            }

            $_COOKIE[ $this->sessionName ] = $tmp;

        }
    }

    /**
     * Set Cookie
     */
    private function setCookie($value, $expire, $secure = false) {

        setcookie( $this->sessionName, $value, $expire, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure );
    }

    /**
     * Store Data.
     */
    private function store() {
        if ( empty( $this->data ) ) {
            delete_site_transient( $this->sessionId );
        } else {
            set_site_transient( $this->sessionId, $this->data, apply_filters( 'youzify_social_login_persistent_expiration', HOUR_IN_SECONDS ) );
        }
    }

}
