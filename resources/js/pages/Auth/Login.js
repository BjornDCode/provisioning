import React from 'react'
import { Inertia } from '@inertiajs/inertia'

import useForm from '@/shared/hooks/useForm'
import useProps from '@/shared/hooks/useProps'

import Base from '@/shared/components/layouts/Base'

import Link from '@/shared/components/primitives/Link'
import Button from '@/shared/components/primitives/Button'

import Form from '@/shared/components/forms/Form'
import FormGroup from '@/shared/components/forms/FormGroup'
import FormInput from '@/shared/components/forms/FormInput'
import FormError from '@/shared/components/forms/FormError'
import FormLabel from '@/shared/components/forms/FormLabel'

const Login = () => {
    const { errors } = useProps()

    const [values, onChange] = useForm({
        email: '',
        password: '',
        remember: false,
    })

    const onSubmit = () => {
        Inertia.post(route('login'), values)
    }

    return (
        <Base>
            <h1>Login</h1>

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
                        autoComplete="current-password"
                    />
                </FormGroup>
                <FormGroup>
                    <FormLabel>Remember me</FormLabel>
                    <FormInput
                        name="remember"
                        type="checkbox"
                        checked={values.remember}
                        onChange={onChange}
                    />
                </FormGroup>
                <FormGroup>
                    <Link to={route('password.request')}>
                        Forgot your password?
                    </Link>
                    <Button type="submit">Login</Button>
                </FormGroup>
            </Form>
        </Base>
    )
}

export default Login
