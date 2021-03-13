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

const boxShadowToClasses = value => {
    const valueModifier = value != 'normal' ? `-${value}` : ''

    return `shadow${valueModifier}`
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
    flex,
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
    shadow,
    space,
    spaceX,
    spaceY,
    spaceL,
    spaceR,
    spaceT,
    spaceB,
    textColor,
    textShade,
    top,
    bottom,
    left,
    right,
    width,
    inset,
    insetX,
    insetY,
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
        propToClasses(flex, value => `flex-${value}`),
        propToClasses(height, height => `h-${height}`),
        propToClasses(justify, value => `justify-${value}`),
        propToClasses(margin, value => `m-${value}`),
        propToClasses(marginY, value => `my-${value}`),
        propToClasses(marginX, value => `mx-${value}`),
        propToClasses(marginL, value => `ml-${value}`),
        propToClasses(marginR, value => `mr-${value}`),
        propToClasses(marginT, value => `mt-${value}`),
        propToClasses(marginB, value => `mb-${value}`),
        propToClasses(maxWidth, width => `max-w-${width}`),
        propToClasses(position),
        propToClasses(shadow, boxShadowToClasses),
        propToClasses(space, space => `p-${space}`),
        propToClasses(spaceY, space => `py-${space}`),
        propToClasses(spaceX, space => `px-${space}`),
        propToClasses(spaceL, space => `pl-${space}`),
        propToClasses(spaceR, space => `pr-${space}`),
        propToClasses(spaceT, space => `pt-${space}`),
        propToClasses(spaceB, space => `pb-${space}`),
        propsToClasses([textColor, textShade], colorPropsToClasses('text')),
        propToClasses(width, width => `w-${width}`),
        propToClasses(top, value => `top-${value}`),
        propToClasses(bottom, value => `bottom-${value}`),
        propToClasses(left, value => `left-${value}`),
        propToClasses(right, value => `right-${value}`),
        propToClasses(inset, value => `inset-${value}`),
        propToClasses(insetX, value => `inset-x-${value}`),
        propToClasses(insetY, value => `inset-y-${value}`),
        className
    )

    return (
        <Component className={classes} {...props}>
            {children}
        </Component>
    )
}

export default Box
