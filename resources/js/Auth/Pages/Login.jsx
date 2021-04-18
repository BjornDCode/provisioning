import React from 'react'

import useForm from '@/Shared/Hooks/useForm'

import Auth from '@/Auth/Components/Layouts/Auth'

import Link from '@/Shared/Components/Leafs/Link'
import Button from '@/Shared/Components/Leafs/Button'

import Form from '@/Auth/Components/FormElements/Form'
import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import TextField from '@/Shared/Components/Fields/TextField'
import SingleCheckboxField from '@/Shared/Components/Fields/SingleCheckboxField'

const Login = () => {
    const { values, onChange, errors, status, disabled, post } = useForm({
        email: '',
        password: '',
        remember: false,
    })

    const onSubmit = () => {
        post(route('login'), {
            ...values,
            remember: values.remember ? 'on' : '',
        })
    }

    return (
        <Auth title="Login">
            <Form onSubmit={onSubmit}>
                <TextField
                    label="Email"
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
                    autoComplete="current-password"
                />
                <FormGroup className="flex items-center justify-between">
                    <SingleCheckboxField
                        label="Remember me"
                        name="remember"
                        type="checkbox"
                        checked={values.remember}
                        onChange={onChange}
                    />
                    <Link
                        to={route('password.request')}
                        className="text-sm text-white underline"
                    >
                        Forgot your password?
                    </Link>
                </FormGroup>
                <FormGroup>
                    <Button type="submit" size="large">
                        Login
                    </Button>
                </FormGroup>
            </Form>
        </Auth>
    )
}

export default Login
