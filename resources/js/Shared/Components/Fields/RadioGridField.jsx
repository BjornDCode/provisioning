import React, { Fragment } from 'react'
import { RadioGroup } from '@headlessui/react'

import useClasses from '@/Shared/Hooks/useClasses'

import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import FormError from '@/Shared/Components/FormElements/FormError'
import FormLabel from '@/Shared/Components/FormElements/FormLabel'

import Icon from '@/Shared/Components/Leafs/Icon'

const RadioGridField = ({
    name,
    label,
    error,
    value,
    onChange,
    options = [],
    Header = () => {},
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
            className="relative overflow-hidden"
            name={name}
            value={value}
            onChange={handleChange}
        >
            <RadioGroup.Label as={FormLabel}>{label}</RadioGroup.Label>
            <div className="bg-gray-600 rounded-md">
                {Header({ options })}
                <div className="p-8 grid grid-cols-2 gap-4 md:grid-cols-4">
                    {options.map(option => (
                        <div className="aspect-w-1 aspect-h-1" key={option.key}>
                            <RadioGroup.Option
                                value={option.key}
                                disabled={option.disabled}
                                className={({ checked, active, disabled }) =>
                                    useClasses(
                                        'rounded-md flex flex-col justify-center items-center',
                                        {
                                            'hover:bg-gray-700 hover:cursor-pointer': !disabled,
                                            'hover:cursor-not-allowed': disabled,
                                            'border-2 border-green-300': checked,
                                            'outline-none ring-1 ring-green-300 bg-gray-700': active,
                                        }
                                    )
                                }
                            >
                                {() => (
                                    <Fragment>
                                        {option.disabled && (
                                            <div className="absolute inset-0 rounded-md flex justify-center items-center bg-gray-700 bg-opacity-80">
                                                <span className="font-bold text-xs text-white">
                                                    Coming soon
                                                </span>
                                            </div>
                                        )}
                                        <Icon
                                            name={option.icon}
                                            className="w-10 h-10 text-gray-100 mb-2"
                                        />
                                        <span className="text-sm font-medium text-white">
                                            {option.label}
                                        </span>
                                    </Fragment>
                                )}
                            </RadioGroup.Option>
                        </div>
                    ))}
                </div>
            </div>
            {error && <FormError className="absolute mt-1">{error}</FormError>}
        </RadioGroup>
    )
}

export default RadioGridField
