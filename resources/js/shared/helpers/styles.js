import { falsy, distinct } from '@/shared/helpers/methods'

export const colorPropsToClasses = prefix => (color, shade) => {
    if (!shade) {
        return `${prefix}-${color}`
    }

    return `${prefix}-${color}-${shade}`
}

const statesObjectToClasses = (prop, factory = value => value) => {
    return Object.keys(prop)
        .map(state => {
            const value = prop[state]
            // Default state - Shouldn't have prefix added
            if (state === 'df') {
                return factory(value)
            }

            return `${state}:${factory(value)}`
        })
        .join(' ')
}

const statesObjectsToClasses = (props, factory = value => value) => {
    // Find all states in all prop objects
    const keys = props
        .map(prop => Object.keys(prop))
        .flat()
        .filter(distinct)

    return keys
        .map(state => {
            const values = props
                .map(prop => prop[state])
                .map((prop, index) => {
                    if (prop) {
                        return prop
                    }

                    // If the prop doesn't have a value for the state - return the default value
                    return props[index]['df']
                })

            // Default state - Shouldn't have prefix added
            if (state === 'df') {
                return factory(...values)
            }

            return `${state}:${factory(...values)}`
        })
        .join(' ')
}

export const propToClasses = (prop, factory) => {
    if (prop === undefined || prop === null) {
        return ''
    }

    if (typeof prop !== 'object') {
        prop = { df: prop }
    }

    return statesObjectToClasses(prop, factory)
}

export const propsToClasses = (props = [], factory) => {
    if (props.every(falsy)) {
        return ''
    }

    props = props.map(prop => {
        if (typeof prop === 'object') {
            return prop
        }

        return { df: prop }
    })

    return statesObjectsToClasses(props, factory)
}
