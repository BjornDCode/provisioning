const defaultTheme = require('tailwindcss/defaultTheme')
const colors = require('tailwindcss/colors')

module.exports = {
    purge: [
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
        },

        colors: {
            transparent: 'transparent',
            current: 'currentColor',
            gray: colors.blueGray,
            pink: colors.pink,
            cyan: colors.cyan,
            green: colors.emerald,
            red: colors.rose,
        },
    },

    variants: {
        extend: {
            opacity: ['disabled'],
            borderWidth: ['first', 'last'],
        },
    },
}
