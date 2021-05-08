import React, { Fragment } from 'react'
import { RadioGroup } from '@headlessui/react'

import useClasses from '@/Shared/Hooks/useClasses'

import Paragraph from '@/Shared/Components/Leafs/Paragraph'

import List from '@/Shared/Components/Leafs/List'
import ListItemRadio from '@/Shared/Components/FormElements/ListItemRadio'

import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import FormError from '@/Shared/Components/FormElements/FormError'
import FormLabel from '@/Shared/Components/FormElements/FormLabel'

const RadioGridField = ({
    name,
    label,
    error,
    value,
    empty = 'No options',
    onChange,
    options = [],
    ...props
}) => {
    const handleChange = value => {
        // Simulate DOM event
        onChange({
            target: {
                name,
                value,
            },
        })
    }

    return (
        <RadioGroup
            as={FormGroup}
            className="relative"
            name={name}
            value={value}
            onChange={handleChange}
        >
            <RadioGroup.Label as={FormLabel}>{label}</RadioGroup.Label>
            {options.length === 0 && (
                <div className="bg-gray-600 rounded-md p-8">
                    <Paragraph>{empty}</Paragraph>
                </div>
            )}

            {options.length > 0 && (
                <List>
                    {options.map(option => (
                        <ListItemRadio key={option.key} value={option.key}>
                            {option.label}
                        </ListItemRadio>
                    ))}
                </List>
            )}
            {error && <FormError className="absolute mt-1">{error}</FormError>}
        </RadioGroup>
    )
}

export default RadioGridField
