import React from 'react'

import Box from '@/shared/components/base/Box'

import { HiMenuAlt3 } from 'react-icons/hi'

const icons = {
    HiMenuAlt3,
}

const Icon = ({ name, ...props }) => {
    const Component = icons[name]
    return <Box Component={Component} {...props} />
}

export default Icon
