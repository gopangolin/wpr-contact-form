<?php
/**
 * WP-Reactivate
 *
 *
 * @package   WP-Reactivate
 * @author    Pangolin
 * @license   GPL-3.0
 * @link      https://gopangolin.com
 * @copyright 2017 Pangolin (Pty) Ltd
 */

namespace Pangolin\WPR\Endpoint;
use Pangolin\WPR;

/**
 * @subpackage REST_Controller
 */
class Admin {
    /**
	 * Instance of this class.
	 *
	 * @since    0.8.1
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     0.8.1
	 */
	private function __construct() {
        $plugin = WPR\Plugin::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
	}

    /**
     * Set up WordPress hooks and filters
     *
     * @return void
     */
    public function do_hooks() {
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }

	/**
	 * Return an instance of this class.
	 *
	 * @since     0.8.1
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
			self::$instance->do_hooks();
		}

		return self::$instance;
	}

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {
        $version = '1';
        $namespace = $this->plugin_slug . '/v' . $version;
        $endpoint = '/admin/';

        register_rest_route( $namespace, $endpoint, array(
            array(
                'methods'               => \WP_REST_Server::READABLE,
                'callback'              => array( $this, 'get_contact_email' ),
                'permission_callback'   => array( $this, 'admin_permissions_check' ),
            ),
        ) );

        register_rest_route( $namespace, $endpoint, array(
            array(
                'methods'               => \WP_REST_Server::CREATABLE,
                'callback'              => array( $this, 'update_contact_email' ),
                'permission_callback'   => array( $this, 'admin_permissions_check' ),
                'args'                  => array(
                    'email' => array(
                        'required' => true,
                        'type' => 'string',
                        'description' => 'The user\'s email address',
                        'format' => 'email'
                    ),
                ),
            ),
        ) );

        register_rest_route( $namespace, $endpoint, array(
            array(
                'methods'               => \WP_REST_Server::EDITABLE,
                'callback'              => array( $this, 'update_contact_email' ),
                'permission_callback'   => array( $this, 'admin_permissions_check' ),
                'args'                  => array(
                    'email' => array(
                        'required' => true,
                        'type' => 'string',
                        'description' => 'The user\'s email address',
                        'format' => 'email'
                    ),
                ),
            ),
        ) );

        register_rest_route( $namespace, $endpoint, array(
            array(
                'methods'               => \WP_REST_Server::DELETABLE,
                'callback'              => array( $this, 'delete_contact_email' ),
                'permission_callback'   => array( $this, 'admin_permissions_check' ),
                'args'                  => array(),
            ),
        ) );

    }

    /**
     * Get Example
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public function get_contact_email( $request ) {
        $example_option = get_option( 'wpr_contact_email' );

        // Don't return false if there is no option
        if ( ! $example_option ) {
            return new \WP_REST_Response( array(
                'success' => true,
                'value' => ''
            ), 200 );
        }

        return new \WP_REST_Response( array(
            'success' => true,
            'value' => $example_option
        ), 200 );
    }

    /**
     * Create OR Update Example
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public function update_contact_email( $request ) {
        $updated = update_option( 'wpr_contact_email', $request->get_param( 'email' ) );

        return new \WP_REST_Response( array(
            'success'   => $updated,
            'value'     => $request->get_param( 'email' )
        ), 200 );
    }

    /**
     * Delete Example
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public function delete_contact_email( $request ) {
        $deleted = delete_option( 'wpr_contact_email' );

        return new \WP_REST_Response( array(
            'success'   => $deleted,
            'value'     => ''
        ), 200 );
    }

    /**
     * Check if a given request has access to update a setting
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function admin_permissions_check( $request ) {
        return current_user_can( 'manage_options' );
    }
}
