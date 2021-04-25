import React from 'react'

import useForm from '@/Shared/Hooks/useForm'

import Auth from '@/Auth/Components/Layouts/Auth'

import Link from '@/Shared/Components/Leafs/Link'
import Button from '@/Shared/Components/Leafs/Button'

import Form from '@/Auth/Components/FormElements/Form'
import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import TextField from '@/Shared/Components/Fields/TextField'

const Register = () => {
    const { values, onChange, errors, status, disabled, post } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    })

    const onSubmit = () => {
        post(route('register'), values)
    }

    return (
        <Auth title="Register">
            <Form onSubmit={onSubmit}>
                <TextField
                    label="Name"
                    name="name"
                    value={values.name}
                    onChange={onChange}
                    required
                    autoFocus
                    error={errors.name}
                />
                <TextField
                    label="Email"
                    type="email"
                    name="email"
                    value={values.email}
                    onChange={onChange}
                    required
                    error={errors.email}
                />
                <TextField
                    label="Password"
                    name="password"
                    type="password"
                    value={values.password}
                    onChange={onChange}
                    required
                    autoComplete="current-password"
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
                        Register
                    </Button>
                </FormGroup>
            </Form>
        </Auth>
    )
}

export default Register
