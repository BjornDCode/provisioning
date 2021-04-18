import React from 'react'

import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import FormInput from '@/Shared/Components/FormElements/FormInput'
import FormLabel from '@/Shared/Components/FormElements/FormLabel'

const SingleCheckboxField = ({ label, name, ...props }) => (
    <FormGroup className="flex items-center">
        <input
            type="checkbox"
            id={name}
            name={name}
            className="border-0 rounded text-green-500 focus:ring-2 focus:ring-cyan-300 focus:ring-offset-gray-700"
            {...props}
        />
        <label
            htmlFor={name}
            className="block text-gray-100 font-medium text-sm ml-2"
        >
            {label}
        </label>
    </FormGroup>
)

export default SingleCheckboxField
