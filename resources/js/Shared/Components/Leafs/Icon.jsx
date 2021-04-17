import React from 'react'
import { HiMenuAlt3, HiX } from 'react-icons/hi'

import { match } from '@/Shared/Helpers/methods'

const icons = {
    Menu: HiMenuAlt3,
    Close: HiX,
}

const Icon = ({ name, ...props }) => {
    const Component = match(name, icons)

    return <Component {...props} />
}

export default Icon
