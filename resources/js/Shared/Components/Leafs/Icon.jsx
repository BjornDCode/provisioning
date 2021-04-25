import React from 'react'
import {
    HiMenuAlt3,
    HiX,
    HiChevronDown,
    HiChevronRight,
    HiCheck,
} from 'react-icons/hi'

import { match } from '@/Shared/Helpers/methods'

const icons = {
    Menu: HiMenuAlt3,
    Close: HiX,
    ChevronDown: HiChevronDown,
    ChevronRight: HiChevronRight,
    Checkmark: HiCheck,
}

const Icon = ({ name, ...props }) => {
    const Component = match(name, icons)

    return <Component {...props} />
}

export default Icon
