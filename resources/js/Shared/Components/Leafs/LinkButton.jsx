import React from 'react'

import Link from '@/Shared/Components/Leafs/Link'
import Button from '@/Shared/Components/Leafs/Button'

const LinkButton = ({ as = Link, children, ...props }) => {
    const Component = as

    return (
        <Button as={Component} {...props}>
            {children}
        </Button>
    )
}

export default LinkButton
