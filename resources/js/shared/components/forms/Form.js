import React from 'react'

import Box from '@/shared/components/base/Box'

const Form = ({ children, onSubmit = () => {}, ...props }) => {
    const handleSubmit = event => {
        event.preventDefault()
        onSubmit(event)
    }

    return (
        <Box Component="form" onSubmit={handleSubmit} {...props}>
            {children}
        </Box>
    )
}

export default Form
