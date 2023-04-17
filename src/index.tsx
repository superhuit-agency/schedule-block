import './style.css';

// @ts-ignore
import React, { useMemo } from 'react'

import { sprintf, __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { Notice, PanelBody } from '@wordpress/components';
import { BlockConfiguration } from '@wordpress/blocks';
import { addFilter, applyFilters } from '@wordpress/hooks'
import { InspectorControls } from '@wordpress/block-editor';
import { createHigherOrderComponent } from '@wordpress/compose';

import { DateTimePickerControl } from './DateTimePickerControl'

export const isValidBlockType = ( blockName: string ) => {
	const blocksToAddSchedule: Array<string> = applyFilters( 'scheduleBlock.blocksToAddSchedule', [] ) as Array<string>;
	return blocksToAddSchedule.includes( blockName );
}

export const addScheduleAttributes = (settings: BlockConfiguration,	name: string) => {
	return isValidBlockType(name)
		? {
				...settings,
				attributes: {
					...settings.attributes,
					schedule: {
						type: 'object'
					}
				}
			}
		: settings;
}

type ScheduleType = {
	start?: string;
	end?: string;
}

export const ScheduleNotice = ( schedule: ScheduleType ) => {
	if ( schedule?.start || schedule?.end ) {
		const displayDateRange = useMemo(() => {
			const range = [];
			if ( schedule.start ) {
				const start = new Date(schedule.start);
				// translators: %s: Date string.
				range.push( sprintf( __( 'from %s', 'schedule-block'), start.toLocaleString('fr-CH') ) );
			}

			if ( schedule.end ) {
				const end = new Date(schedule.end);
				// translators: %s: Date string.
				range.push( sprintf( __( 'until %s', 'schedule-block'), end.toLocaleString('fr-CH') ) );
			}
			return range.join(' ');
		}, [schedule])

		return (
			<Notice status="warning" isDismissible={false} className="schedule-block__block-notice">
				{
					// translators: %s: Range of dates from ... until ... .
					sprintf( __('Schedule options set to display this section %s', 'schedule-block'), displayDateRange)
				}
			</Notice>
		)
	}

	return null;
}

export const addScheduleNotice = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		// If this block supports scheduling and is currently selected, add our UI
		if ( isValidBlockType( props.name ) ) {
			return (
				<Fragment>
					<ScheduleNotice { ...props.attributes?.schedule ?? {} } />
					<BlockEdit { ...props } />
				</Fragment>
			)
		}

		return <BlockEdit { ...props } />;
	}
}, 'addScheduleNotice');

/**
 * Override the default edit UI to include a new block inspector control for
 * adding our custom control.
 *
 * @param {function|Component} BlockEdit Original component.
 *
 * @return {string} Wrapped component.
 */
export const addSchedulePanelControls = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		// If this block supports scheduling and is currently selected, add our UI
		if ( isValidBlockType( props.name ) && props.isSelected ) {
			return (
				<Fragment>
					<BlockEdit { ...props } />
					<InspectorControls>
						<PanelBody title={ __( 'Schedule', 'schedule-block' ) } initialOpen={false}>
							<DateTimePickerControl
								id="schedule-start"
								label={ __( 'Start showing', 'schedule-block' ) }
								date={ props.attributes?.schedule?.start ?? null }
								onChange={ ( newDate: string ) => props.setAttributes( { schedule: { ...props.attributes?.schedule ?? {}, start: newDate}} ) }
								isClearable
							/>
							<DateTimePickerControl
								id="schedule-end"
								label={ __( 'End showing', 'schedule-block' ) }
								date={ props.attributes?.schedule?.end ?? null }
								onChange={ ( newDate: string ) => props.setAttributes( { schedule: { ...props.attributes?.schedule ?? {}, end: newDate}} ) }
								isClearable
							/>
						</PanelBody>
					</InspectorControls>
				</Fragment>
			);
		}

		return <BlockEdit { ...props } />;
	}
}, 'addSchedulePanelControls' );

addFilter( 'blocks.registerBlockType', 'schedule-block/add-schedule-attributes', addScheduleAttributes );
addFilter( 'editor.BlockEdit', 'schedule-block/schedule-notice', addScheduleNotice );
addFilter( 'editor.BlockEdit', 'schedule-block/schedule-controls-panel', addSchedulePanelControls );
