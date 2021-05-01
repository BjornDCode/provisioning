import React, { forwardRef } from 'react'

const FormGroup = forwardRef(({ children, ...props }, ref) => (
    <div ref={ref} {...props}>
        {children}
    </div>
))

export default FormGroup
