import React from 'react'

import { match } from '@/shared/helpers/methods'

import Box from '@/shared/components/base/Box'

import { HiMenuAlt3, HiX } from 'react-icons/hi'

const icons = {
    Menu: HiMenuAlt3,
    Close: HiX,
}

const Icon = ({ name, ...props }) => {
    const Component = match(name, icons)

    return <Box Component={Component} {...props} />
}

export default Icon
