import React from 'react'
import { Inertia } from '@inertiajs/inertia'

import useForm from '@/Shared/Hooks/useForm'
import useProps from '@/Shared/Hooks/useProps'

import Base from '@/Shared/Components/Layouts/Base'

import Link from '@/Shared/Components/Leafs/Link'
import Button from '@/Shared/Components/Leafs/Button'

import Form from '@/Shared/Components/FormElements/Form'
import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import FormInput from '@/Shared/Components/FormElements/FormInput'
import FormError from '@/Shared/Components/FormElements/FormError'
import FormLabel from '@/Shared/Components/FormElements/FormLabel'

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
