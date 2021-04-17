import React from 'react'
import { Inertia } from '@inertiajs/inertia'

import useForm from '@/Shared/Hooks/useForm'
import useProps from '@/Shared/Hooks/useProps'

import Base from '@/Shared/Components/Layouts/Base'

import Button from '@/Shared/Components/Leafs/Button'

import Form from '@/Shared/Components/FormElements/Form'
import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import FormInput from '@/Shared/Components/FormElements/FormInput'
import FormError from '@/Shared/Components/FormElements/FormError'
import FormLabel from '@/Shared/Components/FormElements/FormLabel'

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
