import React from 'react'
import { InertiaLink } from '@inertiajs/inertia-react'

import useClasses from '@/Shared/Hooks/useClasses'

import Icon from '@/Shared/Components/Leafs/Icon'
import ListItem from '@/Shared/Components/Leafs/ListItem'
import ListItemText from '@/Shared/Components/Leafs/ListItemText'

const Link = ({ children, to, className, ...props }) => (
    <InertiaLink
        className={useClasses(
            'focus:rounded focus:outline-none focus:ring-2 focus:ring-cyan-200 focus:ring-inset',
            className
        )}
        href={to}
        {...props}
    >
        {children}
    </InertiaLink>
)

const Right = () => (
    <Icon
        name="ChevronRight"
        className="text-gray-400 group-hover:text-gray-700"
    />
)

const Text = ({ text }) => (
    <ListItemText className="group-hover:text-gray-800">{text}</ListItemText>
)

const ListItemLink = ({ to, children }) => (
    <ListItem
        as={Link}
        to={to}
        Right={Right}
        Text={Text}
        className="group hover:bg-green-400"
    >
        {children}
    </ListItem>
)

export default ListItemLink
