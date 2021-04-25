import React from 'react'

import Icon from '@/Shared/Components/Leafs/Icon'

const IconButton = ({ type, name, onClick }) => (
    <button
        type={type}
        onClick={onClick}
        className="flex items-center justify-center w-8 h-8 bg-gray-800 rounded-full shadow focus:outline-none focus:ring-2 focus:ring-cyan-300"
    >
        <Icon name={name} className="text-gray-300" />
    </button>
)

export default IconButton
