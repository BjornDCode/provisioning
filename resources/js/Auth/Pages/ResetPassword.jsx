import React from 'react'

import useForm from '@/Shared/Hooks/useForm'
import useProps from '@/Shared/Hooks/useProps'

import Auth from '@/Auth/Components/Layouts/Auth'

import Button from '@/Shared/Components/Leafs/Button'

import Form from '@/Auth/Components/FormElements/Form'
import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import TextField from '@/Shared/Components/Fields/TextField'

const ResetPassword = () => {
    const { token } = useProps()

    const { values, onChange, errors, status, disabled, post } = useForm({
        token,
        email: '',
        password: '',
        password_confirmation: '',
    })

    const onSubmit = () => {
        post(route('password.update'), values)
    }

    return (
        <Auth title="Reset password">
            <Form onSubmit={onSubmit}>
                <TextField
                    label="Email"
                    type="email"
                    name="email"
                    value={values.email}
                    onChange={onChange}
                    required
                    autoFocus
                    error={errors.email}
                />
                <TextField
                    label="Password"
                    name="password"
                    type="password"
                    value={values.password}
                    onChange={onChange}
                    required
                />
                <TextField
                    label="Confirm password"
                    name="password_confirmation"
                    type="password"
                    value={values.password_confirmation}
                    onChange={onChange}
                    required
                />
                <FormGroup>
                    <Button type="submit" size="large">
                        Reset password
                    </Button>
                </FormGroup>
            </Form>
        </Auth>
    )
}

export default ResetPassword
