import React from 'react'

import useClasses from '@/Shared/Hooks/useClasses'

import List from '@/Shared/Components/Leafs/List'

const StandaloneList = ({ className, children, ...props }) => {
    const classes = useClasses('space-y-8', className)

    return (
        <ul className={classes} {...props}>
            {children}
        </ul>
    )
}

export default StandaloneList
