import React from 'react'
import { Inertia } from '@inertiajs/inertia'
import { usePage } from '@inertiajs/inertia-react'

import useForm from '@/shared/hooks/useForm'

import Base from '@/shared/components/layouts/Base'

import Button from '@/shared/components/primitives/Button'
import Paragraph from '@/shared/components/primitives/Paragraph'

import Form from '@/shared/components/forms/Form'
import FormGroup from '@/shared/components/forms/FormGroup'
import FormInput from '@/shared/components/forms/FormInput'
import FormError from '@/shared/components/forms/FormError'
import FormLabel from '@/shared/components/forms/FormLabel'

const ForgotPassword = () => {
    const { errors } = usePage().props

    const [values, onChange] = useForm({
        email: '',
    })

    const onSubmit = () => {
        Inertia.post(route('password.email'), values)
    }

    return (
        <Base>
            <h1>Forgot password</h1>

            <Paragraph>
                Forgot your password? No problem. Just let us know your email
                address and we will email you a password reset link that will
                allow you to choose a new one.
            </Paragraph>

            <Form onSubmit={onSubmit}>
                <FormGroup>
                    <FormLabel>Email</FormLabel>
                    <FormInput
                        type="email"
                        name="email"
                        value={values.email}
                        onChange={onChange}
                        required
                        autoFocus
                    />
                    {errors.email ? (
                        <FormError>{errors.email}</FormError>
                    ) : null}
                </FormGroup>
                <FormGroup>
                    <Button type="submit">Send reset link</Button>
                </FormGroup>
            </Form>
        </Base>
    )
}

export default ForgotPassword
