import React from 'react'
import {
    HiMenuAlt3,
    HiX,
    HiChevronDown,
    HiChevronRight,
    HiCheck,
    HiMinusSm,
    HiOutlineRefresh,
    HiOutlineDotsHorizontal,
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

import {
    FaBitbucket,
    FaGitlab,
    FaGithub,
    FaDigitalOcean,
    FaAws,
    FaLinode,
} from 'react-icons/fa'
import { SiVultr } from 'react-icons/si'

import { match } from '@/Shared/Helpers/methods'

const icons = {
    Menu: HiMenuAlt3,
    Close: HiX,
    ChevronDown: HiChevronDown,
    ChevronRight: HiChevronRight,
    Checkmark: HiCheck,
    Minus: HiMinusSm,
    Loading: HiOutlineRefresh,
    Dots: HiOutlineDotsHorizontal,

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

    Bitbucket: FaBitbucket,
    Gitlab: FaGitlab,
    Github: FaGithub,

    DigitalOcean: FaDigitalOcean,
    Linode: FaLinode,
    Vultr: SiVultr,
    Aws: FaAws,
}

const Icon = ({ name, ...props }) => {
    const Component = match(name, icons)

    return <Component {...props} />
}

export default Icon
