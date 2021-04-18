import React from 'react'

import useClasses from '@/Shared/Hooks/useClasses'

const FormInput = ({ error, ...props }) => {
    const classes = useClasses(
        'block w-full bg-gray-600 text-white px-4 py-3 leading-tight rounded-md focus:outline-none focus:ring-2 focus:ring-green-300',
        {
            'border-0': !error,
            'border-1 border-red-300': error,
        }
    )

    return <input className={classes} {...props} />
}

export default FormInput
