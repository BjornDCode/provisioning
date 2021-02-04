import React from 'react'
import { Inertia } from '@inertiajs/inertia'

import useForm from '@/shared/hooks/useForm'
import useProps from '@/shared/hooks/useProps'

import Base from '@/shared/components/layouts/Base'

import Button from '@/shared/components/primitives/Button'

import Form from '@/shared/components/forms/Form'
import FormGroup from '@/shared/components/forms/FormGroup'
import FormInput from '@/shared/components/forms/FormInput'
import FormError from '@/shared/components/forms/FormError'
import FormLabel from '@/shared/components/forms/FormLabel'

const ResetPassword = () => {
    const { errors, token } = useProps()

    const [values, onChange] = useForm({
        token,
        email: '',
        password: '',
        password_confirmation: '',
    })

    const onSubmit = () => {
        Inertia.post(route('password.update'), values)
    }

    return (
        <Base>
            <h1>Reset password</h1>

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
                    <FormLabel>Password</FormLabel>
                    <FormInput
                        name="password"
                        type="password"
                        value={values.password}
                        onChange={onChange}
                        required
                    />
                </FormGroup>
                <FormGroup>
                    <FormLabel>Confirm password</FormLabel>
                    <FormInput
                        name="password_confirmation"
                        type="password"
                        value={values.password_confirmation}
                        onChange={onChange}
                        required
                    />
                </FormGroup>
                <FormGroup>
                    <Button type="submit">Reset password</Button>
                </FormGroup>
            </Form>
        </Base>
    )
}

export default ResetPassword
