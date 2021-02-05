import resolveConfig from 'tailwindcss/resolveConfig'
import tailwindConfig from '~/tailwind.config.js'

const config = resolveConfig(tailwindConfig)

export const breakpoints = {
    df: '0px',
    ...config.theme.screens,
}

export const colors = config.theme.colors
