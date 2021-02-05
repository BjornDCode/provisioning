import React from 'react'

import useUnderBreakpoint from '@/shared/hooks/useUnderBreakpoint'

import Stack from '@/shared/components/layouts/Stack'
import Shelf from '@/shared/components/layouts/Shelf'

const StackShelf = ({ breakpoint = 'md', children, ...props }) => {
    const under = useUnderBreakpoint(breakpoint)
    const Component = under ? Stack : Shelf

    return <Component {...props}>{children}</Component>
}

export default StackShelf
