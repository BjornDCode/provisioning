import React from 'react'

import useClasses from '@/Shared/Hooks/useClasses'

import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import FormInput from '@/Shared/Components/FormElements/FormInput'
import FormError from '@/Shared/Components/FormElements/FormError'
import FormLabel from '@/Shared/Components/FormElements/FormLabel'

const TextField = ({
    type = 'text',
    name,
    label,
    error,
    containerClassName,
    ...props
}) => {
    const containerClasses = useClasses('relative', containerClassName)

    return (
        <FormGroup className={containerClasses}>
            <FormLabel input={name}>{label}</FormLabel>
            <FormInput
                type={type}
                id={name}
                name={name}
                error={!!error}
                {...props}
            />

            {error && <FormError className="absolute mt-1">{error}</FormError>}
        </FormGroup>
    )
}

export default TextField
