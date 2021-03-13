import React from 'react'

import useProps from '@/shared/hooks/useProps'

import Box from '@/shared/components/base/Box'
import Text from '@/shared/components/base/Text'

import Header from '@/shared/components/partials/Header'
import FlashMessage from '@/shared/components/partials/FlashMessage'

const Base = ({ children, ...props }) => {
    const { flash } = useProps()

    return (
        <Box>
            <Header />

            <Box Component="main">{children}</Box>
            <FlashMessage text={flash.message || ''} />
        </Box>
    )
}

export default Base
