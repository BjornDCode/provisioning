import React from 'react'

import useClasses from '@/shared/hooks/useClasses'
import {
    propToClasses,
    propsToClasses,
    colorPropsToClasses,
} from '@/shared/helpers/styles'

const borderPropToClasses = direction => {
    return value => {
        const directionModifier = direction ? `-${direction}` : ''
        const valueModifier = value != 1 ? `-${value}` : ''

        return `border${directionModifier}${valueModifier}`
    }
}

const borderRadiusToClasses = direction => {
    return value => {
        const directionModifier = direction ? `-${direction}` : ''
        const valueModifier = value != 'normal' ? `-${value}` : ''

        return `rounded${directionModifier}${valueModifier}`
    }
}

const Box = ({
    children,
    Component = 'div',
    className = '',
    align,
    backgroundColor,
    backgroundShade,
    border,
    borderT,
    borderB,
    borderL,
    borderR,
    borderRadius,
    borderRadiusT,
    borderRadiusB,
    borderRadiusL,
    borderRadiusR,
    borderColor,
    borderShade,
    display,
    height,
    justify,
    margin,
    marginX,
    marginY,
    marginL,
    marginR,
    marginT,
    marginB,
    maxWidth,
    position,
    space,
    spaceX,
    spaceY,
    spaceL,
    spaceR,
    spaceT,
    spaceB,
    textColor,
    textShade,
    width,
    ...props
}) => {
    const classes = useClasses(
        propToClasses(align, align => `items-${align}`),
        propsToClasses(
            [backgroundColor, backgroundShade],
            colorPropsToClasses('bg')
        ),
        propToClasses(border, borderPropToClasses()),
        propToClasses(borderT, borderPropToClasses('t')),
        propToClasses(borderB, borderPropToClasses('b')),
        propToClasses(borderL, borderPropToClasses('l')),
        propToClasses(borderR, borderPropToClasses('r')),
        propToClasses(borderRadius, borderRadiusToClasses()),
        propToClasses(borderRadiusT, borderRadiusToClasses('t')),
        propToClasses(borderRadiusB, borderRadiusToClasses('b')),
        propToClasses(borderRadiusL, borderRadiusToClasses('l')),
        propToClasses(borderRadiusR, borderRadiusToClasses('r')),
        propsToClasses(
            [borderColor, borderShade],
            colorPropsToClasses('border')
        ),
        propToClasses(display),
        propToClasses(height, height => `h-${height}`),
        propToClasses(justify, value => `justify-${value}`),
        propToClasses(margin, value => `m-${value}`),
        propToClasses(marginY, value => `my-${value}`),
        propToClasses(marginX, value => `mx-${value}`),
        propToClasses(marginL, value => `ml-${value}`),
        propToClasses(marginR, value => `mr-${value}`),
        propToClasses(marginT, value => `mt-${value}`),
        propToClasses(marginB, value => `mb-${value}`),
        propToClasses(maxWidth, width => `max-${width}`),
        propToClasses(position),
        propToClasses(space, space => `p-${space}`),
        propToClasses(spaceY, space => `py-${space}`),
        propToClasses(spaceX, space => `px-${space}`),
        propToClasses(spaceL, space => `pl-${space}`),
        propToClasses(spaceR, space => `pr-${space}`),
        propToClasses(spaceT, space => `pt-${space}`),
        propToClasses(spaceB, space => `pb-${space}`),
        propsToClasses([textColor, textShade], colorPropsToClasses('text')),
        propToClasses(width, width => `w-${width}`),
        className
    )

    return (
        <Component className={classes} {...props}>
            {children}
        </Component>
    )
}

export default Box
