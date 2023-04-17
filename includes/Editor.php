<?php

namespace SUPT\ScheduleBlock;

// Exit if accessed directly.
defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

class Editor {
	/**
	 * ScheduleBlock Editor constructor.
	 *
	 * @return object|Editor - The one true Editor
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		add_action('enqueue_block_editor_assets', [$this, 'enqueue_editor_assets']);
	}

	/**
	 * Enqueue
	 *
	 * @since 1.0.0
	 */
	public function enqueue_editor_assets() {
		// Bail early if file does not exists
		if ( !file_exists(SCHEDULE_BLOCK_PATH .'/dist/editor.js') ) return;

		$script_deps = apply_filters( 'schedule-block-editor-script-deps', [
			'wp-editor', 'wp-blocks', 'wp-dom-ready', 'wp-edit-post',
			'wp-hooks', 'wp-components', 'wp-blocks', 'wp-element',
			'wp-data', 'wp-date', 'wp-i18n', 'wp-api-fetch',
		]);

		wp_register_script( 'schedule-block-editor-script', SCHEDULE_BLOCK_URL .'dist/editor.js', $script_deps, null, true );

		$localize_scripts = apply_filters( 'schedule-block-localize-script', [] );
		if ( !empty($localize_scripts) ) wp_localize_script( 'schedule-block-editor-script', 'schedule-block', $localize_scripts );

		wp_enqueue_script( 'schedule-block-editor-script' );
	}
}



