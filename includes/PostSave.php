<?php

namespace SUPT\ScheduleBlock;

use DateTime;
use DateTimeZone;

// Exit if accessed directly.
defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

class PostSave {
	/**
	 * ScheduleBlock ParseBlock constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'wp_insert_post', [$this, 'trigger_block_with_schedule_action_on_post_save'], 10, 2 );
	}

	function trigger_block_with_schedule_action_on_post_save( $post_id, $post ) {

		$blocks = parse_blocks($post->post_content);

		foreach ($blocks as $block) {
			// Bail if block doesn't have schedule attrs
			if ( !isset($block['attrs']['schedule']) ) continue;

			do_action( 'schedule_block-save_post-block-with-schedule', $block['attrs']['schedule'], $block, $post_id );

			$now = new DateTime();
			$now->setTimezone( new DateTimeZone( 'Z' ) );

			if ( !empty($block['attrs']['schedule']['start']) ) {
				$start = new DateTime( $block['attrs']['schedule']['start'] );
				if ( $start > $now ) {
					do_action( 'schedule_block-save_post-block_with-start_time', $block['attrs']['schedule']['start'], $block, $post_id );
				}
			}

			if ( !empty($block['attrs']['schedule']['end']) ) {
				$end = new DateTime( $block['attrs']['schedule']['end'] );
				if ( $end > $now ) {
					do_action( 'schedule_block-save_post-block_with-end_time', $block['attrs']['schedule']['end'], $block, $post_id );
				}
			}
		}
	}
}



