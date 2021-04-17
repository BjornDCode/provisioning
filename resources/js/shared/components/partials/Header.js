import React, { Fragment, useState } from 'react'
import { Inertia } from '@inertiajs/inertia'

import useProps from '@/shared/hooks/useProps'
import useOnPageChange from '@/shared/hooks/useOnPageChange'
import useUnderBreakpoint from '@/shared/hooks/useUnderBreakpoint'

import Icon from '@/shared/components/primitives/Icon'
import Link from '@/shared/components/primitives/Link'

const NavItem = ({ children, ...props }) => (
    <Link
        className="block border-b border-gray-600 text-sm font-medium text-gray-300 px-4 py-3 md:border-b-0 first:border-t md:first:border-t-0 md:p-0 hover:text-green-300"
        {...props}
    >
        {children}
    </Link>
)

const NavItems = ({ authenticated }) => {
    const onLogout = event => {
        event.preventDefault()
        Inertia.post(route('logout'))
    }

    return authenticated ? (
        <Fragment>
            <NavItem href="#" onClick={onLogout}>
                Log out
            </NavItem>
        </Fragment>
    ) : (
        <Fragment>
            <NavItem to={route('login')}>Login</NavItem>
            <NavItem to={route('register')}>Register</NavItem>
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
                <button onClick={toggle} className="p-0 rounded">
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
        <nav className="flex items-center space-x-4">
            <NavItems authenticated={authenticated} />
        </nav>
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
