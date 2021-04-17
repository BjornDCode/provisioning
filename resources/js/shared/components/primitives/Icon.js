import React from 'react'

import { match } from '@/shared/helpers/methods'

import { HiMenuAlt3, HiX } from 'react-icons/hi'

const icons = {
    Menu: HiMenuAlt3,
    Close: HiX,
}

const Icon = ({ name, ...props }) => {
    const Component = match(name, icons)

    return <Component {...props} />
}

export default Icon
