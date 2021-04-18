import React from 'react'

import BaseForm from '@/Shared/Components/FormElements/Form'

const Form = ({ children, ...props }) => (
    <BaseForm className="space-y-6" {...props}>
        {children}
    </BaseForm>
)

export default Form
