import React from 'react'

import useProps from '@/shared/hooks/useProps'

import Header from '@/shared/components/partials/Header'
import FlashMessage from '@/shared/components/partials/FlashMessage'

const Base = ({ children, ...props }) => {
    const { flash } = useProps()

    return (
        <div>
            <Header />

            <main>{children}</main>
            <FlashMessage text={flash.message || ''} />
        </div>
    )
}

export default Base
