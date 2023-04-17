<?php

namespace SUPT\ScheduleBlock;

// Exit if accessed directly.
defined( 'ABSPATH' ) or die( 'Cheatin&#8217; uh?' );

class RenderBlock {
	/**
	 * ScheduleBlock RenderBlock constructor.
	 *
	 * @return object|RenderBlock - The one true RenderBlock
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		add_filter( 'render_block', [$this, 'render_block'], 10, 2 );
	}

	/**
	 * Check if the given block has schedule settings.
	 *
	 * @since 1.0.0
	 *
	 * @param string $block_content The block frontend output.
	 * @param array  $block         The block info and attributes.
	 * @return mixed                Return either the $block_content or nothing depending on schedule settings.
	 */
	function render_block( $block_content, $block ) {
		$is_visible = schedule_block_is_block_visible( $block );

		return $is_visible ? $block_content : '';
	}
}



