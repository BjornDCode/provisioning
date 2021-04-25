import React from 'react'

import useClasses from '@/Shared/Hooks/useClasses'

const Headline = ({ level = '1', className = '', children, ...props }) => {
    const Component = `h${level}`

    const classes = useClasses(
        'text-gray-50 tracking-tight font-semibold',
        {
            'text-2xl': level === '1',
            'text-xl': level === '2',
        },
        className
    )

    return (
        <Component className={classes} {...props}>
            {children}
        </Component>
    )
}

export default Headline
