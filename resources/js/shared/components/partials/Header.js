import React, { Fragment, useState } from 'react'
import { Inertia } from '@inertiajs/inertia'

import useProps from '@/shared/hooks/useProps'
import useOnPageChange from '@/shared/hooks/useOnPageChange'
import useUnderBreakpoint from '@/shared/hooks/useUnderBreakpoint'

import Icon from '@/shared/components/primitives/Icon'
import Link from '@/shared/components/primitives/Link'
import Shelf from '@/shared/components/layouts/Shelf'
import Stack from '@/shared/components/layouts/Stack'

import Box from '@/shared/components/base/Box'

const NavItem = ({ children, ...props }) => (
    <Link
        display="block"
        spaceX={{ df: 4, md: 0 }}
        spaceY={{ df: 3, md: 0 }}
        borderB={{ df: 1, md: 0 }}
        borderT={{ first: 1, 'md:first': 0 }}
        borderColor="gray"
        borderShade="600"
        fontSize="sm"
        fontWeight="medium"
        textColor={{ df: 'gray', hover: 'green' }}
        textShade="300"
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
        <Box
            width={8}
            height={8}
            backgroundColor="green"
            backgroundShade="400"
        />
    </Link>
)

const MobileHeader = ({ authenticated }) => {
    const [open, setOpen] = useState(false)
    const toggle = () => setOpen(!open)

    useOnPageChange(() => {
        setOpen(false)
    })

    return (
        <Stack Component="header" spaceX={2} spaceY={2} spacing={3}>
            <Shelf justify="between">
                <Logo />
                <button onClick={toggle} className="p-0 rounded">
                    <Icon
                        name="Menu"
                        display="block"
                        textColor="green"
                        textShade="400"
                        width={8}
                        height={8}
                    />
                </button>
            </Shelf>
            {open && (
                <Stack Component="nav">
                    <NavItems authenticated={authenticated} />
                </Stack>
            )}
        </Stack>
    )
}

const DesktopHeader = ({ authenticated }) => (
    <Shelf Component="header" justify="between" spaceX={8} spaceY={4}>
        <Logo />
        <Shelf Component="nav" align="center" spacing={4}>
            <NavItems authenticated={authenticated} />
        </Shelf>
    </Shelf>
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
