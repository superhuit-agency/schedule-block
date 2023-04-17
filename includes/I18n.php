<?php

namespace SUPT\ScheduleBlock;

class I18n {
	function __construct() {
		add_action( 'init', [$this, 'load_plugin_textdomain'] );
		add_action( 'switch_locale', [$this, 'load_plugin_textdomain'] );
		add_action( 'enqueue_block_editor_assets', [$this, 'load_plugin_scripts_translations'], 11 );
	}

	function load_plugin_textdomain() {
		load_plugin_textdomain( 'schedule-block', false, 'schedule-block/languages' );
	}

	function load_plugin_scripts_translations() {
		wp_set_script_translations('schedule-block-editor-script', 'schedule-block', SCHEDULE_BLOCK_PATH .'/languages');
	}
}
