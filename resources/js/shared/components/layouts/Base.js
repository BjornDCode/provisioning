import React from 'react'

import useProps from '@/shared/hooks/useProps'

import Box from '@/shared/components/base/Box'
import Text from '@/shared/components/base/Text'
import Header from '@/shared/components/partials/Header'

const Base = ({ children, ...props }) => {
    const { flash } = useProps()

    return (
        <Box>
            <Header />

            <Box Component="main">{children}</Box>
            {flash.message && <Text>{flash.message}</Text>}
        </Box>
    )
}

export default Base
