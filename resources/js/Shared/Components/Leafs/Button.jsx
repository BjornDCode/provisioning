import React from 'react'

import useClasses from '@/Shared/Hooks/useClasses'

const Button = ({
    as = 'button',
    children,
    className,
    variant = 'primary',
    size = 'medium',
    ...props
}) => {
    const Component = as

    const classes = useClasses(
        'block leading-none text-sm font-medium rounded-md shadow focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:ring-offset-2 focus:ring-offset-gray-700',
        {
            'px-10 py-3': size === 'medium',
            'w-full py-4': size === 'large',
        },
        {
            'bg-green-400 text-green-900 hover:bg-green-300 hover:text-green-800':
                variant === 'primary',
            'bg-gray-800 text-gray-300 hover:bg-gray-900':
                variant === 'secondary',
        },
        className
    )

    return (
        <Component className={classes} {...props}>
            {children}
        </Component>
    )
}

export default Button
