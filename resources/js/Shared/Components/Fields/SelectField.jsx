import React from 'react'

import useClasses from '@/Shared/Hooks/useClasses'

import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import FormSelect from '@/Shared/Components/FormElements/FormSelect'
import FormError from '@/Shared/Components/FormElements/FormError'
import FormLabel from '@/Shared/Components/FormElements/FormLabel'

const TextField = ({
    name,
    label,
    error,
    containerClassName,
    options = [],
    ...props
}) => {
    const containerClasses = useClasses('relative', containerClassName)

    return (
        <FormGroup className={containerClasses}>
            <FormLabel input={name}>{label}</FormLabel>
            <FormSelect
                id={name}
                name={name}
                error={!!error}
                options={options}
                {...props}
            />

            {error && <FormError className="absolute mt-1">{error}</FormError>}
        </FormGroup>
    )
}

export default TextField
