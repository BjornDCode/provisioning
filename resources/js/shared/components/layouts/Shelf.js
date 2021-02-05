import React from 'react'

import useClasses from '@/shared/hooks/useClasses'
import { propToClasses } from '@/shared/helpers/styles'
import Box from '@/shared/components/base/Box'

const Shelf = ({
    className = '',
    children,
    spacing,
    display = 'flex',
    ...props
}) => {
    const classes = useClasses(
        propToClasses(spacing, spacing => `space-x-${spacing}`),
        className
    )

    return (
        <Box className={classes} display={display} {...props}>
            {children}
        </Box>
    )
}

export default Shelf
