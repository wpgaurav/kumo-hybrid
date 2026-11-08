<?php
/**
 * License-gated updates for the Kumo Hybrid downloaded from GauravTiwari.org.
 * Included only in the direct-download distribution, never WordPress.org builds.
 * The key gates theme updates only; every theme feature works without it.
 *
 * @package Kumo_Hybrid
 * @license GPL-2.0-or-later
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'Kumo_Hybrid_License', false ) ) {
	return;
}

/** Free lifetime-key updater; theme features never depend on its state. */
final class Kumo_Hybrid_License {
	const SLUG         = 'kumo-hybrid';
	const PRODUCT_ID   = 1189259;
	const SERVER       = 'https://gauravtiwari.org/';
	const ACCOUNT      = 'https://gauravtiwari.org/account/licenses/';
	const OPTION       = 'kumo_hybrid_license';
	const UPDATE_CACHE = 'kumo_hybrid_license_update';
	const NOTICE       = 'kumo_hybrid_license_notice';
	const CRON         = 'kumo_hybrid_license_check';
	const PAGE         = 'kumo-hybrid-license';

	/**
	 * Register the WordPress updater and settings hooks.
	 */
	public static function boot() {
		add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
		add_action( 'admin_post_kumo_hybrid_license', array( __CLASS__, 'handle' ) );
		add_action( 'admin_notices', array( __CLASS__, 'reminder' ) );
		add_filter( 'pre_set_site_transient_update_themes', array( __CLASS__, 'updates' ) );
		add_action( self::CRON, array( __CLASS__, 'check' ) );
		add_action( 'switch_theme', array( __CLASS__, 'unschedule' ) );
		add_action( 'admin_init', array( __CLASS__, 'schedule' ) );
	}

	/**
	 * Read the installed parent theme version.
	 *
	 * @return string
	 */
	private static function version() {
		return (string) wp_get_theme( self::SLUG )->get( 'Version' );
	}

	/**
	 * Schedule the daily check after an administrator visits the site.
	 */
	public static function schedule() {
		if ( ! wp_next_scheduled( self::CRON ) ) {
			wp_schedule_event( time() + DAY_IN_SECONDS, 'daily', self::CRON );
		}
	}

	/**
	 * Clear the daily check when switching themes.
	 */
	public static function unschedule() {
		wp_clear_scheduled_hook( self::CRON );
	}

	/**
	 * Read the stored activation state with safe defaults.
	 *
	 * @return array
	 */
	public static function state() {
		$data = get_option( self::OPTION, array() );
		return wp_parse_args(
			is_array( $data ) ? $data : array(),
			array(
				'key'             => '',
				'status'          => 'unregistered',
				'activation_hash' => '',
				'expires'         => '',
				'checked_at'      => 0,
			)
		);
	}

	/**
	 * Save activation state without autoloading it.
	 *
	 * @param array $data Activation state.
	 */
	private static function save( $data ) {
		update_option( self::OPTION, $data, false );
	}

	/**
	 * Make a bounded TLS request to the configured license server.
	 *
	 * @param string $action Allowed endpoint action.
	 * @param array  $params Request parameters.
	 * @return array|WP_Error
	 */
	private static function request( $action, $params ) {
		if ( ! in_array( $action, array( 'activate_license', 'check_license', 'deactivate_license', 'get_license_version' ), true ) ) {
			return new WP_Error( 'kumo_hybrid_license_action', __( 'Unsupported license request.', 'kumo-hybrid' ) );
		}
		$response = wp_remote_post(
			add_query_arg( 'fluent-cart', $action, self::SERVER ),
			array(
				'timeout'             => 15,
				'redirection'         => 0,
				'sslverify'           => true,
				'limit_response_size' => 131072,
				'body'                => array_merge(
					array(
						'item_id'         => self::PRODUCT_ID,
						'site_url'        => home_url( '/' ),
						'current_version' => self::version(),
					),
					$params
				),
			)
		);
		if ( is_wp_error( $response ) ) {
			return new WP_Error( 'kumo_hybrid_license_connection', __( 'Could not reach the license server. Your saved license has been kept.', 'kumo-hybrid' ) );
		}
		$code = wp_remote_retrieve_response_code( $response );
		$data = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( 422 === $code && is_array( $data ) && ! empty( $data['error_type'] ) ) {
			$messages = array(
				'license_not_found'  => __( 'This license key was not found. Copy it again from your GauravTiwari.org account.', 'kumo-hybrid' ),
				'key_mismatch'       => __( 'This key belongs to another product. Enter your Kumo Hybrid license key.', 'kumo-hybrid' ),
				'license_not_active' => __( 'This license is inactive. Contact support from your GauravTiwari.org account.', 'kumo-hybrid' ),
				'license_expired'    => __( 'This license has expired. Get a new free key from the Kumo Hybrid page.', 'kumo-hybrid' ),
			);
			return new WP_Error( 'kumo_hybrid_license_invalid', $messages[ $data['error_type'] ] ?? __( 'This key could not be verified for the Kumo Hybrid.', 'kumo-hybrid' ) );
		}
		if ( $code < 200 || $code >= 300 || ! is_array( $data ) ) {
			return new WP_Error( 'kumo_hybrid_license_response', __( 'The license server could not verify this request. Please try again.', 'kumo-hybrid' ) );
		}
		return $data;
	}

