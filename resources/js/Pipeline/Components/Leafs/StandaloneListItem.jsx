import React from 'react'

import useClasses from '@/Shared/Hooks/useClasses'

import ListItem from '@/Shared/Components/Leafs/ListItem'

const StandaloneListItem = ({ children, className, ...props }) => {
    const classes = useClasses('rounded-lg py-6', className)

    return (
        <ListItem className={classes} {...props}>
            {children}
        </ListItem>
    )
}

export default StandaloneListItem
