export const match = (key, values) => values[key] || values['default']

export const falsy = value => !value

export const distinct = (value, index, self) => self.indexOf(value) === index

export const conditionalProp = (prop, props) => (prop ? props : {})
