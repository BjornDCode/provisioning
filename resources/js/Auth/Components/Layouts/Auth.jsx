import React from 'react'

import Base from '@/Shared/Components/Layouts/Base'
import Headline from '@/Shared/Components/Leafs/Headline'

const Auth = ({ title, children, ...props }) => (
    <Base>
        <div className="px-2 py-6 md:px-8 md:py-12 md:max-w-md md:mx-auto">
            <Headline className="mb-8" {...props}>
                {title}
            </Headline>
            {children}
        </div>
    </Base>
)

export default Auth
