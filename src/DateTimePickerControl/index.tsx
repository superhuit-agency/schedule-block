import 'react-datepicker/dist/react-datepicker.css';
import './style.css';

// @ts-ignore
import React, { FC, forwardRef, useMemo } from 'react'

import ReactDatePicker, { ReactDatePickerProps, registerLocale } from "react-datepicker";
import { BaseControl, Icon } from '@wordpress/components';
import fr from 'date-fns/locale/fr';
import { BlockAttribute } from '@wordpress/blocks';


type DateTimePickerControl = Omit<ReactDatePickerProps, "selected"> & {
	label: string;
	date?: Date | null | undefined;
}

export const DatetimePickerButton = forwardRef(({ title, value, onClick }:any, ref) => {
	return (
		<button
			ref={ref}
			type="button"
			title={title}
			className="components-button is-link has-text has-icon"
			onClick={onClick}
		>
			<Icon icon="calendar-alt" />
    	<span>{value}</span>
		</button>
	)
})

export const DateTimePickerControl: FC<DateTimePickerControl> =
({ id, label, date, onChange, ...props}) => {
	registerLocale('fr', fr)

	const selectedDate = useMemo(() => {
		let selected = null
		if ( typeof date === 'string' ) {
			if ( date !== '' ) selected = new Date(date);
		}
		else selected = date

		return selected
	}, [date])

	return (
		<BaseControl
			id={id}
			label={ label }
			className="supt-datetimepickercontrol"
		>
			<ReactDatePicker
				selected={ selectedDate }
				showTimeSelect
				onChange={ (newDate: Date) => onChange(newDate?.toISOString() ?? '') }
				portalId="root-portal"
				locale="fr"
				dateFormat="Pp"
				timeFormat="p"
				timeIntervals={15}

				customInput={ <DatetimePickerButton title="Choose a date/time" /> }

				{...props}
			/>
		</BaseControl>
	)
}
