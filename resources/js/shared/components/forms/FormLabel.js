import React from 'react'

import Text from '@/shared/components/base/Text'

const FormLabel = ({ children, ...props }) => (
    <Text Component="label" {...props}>
        {children}
    </Text>
)

export default FormLabel
