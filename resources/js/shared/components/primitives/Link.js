import React from 'react'

import { InertiaLink } from '@inertiajs/inertia-react'

import Text from '@/shared/components/base/Text'

const Link = ({ children, to = '#', ...props }) => (
    <Text href={to} Component={InertiaLink} {...props}>
        {children}
    </Text>
)

export default Link