	/**
	 * Normalize server status without accepting contradictory success flags.
	 *
	 * @param array $data Server response.
	 * @return string
	 */
	private static function status( $data ) {
		$status = $data['status'] ?? $data['license'] ?? 'invalid';
		if ( isset( $data['success'] ) && ! $data['success'] && 'valid' === $status ) {
			return 'invalid';
		}
		return in_array( $status, array( 'valid', 'expired', 'disabled', 'invalid', 'inactive', 'unregistered' ), true ) ? $status : 'invalid';
	}

	/**
	 * Activate an update key for this website.
	 *
	 * @param string $key Free update key.
	 * @return bool|WP_Error
	 */
	public static function activate( $key ) {
		$key = trim( sanitize_text_field( $key ) );
		if ( '' === $key || strlen( $key ) > 512 ) {
			return new WP_Error( 'kumo_hybrid_license_key', __( 'Enter a valid license key.', 'kumo-hybrid' ) );
		}
		if ( ! empty( self::state()['key'] ) ) {
			return new WP_Error( 'kumo_hybrid_license_existing', __( 'Deactivate the saved license before activating a different key.', 'kumo-hybrid' ) );
		}
		$data = self::request( 'activate_license', array( 'license_key' => $key ) );
		if ( is_wp_error( $data ) ) {
			return $data;
		}
		if ( empty( $data['success'] ) || 'valid' !== self::status( $data ) || empty( $data['activation_hash'] ) ) {
			return new WP_Error( 'kumo_hybrid_license_invalid', __( 'This key could not be activated for the Kumo Hybrid.', 'kumo-hybrid' ) );
		}
		self::save(
			array(
				'key'             => $key,
				'status'          => 'valid',
				'activation_hash' => sanitize_text_field( $data['activation_hash'] ),
				'expires'         => sanitize_text_field( $data['expiration_date'] ?? '' ),
				'checked_at'      => time(),
			)
		);
		self::clear_update();
		return true;
	}

	/**
	 * Refresh saved state while retaining it on transport failure.
	 *
	 * @return bool|WP_Error
	 */
	public static function check() {
		$state = self::state();
		if ( empty( $state['key'] ) ) {
			return new WP_Error( 'kumo_hybrid_license_missing', __( 'No license key is saved.', 'kumo-hybrid' ) );
		}
		$params = $state['activation_hash'] ? array( 'activation_hash' => $state['activation_hash'] ) : array( 'license_key' => $state['key'] );
		$data   = self::request( 'check_license', $params );
		if ( is_wp_error( $data ) ) {
			return $data;
		}
		$state['status']     = self::status( $data );
		$state['checked_at'] = time();
		if ( isset( $data['expiration_date'] ) ) {
			$state['expires'] = sanitize_text_field( $data['expiration_date'] );
		}
		self::save( $state );
		self::clear_update();
		return 'valid' === $state['status'] ? true : new WP_Error( 'kumo_hybrid_license_status', __( 'The saved license is not currently valid. The theme keeps working; updates are paused.', 'kumo-hybrid' ) );
	}

