import React from 'react'

import useClasses from '@/shared/hooks/useClasses'
import { propToClasses } from '@/shared/helpers/styles'

import Box from '@/shared/components/base/Box'

const Text = ({
    children,
    Component = 'span',
    className = '',
    fontSize,
    fontWeight,
    ...props
}) => {
    const classes = useClasses(
        propToClasses(fontSize, size => `text-${size}`),
        propToClasses(fontWeight, weight => `font-${weight}`),
        className
    )

    return (
        <Box className={classes} Component={Component} {...props}>
            {children}
        </Box>
    )
}

export default Text
