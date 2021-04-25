import React from 'react'

const List = ({ as = 'ul', children }) => {
    const Component = as
    const computedProps = {
        ...(as !== 'ul' ? { role: 'list' } : {}),
    }

    return (
        <Component
            className="rounded-lg overflow-hidden divide-y divide-gray-700"
            {...computedProps}
        >
            {children}
        </Component>
    )
}

export default List
