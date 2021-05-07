import React from 'react'

import useClasses from '@/Shared/Hooks/useClasses'

const RadioCircle = ({ checked }) => (
    <div
        className={useClasses('w-4 h-4 rounded-full', {
            'bg-green-300': checked,
            'bg-gray-700 group-hover:ring-1 group-hover:ring-green-300': !checked,
        })}
    ></div>
)

export default RadioCircle
