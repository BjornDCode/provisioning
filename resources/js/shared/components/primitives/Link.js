import React from 'react'

import { InertiaLink } from '@inertiajs/inertia-react'

const Link = ({ children, to = '#', ...props }) => (
    <InertiaLink href={to} {...props}>
        {children}
    </InertiaLink>
)

export default Link
