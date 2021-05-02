import React from 'react'

import Base from '@/Shared/Components/Layouts/Base'

import Headline from '@/Shared/Components/Leafs/Headline'

const Authenticated = ({ title, children }) => {
    return (
        <Base>
            <div className="px-2 py-6 md:px-8 md:py-12 md:max-w-xl md:mx-auto">
                <div className="mb-8">
                    <Headline>{title}</Headline>
                </div>

                <div className="space-y-12">{children}</div>
            </div>
        </Base>
    )
}

export default Authenticated
