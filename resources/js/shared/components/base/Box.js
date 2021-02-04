import React from 'react'

const Box = ({ children, Component = 'div', ...props }) => {
    return <Component {...props}>{children}</Component>
}

export default Box