	/**
	 * Disconnect this website without deleting state on failure.
	 *
	 * @return bool|WP_Error
	 */
	public static function deactivate() {
		$state = self::state();
		if ( empty( $state['key'] ) ) {
			return new WP_Error( 'kumo_hybrid_license_missing', __( 'No license key is saved.', 'kumo-hybrid' ) );
		}
		$data = self::request(
			'deactivate_license',
			array(
				'license_key'     => $state['key'],
				'activation_hash' => $state['activation_hash'],
			)
		);
		if ( is_wp_error( $data ) ) {
			return $data;
		}
		if ( empty( $data['success'] ) ) {
			return new WP_Error( 'kumo_hybrid_license_deactivate', __( 'The server could not deactivate this website. Your saved key has been kept so you can retry.', 'kumo-hybrid' ) );
		}
		delete_option( self::OPTION );
		self::clear_update();
		return true;
	}

	/**
	 * Invalidate this theme’s cached update offer.
	 */
	private static function clear_update() {
		delete_transient( self::UPDATE_CACHE );
		$cache = get_site_transient( 'update_themes' );
		if ( is_object( $cache ) ) {
			unset( $cache->response[ self::SLUG ], $cache->no_update[ self::SLUG ] );
			set_site_transient( 'update_themes', $cache );
		}
	}

	/**
	 * Offer newer releases through the native WordPress theme updater.
	 *
	 * @param object $transient WordPress theme update data.
	 * @return object
	 */
	public static function updates( $transient ) {
		if ( ! is_object( $transient ) || empty( $transient->checked[ self::SLUG ] ) ) {
			return $transient;
		}
		$state   = self::state();
		$expired = $state['expires'] && 'lifetime' !== $state['expires'] && strtotime( $state['expires'] ) && strtotime( $state['expires'] ) < time();
		if ( 'valid' !== $state['status'] || empty( $state['key'] ) || $expired ) {
			unset( $transient->response[ self::SLUG ], $transient->no_update[ self::SLUG ] );
			return $transient;
		}
		$data = get_transient( self::UPDATE_CACHE );
		if ( false === $data ) {
			$params = $state['activation_hash'] ? array( 'activation_hash' => $state['activation_hash'] ) : array( 'license_key' => $state['key'] );
			$data   = self::request( 'get_license_version', $params );
			if ( ! is_wp_error( $data ) ) {
				set_transient( self::UPDATE_CACHE, $data, 4 * HOUR_IN_SECONDS );
			}
		}
		if ( is_wp_error( $data ) ) {
			return $transient;
		}
		if ( ! is_array( $data ) || 'valid' !== ( $data['license_status'] ?? '' ) || empty( $data['new_version'] ) || ! version_compare( $data['new_version'], self::version(), '>' ) || empty( $data['package'] ) ) {
			unset( $transient->response[ self::SLUG ] );
			return $transient;
		}
		$package = esc_url_raw( $data['package'] );
		if ( 'https' !== wp_parse_url( $package, PHP_URL_SCHEME ) ) {
			return $transient;
		}
		$transient->response[ self::SLUG ] = array(
			'theme'        => self::SLUG,
			'new_version'  => sanitize_text_field( $data['new_version'] ),
			'url'          => 'https://gauravtiwari.org/product/' . self::SLUG . '/',
			'package'      => $package,
			'requires'     => sanitize_text_field( $data['requires'] ?? '6.7' ),
			'requires_php' => sanitize_text_field( $data['requires_php'] ?? '8.3' ),
		);
		return $transient;
	}

	/**
	 * Register the administrator-facing update-key page.
	 */
	public static function menu() {
		add_theme_page( __( 'Kumo Hybrid License', 'kumo-hybrid' ), __( 'Kumo Hybrid License', 'kumo-hybrid' ), 'edit_theme_options', self::PAGE, array( __CLASS__, 'page' ) );
	}

	/**
	 * Show a settings link only on relevant administrator screens.
	 */
	public static function reminder() {
		if ( ! current_user_can( 'edit_theme_options' ) || ! empty( self::state()['key'] ) ) {
			return;
		}
		$screen = get_current_screen();
		if ( ! $screen || ! in_array( $screen->id, array( 'themes', 'update-core', 'dashboard' ), true ) ) {
			return;
		}
		printf(
			'<div class="notice notice-info"><p>%s <a href="%s">%s</a></p></div>',
			esc_html__( 'Activate your free Kumo Hybrid license key to receive theme updates.', 'kumo-hybrid' ),
			esc_url( admin_url( 'themes.php?page=' . self::PAGE ) ),
			esc_html__( 'Add License Key', 'kumo-hybrid' )
		);
	}

