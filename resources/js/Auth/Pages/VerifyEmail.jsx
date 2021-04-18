import React from 'react'
import { Inertia } from '@inertiajs/inertia'

import Auth from '@/Auth/Components/Layouts/Auth'

import Link from '@/Shared/Components/Leafs/Link'
import Button from '@/Shared/Components/Leafs/Button'
import Paragraph from '@/Shared/Components/Leafs/Paragraph'

import Form from '@/Shared/Components/FormElements/Form'

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
        <Auth title="Verify email">
            <Paragraph className="mb-6">
                Thanks for signing up! Before getting started, could you verify
                your email address by clicking on the link we just emailed to
                you? If you didn't receive the email, we will gladly send you
                another.
            </Paragraph>

            <div className="flex space-x-6">
                <Form onSubmit={onResend} className="w-1/2">
                    <Button type="submit" size="large">
                        Resend email
                    </Button>
                </Form>

                <Form onSubmit={onLogout} className="w-1/2">
                    <Button type="submit" size="large">
                        Log out
                    </Button>
                </Form>
            </div>
        </Auth>
    )
}

export default VerifyEmail
