import React from 'react'

import useClasses from '@/Shared/Hooks/useClasses'

import Base from '@/Shared/Components/Layouts/Base'
import NavBar from '@/Shared/Components/Partials/NavBar'

import Link from '@/Shared/Components/Leafs/Link'
import Headline from '@/Shared/Components/Leafs/Headline'

const NavItem = ({ children, active, ...props }) => {
    const classes = useClasses(
        'block text-sm text-gray-300 font-medium px-4 py-2 rounded-md',
        {
            'bg-gray-800': active,
            'hover:bg-gray-800 hover:text-green-300': !active,
        }
    )

    return (
        <Link className={classes} {...props}>
            {children}
        </Link>
    )
}

const Settings = ({ title, children }) => (
    <Base>
        <div className="px-2 py-6 md:px-8 md:py-12 md:max-w-xl md:mx-auto">
            <div className="mb-8">
                <Headline className="mb-3 pb-2 border-b border-gray-600">
                    {title}
                </Headline>
                <NavBar>
                    <NavItem
                        to={route('settings.account.show')}
                        active={route().current('settings.account.show')}
                    >
                        Account
                    </NavItem>
                    <NavItem
                        to={route('settings.billing.show')}
                        active={route().current('settings.billing.show')}
                    >
                        Billing
                    </NavItem>
                    <NavItem
                        to={route('settings.teams.index')}
                        active={route().current('settings.teams.index')}
                    >
                        Teams
                    </NavItem>
                </NavBar>
            </div>

            <div className="bg-pink-500">{children}</div>
        </div>
    </Base>
)

export default Settings
