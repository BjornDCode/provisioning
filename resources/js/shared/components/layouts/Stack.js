import React from 'react'

import useClasses from '@/shared/hooks/useClasses'
import { propToClasses } from '@/shared/helpers/styles'

import Box from '@/shared/components/base/Box'

const Stack = ({ className = '', spacing, children, ...props }) => {
    const classes = useClasses(
        propToClasses(spacing, spacing => `space-y-${spacing}`),
        className
    )

    return (
        <Box className={classes} {...props}>
            {children}
        </Box>
    )
}

export default Stack
