import React from 'react'

import useClasses from '@/Shared/Hooks/useClasses'

const FormError = ({ children, className, ...props }) => {
    const classes = useClasses('text-red-300 text-xs', className)

    return (
        <span className={classes} {...props}>
            {children}
        </span>
    )
}

export default FormError
