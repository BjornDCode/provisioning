import React from 'react'

import useClasses from '@/shared/hooks/useClasses'

import { InertiaLink } from '@inertiajs/inertia-react'

const Link = ({ children, to = '#', className, ...props }) => {
    const classes = useClasses(
        'focus:rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-700 focus:ring-cyan-500',
        className
    )

    return (
        <InertiaLink href={to} className={classes} {...props}>
            {children}
        </InertiaLink>
    )
}
export default Link
