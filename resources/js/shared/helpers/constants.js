import resolveConfig from 'tailwindcss/resolveConfig'
import tailwindConfig from '~/tailwind.config.js'

const config = resolveConfig(tailwindConfig)

export const breakpoints = {
    df: '0px',
    ...config.theme.screens,
}

export const colors = config.theme.colors

export const gitProviders = [
    {
        key: 'github',
        label: 'GitHub',
        icon: 'Github',
        disabled: false,
    },
    {
        key: 'gitlab',
        label: 'GitLab',
        icon: 'Gitlab',
        disabled: true,
    },
    {
        key: 'bitbucket',
        label: 'Bitbucket',
        icon: 'Bitbucket',
        disabled: true,
    },
]

export const projectTypes = [
    {
        key: 'laravel',
        label: 'Laravel',
        icon: 'Laravel',
        disabled: false,
    },
    {
        key: 'symfony',
        label: 'Symfony',
        icon: 'Symfony',
        disabled: true,
    },
    {
        key: 'wordpress',
        label: 'WordPress',
        icon: 'Wordpress',
        disabled: true,
    },
    {
        key: 'drupal',
        label: 'Drupal',
        icon: 'Drupal',
        disabled: true,
    },
    {
        key: 'dotnet',
        label: '.NET',
        icon: 'Dotnet',
        disabled: true,
    },
    {
        key: 'django',
        label: 'Django',
        icon: 'Django',
        disabled: true,
    },
    {
        key: 'rails',
        label: 'Rails',
        icon: 'Rails',
        disabled: true,
    },
    {
        key: 'react',
        label: 'React',
        icon: 'React',
        disabled: true,
    },
    {
        key: 'angular',
        label: 'Angular',
        icon: 'Angular',
        disabled: true,
    },
    {
        key: 'ember',
        label: 'Ember',
        icon: 'Ember',
        disabled: true,
    },
]
