import React, { Fragment } from 'react'
import { Listbox } from '@headlessui/react'

import useClasses from '@/Shared/Hooks/useClasses'

import Icon from '@/Shared/Components/Leafs/Icon'

const FormSelect = ({
    id,
    name,
    value,
    options = [],
    onChange,
    error = false,
}) => {
    const classes = useClasses(
        'flex justify-between items-center w-full text-left bg-gray-600 text-white px-4 py-3 leading-tight rounded-md focus:outline-none focus:ring-2 focus:ring-green-300',
        {
            'border-0': !error,
            'border-1 border-red-300': error,
        }
    )

    const handleChange = value => {
        onChange({
            target: {
                name,
                value,
            },
        })
    }

    const label = (options.find(option => option.key === value) || {}).label

    return (
        <Listbox
            value={value}
            onChange={handleChange}
            as="div"
            className="relative"
        >
            <Listbox.Button className={classes}>
                <span>{label}</span>
                <Icon name="ChevronDown" />
            </Listbox.Button>
            <Listbox.Options className="w-full absolute z-40 top-full mt-4 divide-y divide-gray-700 focus:outline-none">
                {options.map(option => (
                    <Listbox.Option
                        key={option.key}
                        value={option.key}
                        className={({ active }) =>
                            useClasses(
                                'px-4 py-3 text-sm font-medium text-white cursor-pointer flex justify-between items-center first:rounded-t-md last:rounded-b-md bg-gray-600',
                                {
                                    'ring-2 ring-green-300 ring-inset': active,
                                }
                            )
                        }
                    >
                        {({ selected }) => (
                            <Fragment>
                                <span>{option.label}</span>
                                {selected && <Icon name="Checkmark" />}
                            </Fragment>
                        )}
                    </Listbox.Option>
                ))}
            </Listbox.Options>
        </Listbox>
    )
}

export default FormSelect
