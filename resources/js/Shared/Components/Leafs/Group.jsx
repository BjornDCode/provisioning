import React from 'react'

import Headline from '@/Shared/Components/Leafs/Headline'

const Group = ({ title, children }) => {
    return (
        <div className="space-y-4">
            <Headline level="2">{title}</Headline>
            {children}
        </div>
    )
}

export default Group
