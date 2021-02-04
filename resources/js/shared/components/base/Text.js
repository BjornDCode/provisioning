import React from 'react'

import Box from '@/shared/components/base/Box'

const Text = ({ children, Component = 'span', ...props }) => (
    <Box Component={Component} {...props}>
        {children}
    </Box>
)

export default Text
