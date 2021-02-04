import React from 'react'
import { Inertia } from '@inertiajs/inertia'

import Base from '@/shared/components/layouts/Base'

import Link from '@/shared/components/primitives/Link'
import Button from '@/shared/components/primitives/Button'
import Paragraph from '@/shared/components/primitives/Paragraph'

import Form from '@/shared/components/forms/Form'

const VerifyEmail = () => {
    const onLogout = event => {
        event.preventDefault()
        Inertia.post(route('logout'))
    }

    const onResend = event => {
        event.preventDefault()
        Inertia.post(route('verification.send'))
    }

    return (
        <Base>
            <h1>Verify email</h1>

            <Paragraph>
                Thanks for signing up! Before getting started, could you verify
                your email address by clicking on the link we just emailed to
                you? If you didn't receive the email, we will gladly send you
                another.
            </Paragraph>

            <Form onSubmit={onResend}>
                <Button type="submit">Resend verification email</Button>
            </Form>

            <Form onSubmit={onLogout}>
                <Button type="submit">Log out</Button>
            </Form>
        </Base>
    )
}

export default VerifyEmail