	/**
	 * Handle authorized and nonce-protected settings actions.
	 */
	public static function handle() {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_die( esc_html__( 'You cannot manage this theme license.', 'kumo-hybrid' ), '', array( 'response' => 403 ) );
		}
		check_admin_referer( 'kumo_hybrid_license' );
		$action = sanitize_key( wp_unslash( $_POST['license_action'] ?? '' ) );
		if ( 'activate' === $action ) {
			$result = self::activate( sanitize_text_field( wp_unslash( $_POST['license_key'] ?? '' ) ) );
		} elseif ( 'check' === $action ) {
			$result = self::check();
		} elseif ( 'deactivate' === $action ) {
			$result = self::deactivate();
		} else {
			$result = new WP_Error( 'kumo_hybrid_license_action', __( 'Choose a license action.', 'kumo-hybrid' ) );
		}
		$labels  = array(
			'activate'   => __( 'License activated. Theme updates are on.', 'kumo-hybrid' ),
			'deactivate' => __( 'License deactivated on this website.', 'kumo-hybrid' ),
			'check'      => __( 'License verified.', 'kumo-hybrid' ),
		);
		$message = is_wp_error( $result ) ? $result->get_error_message() : $labels[ $action ];
		set_transient(
			self::NOTICE . '_' . get_current_user_id(),
			array(
				'error'   => is_wp_error( $result ),
				'message' => $message,
			),
			MINUTE_IN_SECONDS
		);
		wp_safe_redirect( admin_url( 'themes.php?page=' . self::PAGE ) );
		exit;
	}

	/**
	 * Render the update-key settings page for authorized administrators.
	 */
	public static function page() {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}
		$state  = self::state();
		$notice = get_transient( self::NOTICE . '_' . get_current_user_id() );
		delete_transient( self::NOTICE . '_' . get_current_user_id() );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Kumo Hybrid License', 'kumo-hybrid' ); ?></h1>
			<?php if ( $notice ) : ?>
				<div class="notice <?php echo $notice['error'] ? 'notice-error' : 'notice-success'; ?>"><p><?php echo esc_html( $notice['message'] ); ?></p></div>
			<?php endif; ?>
			<p><?php esc_html_e( 'Your free license key turns on theme updates. It is lifetime and works on any number of websites. The theme works fully without it.', 'kumo-hybrid' ); ?></p>
			<table class="form-table" role="presentation">
				<tr><th scope="row"><?php esc_html_e( 'Status', 'kumo-hybrid' ); ?></th><td><strong><?php echo esc_html( ucfirst( $state['status'] ) ); ?></strong></td></tr>
				<tr><th scope="row"><?php esc_html_e( 'Installed version', 'kumo-hybrid' ); ?></th><td><?php echo esc_html( self::version() ); ?></td></tr>
				<tr><th scope="row"><?php esc_html_e( 'Website', 'kumo-hybrid' ); ?></th><td><?php echo esc_html( home_url( '/' ) ); ?></td></tr>
			</table>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="kumo_hybrid_license">
				<?php wp_nonce_field( 'kumo_hybrid_license' ); ?>
				<?php if ( ! $state['key'] ) : ?>
					<p><label for="kumo-hybrid-license-key"><strong><?php esc_html_e( 'License key', 'kumo-hybrid' ); ?></strong></label></p>
					<p><input id="kumo-hybrid-license-key" name="license_key" type="password" class="regular-text" autocomplete="off" spellcheck="false" maxlength="512" required></p>
					<p class="description">
						<?php esc_html_e( 'Find your key under Licenses in your GauravTiwari.org account. It is sent securely to GauravTiwari.org to verify this website.', 'kumo-hybrid' ); ?>
						<a href="<?php echo esc_url( self::ACCOUNT ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Open my licenses', 'kumo-hybrid' ); ?></a>
					</p>
					<p><button class="button button-primary" name="license_action" value="activate"><?php esc_html_e( 'Activate License', 'kumo-hybrid' ); ?></button></p>
				<?php else : ?>
					<p><?php esc_html_e( 'A license key is saved. It is hidden for security.', 'kumo-hybrid' ); ?></p>
					<p>
						<button class="button button-primary" name="license_action" value="check"><?php esc_html_e( 'Check License', 'kumo-hybrid' ); ?></button>
						<button class="button" name="license_action" value="deactivate"><?php esc_html_e( 'Deactivate License', 'kumo-hybrid' ); ?></button>
					</p>
				<?php endif; ?>
			</form>
		</div>
		<?php
	}
}
