import React from 'react'

import useClasses from '@/Shared/Hooks/useClasses'

const Headline = ({ level = '1', className = '', children, ...props }) => {
    const Component = `h${level}`

    const classes = useClasses(
        'text-gray-50 text-2xl tracking-tight font-semibold',
        className
    )

    return (
        <Component className={classes} {...props}>
            {children}
        </Component>
    )
}

export default Headline
