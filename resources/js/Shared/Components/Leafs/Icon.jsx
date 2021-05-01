import React from 'react'
import {
    HiMenuAlt3,
    HiX,
    HiChevronDown,
    HiChevronRight,
    HiCheck,
} from 'react-icons/hi'
import {
    DiLaravel,
    DiDjango,
    DiRuby,
    DiSymfonyBadge,
    DiWordpress,
    DiDrupal,
    DiDotnet,
    DiReact,
    DiAngularSimple,
    DiEmber,
} from 'react-icons/di'

import { match } from '@/Shared/Helpers/methods'

const icons = {
    Menu: HiMenuAlt3,
    Close: HiX,
    ChevronDown: HiChevronDown,
    ChevronRight: HiChevronRight,
    Checkmark: HiCheck,

    Laravel: DiLaravel,
    Symfony: DiSymfonyBadge,
    Wordpress: DiWordpress,
    Drupal: DiDrupal,
    Dotnet: DiDotnet,
    Django: DiDjango,
    Rails: DiRuby,
    React: DiReact,
    Angular: DiAngularSimple,
    Ember: DiEmber,
}

const Icon = ({ name, ...props }) => {
    const Component = match(name, icons)

    return <Component {...props} />
}

export default Icon
