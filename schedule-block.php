<?php
/**
 * Plugin Name:       Schedule block
 * Description:       Add scheduling options to WordPress blocks (Gutenberg).
 * Author:            superhuit
 * Author URI:        https://www.superhuit.ch
 * Version:           1.0.0
 * Requires PHP:      7.4
 * Text Domain:       schedule-block
 * Requires at least: 5.0
 * Tested up to:      6.2
 *
 * @package ScheduleBlock
 * @category Core
 * @author Superhuit, Kuuak
 * @version 1.0.0
 */

use SUPT\ScheduleBlock\Editor;
use SUPT\ScheduleBlock\I18n;
use SUPT\ScheduleBlock\PostSave;
use SUPT\ScheduleBlock\RenderBlock;

// Exit if accessed directly.
defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

define( 'SCHEDULE_BLOCK_PLUGIN_VERSION', '1.0.0' );
define( 'SCHEDULE_BLOCK_PATH', __DIR__ );
define( 'SCHEDULE_BLOCK_URL', plugin_dir_url(__FILE__) );

// Load dependencies
// ====
if ( ! file_exists(__DIR__ .'/vendor/autoload.php') ) {
	add_action( 'admin_notices', function() {
		?>
		<div class="notice notice-error">
			<p><?php _e( 'Please install composer dependencies for Schedule Block plugin to work', 'schedule-block' ); ?></p>
		</div>
		<?php
	} );
	return;
}

require_once __DIR__ . '/vendor/autoload.php';

new I18n();
new Editor();
new RenderBlock();
new PostSave();

// ====
// Action & filter hooks
// ====
register_activation_hook( __FILE__, 'schedule_block_activate' );
register_deactivation_hook( __FILE__, 'schedule_block_deactivate' );
register_uninstall_hook(__FILE__, 'schedule_block_uninstall');

/**
 * Execute anything necessary on plugin activation
 */
function schedule_block_activate() {
	// e.g. Save default options to database
}

/**
 * Execute anything necessary on plugin deactivation
 */
function schedule_block_deactivate() {
	// e.g. delete cache or temp options
}

/**
 * Execute anything necessary on plugin uninstall (deletion)
 */
function schedule_block_uninstall() {
	// e.g. remove plugin options from database
}


/**
 * API functions
 */

/**
 * Check if given block should be visible or not.
 *
 * @since 1.0.0
 *
 * @param WP_Block_Parser_Block $block.
 * @return boolean
 */
function schedule_block_is_block_visible( $block ) {
	$is_visible = true;

	if ( isset($block['attrs']['schedule']) ) {
		$now = new DateTime();
		$now->setTimezone( new DateTimeZone( 'Z' ) );

		if ( !empty($block['attrs']['schedule']['start']) ) {
			$start = new DateTime( $block['attrs']['schedule']['start'] );
			$is_visible = $now >= $start;
		}

		if ( $is_visible && !empty($block['attrs']['schedule']['end']) ) {
			$end = new DateTime( $block['attrs']['schedule']['end'] );
			$is_visible = $now < $end;
		}
	}

	return $is_visible;
}
