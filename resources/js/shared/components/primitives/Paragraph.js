import React from 'react'

import Text from '@/shared/components/base/Text'

const Paragraph = ({ children, ...props }) => <Text {...props}>{children}</Text>

export default Paragraph
