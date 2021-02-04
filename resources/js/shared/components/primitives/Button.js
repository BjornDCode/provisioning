import React from 'react'

import Box from '@/shared/components/base/Box'

const Button = ({ children, ...props }) => (
    <Box Component="button" {...props}>
        {children}
    </Box>
)

export default Button
