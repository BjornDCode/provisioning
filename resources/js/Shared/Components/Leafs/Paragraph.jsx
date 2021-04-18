import React from 'react'

import useClasses from '@/Shared/Hooks/useClasses'

const Paragraph = ({ children, className, ...props }) => {
    const classes = useClasses('text-white', className)

    return (
        <p className={classes} {...props}>
            {children}
        </p>
    )
}

export default Paragraph
