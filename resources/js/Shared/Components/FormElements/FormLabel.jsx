import React from 'react'

import useClasses from '@/Shared/Hooks/useClasses'

const FormLabel = ({ children, input, className, ...props }) => {
    const classes = useClasses(
        'block text-gray-100 font-medium text-xs mb-1',
        className
    )

    return (
        <label htmlFor={input} className={classes} {...props}>
            {children}
        </label>
    )
}

export default FormLabel
