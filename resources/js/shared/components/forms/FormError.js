import React from 'react'

import Text from '@/shared/components/base/Text'

const FormError = ({ children, ...props }) => <Text {...props}>{children}</Text>

export default FormError
