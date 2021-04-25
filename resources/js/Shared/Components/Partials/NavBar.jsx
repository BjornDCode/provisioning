import React from 'react'

import useClasses from '@/Shared/Hooks/useClasses'

const NavBar = ({ children, className }) => {
    const classes = useClasses('flex items-center space-x-1', className)

    return <nav className={classes}>{children}</nav>
}

export default NavBar
