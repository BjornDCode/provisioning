import React from 'react'

import useClasses from '@/Shared/Hooks/useClasses'

const ListItemText = ({ children, className }) => {
    const classes = useClasses('text-gray-300 font-medium', className)

    return <span className={classes}>{children}</span>
}

export default ListItemText
