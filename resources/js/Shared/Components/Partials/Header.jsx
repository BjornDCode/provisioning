import React, { Fragment, useState } from 'react'
import { Inertia } from '@inertiajs/inertia'

import useProps from '@/Shared/Hooks/useProps'
import useClasses from '@/Shared/Hooks/useClasses'
import useOnPageChange from '@/Shared/Hooks/useOnPageChange'
import useUnderBreakpoint from '@/Shared/Hooks/useUnderBreakpoint'

import Icon from '@/Shared/Components/Leafs/Icon'
import Link from '@/Shared/Components/Leafs/Link'
import NavBar from '@/Shared/Components/Partials/NavBar'

const NavItem = ({ children, active, ...props }) => {
    const classes = useClasses(
        'block border-b border-gray-600 text-sm text-gray-300 font-medium px-4 py-3 md:border-b-0 first:border-t md:first:border-t-0 md:py-2 md:rounded-md',
        {
            'md:bg-gray-800': active,
            'hover:bg-gray-800 hover:text-green-300': !active,
        }
    )

    return (
        <Link className={classes} {...props}>
            {children}
        </Link>
    )
}

const NavItems = ({ authenticated }) => {
    const onLogout = event => {
        event.preventDefault()
        Inertia.post(route('logout'))
    }

    return authenticated ? (
        <Fragment>
            <NavItem
                to={route('settings.account.show')}
                active={route().current('settings.*')}
            >
                Settings
            </NavItem>
            <NavItem href="#" onClick={onLogout}>
                Log out
            </NavItem>
        </Fragment>
    ) : (
        <Fragment>
            <NavItem to={route('login')} active={route().current('login')}>
                Login
            </NavItem>
            <NavItem
                to={route('register')}
                active={route().current('register')}
            >
                Register
            </NavItem>
        </Fragment>
    )
}

const Logo = () => (
    <Link to="/">
        <div className="w-8 h-8 bg-green-400 rounded" />
    </Link>
)

const MobileHeader = ({ authenticated }) => {
    const [open, setOpen] = useState(false)
    const toggle = () => setOpen(!open)

    useOnPageChange(() => {
        setOpen(false)
    })

    return (
        <header className="p-2 space-y-3">
            <div className="flex justify-between">
                <Logo />
                <button
                    onClick={toggle}
                    className="p-0 rounded focus:outline-none focus:ring-2 focus:ring-cyan-300"
                >
                    <Icon
                        name="Menu"
                        className="block text-green-400 w-8 h-8"
                    />
                </button>
            </div>
            {open && (
                <nav>
                    <NavItems authenticated={authenticated} />
                </nav>
            )}
        </header>
    )
}

const DesktopHeader = ({ authenticated }) => (
    <header className="flex justify-between px-8 py-4">
        <Logo />
        <NavBar>
            <NavItems authenticated={authenticated} />
        </NavBar>
    </header>
)

const Header = () => {
    const { user } = useProps()
    const mobile = useUnderBreakpoint('md')
    const authenticated = !!user
    return mobile ? (
        <MobileHeader authenticated={authenticated} />
    ) : (
        <DesktopHeader authenticated={authenticated} />
    )
}

export default Header
