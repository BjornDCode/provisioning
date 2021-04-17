import React from 'react'

import useProps from '@/Shared/Hooks/useProps'

import Header from '@/Shared/Components/Partials/Header'
import FlashMessage from '@/Shared/Components/Partials/FlashMessage'

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
