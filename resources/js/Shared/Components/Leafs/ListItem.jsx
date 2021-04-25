import React from 'react'

import useClasses from '@/Shared/Hooks/useClasses'

import ListItemText from '@/Shared/Components/Leafs/ListItemText'

const ListItem = ({
    as = 'li',
    Right,
    Text = ({ text }) => <ListItemText>{text}</ListItemText>,
    className,
    children,
    ...props
}) => {
    const Component = as
    const computedProps = {
        ...(as !== 'li' ? { role: 'listitem' } : {}),
    }

    const classes = useClasses(
        'flex justify-between items-center px-8 py-4 bg-gray-600',
        className
    )

    return (
        <Component className={classes} {...computedProps} {...props}>
            {Text({ text: children })}
            {Right && Right()}
        </Component>
    )
}

export default ListItem
