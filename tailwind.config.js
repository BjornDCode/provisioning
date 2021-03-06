const defaultTheme = require('tailwindcss/defaultTheme')
const colors = require('tailwindcss/colors')

module.exports = {
    mode: 'jit',

    purge: [
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './resources/js/**/*.jsx',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
        },

        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            white: 'white',
            black: 'black',
            gray: colors.blueGray,
            pink: colors.pink,
            cyan: colors.cyan,
            green: colors.emerald,
            red: colors.rose,
            yellow: colors.amber,
        },
    },

    groupVariants: {
        'group-first': ['group', 'first', ':first-child'],
        'group-last': ['group', 'last', ':last-child'],
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/aspect-ratio'),
        require('tailwindcss-group-variants'),
    ],
}
