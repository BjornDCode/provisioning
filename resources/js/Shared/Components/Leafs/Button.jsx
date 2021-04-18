import React from 'react'

import useClasses from '@/Shared/Hooks/useClasses'

const Button = ({ children, className, size = 'medium', ...props }) => {
    const classes = useClasses(
        'block bg-green-400 leading-none text-sm font-medium text-green-900 rounded-md shadow focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:ring-offset-2 focus:ring-offset-gray-700',
        {
            'px-10 py-3': size === 'medium',
            'w-full py-4': size === 'large',
        },
        className
    )

    return (
        <button className={classes} {...props}>
            {children}
        </button>
    )
}

export default Button
