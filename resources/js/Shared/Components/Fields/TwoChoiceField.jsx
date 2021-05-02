import React, { Fragment } from 'react'
import { RadioGroup } from '@headlessui/react'

import useClasses from '@/Shared/Hooks/useClasses'

import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import FormError from '@/Shared/Components/FormElements/FormError'
import FormLabel from '@/Shared/Components/FormElements/FormLabel'

const TwoChoiceField = ({
    name,
    label,
    error,
    value,
    onChange,
    options = [],
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

            <div className="grid grid-cols-2 gap-4">
                {options.map(option => (
                    <RadioGroup.Option
                        key={option.key}
                        value={option.key}
                        className={({ checked, active }) =>
                            useClasses(
                                'group rounded-md flex justify-between items-center bg-gray-600 px-6 py-8 cursor-pointer hover:ring-1 hover:ring-inset hover:ring-green-300',
                                {
                                    'border-2 border-green-300': checked,
                                    'outline-none ring-1 ring-inset ring-green-300': active,
                                }
                            )
                        }
                    >
                        {({ checked }) => (
                            <Fragment>
                                <span
                                    className={useClasses('font-medium', {
                                        'text-green-200': checked,
                                        'text-white group-hover:text-green-200': !checked,
                                    })}
                                >
                                    {option.label}
                                </span>
                                <div
                                    className={useClasses(
                                        'w-4 h-4 rounded-full',
                                        {
                                            'bg-green-300': checked,
                                            'bg-gray-700 group-hover:ring-1 group-hover:ring-green-300': !checked,
                                        }
                                    )}
                                ></div>
                            </Fragment>
                        )}
                    </RadioGroup.Option>
                ))}
            </div>

            {error && <FormError className="absolute mt-1">{error}</FormError>}
        </RadioGroup>
    )
}

export default TwoChoiceField
