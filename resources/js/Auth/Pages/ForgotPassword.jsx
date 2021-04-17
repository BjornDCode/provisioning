import React from 'react'
import { Inertia } from '@inertiajs/inertia'

import useForm from '@/Shared/Hooks/useForm'
import useProps from '@/Shared/Hooks/useProps'

import Base from '@/Shared/Components/Layouts/Base'

import Button from '@/Shared/Components/Leafs/Button'
import Paragraph from '@/Shared/Components/Leafs/Paragraph'

import Form from '@/Shared/Components/FormElements/Form'
import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import FormInput from '@/Shared/Components/FormElements/FormInput'
import FormError from '@/Shared/Components/FormElements/FormError'
import FormLabel from '@/Shared/Components/FormElements/FormLabel'

const ForgotPassword = () => {
    const { errors } = useProps()

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
