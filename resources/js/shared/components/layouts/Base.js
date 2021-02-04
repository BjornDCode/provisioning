import React, { Fragment } from 'react'
import { Inertia } from '@inertiajs/inertia'
import { usePage } from '@inertiajs/inertia-react'

import Link from '@/shared/components/primitives/Link'

import Text from '@/shared/components/base/Text'

const Base = ({ children, ...props }) => {
    const onLogout = event => {
        event.preventDefault()
        Inertia.post(route('logout'))
    }

    const { user, flash } = usePage().props

    return (
        <div>
            <header>
                {!user ? (
                    <Fragment>
                        <Link to={route('login')}>Login</Link>
                        <Link to={route('register')}>Register</Link>
                    </Fragment>
                ) : (
                    <Fragment>
                        <a href="#" onClick={onLogout}>
                            Log out
                        </a>
                    </Fragment>
                )}
            </header>
            <main>{children}</main>
            {flash.status && <Text>{flash.status}</Text>}
        </div>
    )
}

export default Base
