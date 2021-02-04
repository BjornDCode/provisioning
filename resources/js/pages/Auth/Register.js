import React from 'react'
import { Inertia } from '@inertiajs/inertia'
import { usePage } from '@inertiajs/inertia-react'

import useForm from '@/shared/hooks/useForm'

import Base from '@/shared/components/layouts/Base'

import Link from '@/shared/components/primitives/Link'
import Button from '@/shared/components/primitives/Button'

import Form from '@/shared/components/forms/Form'
import FormGroup from '@/shared/components/forms/FormGroup'
import FormInput from '@/shared/components/forms/FormInput'
import FormError from '@/shared/components/forms/FormError'
import FormLabel from '@/shared/components/forms/FormLabel'

const Register = () => {
    const { errors } = usePage().props

    const [values, onChange] = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    })

    const onSubmit = () => {
        Inertia.post(route('register'), values)
    }

    return (
        <Base>
            <h1>Register</h1>

            <Form onSubmit={onSubmit}>
                <FormGroup>
                    <FormLabel>Name</FormLabel>
                    <FormInput
                        name="name"
                        value={values.name}
                        onChange={onChange}
                        required
                        autoFocus
                    />
                    {errors.name ? <FormError>{errors.name}</FormError> : null}
                </FormGroup>
                <FormGroup>
                    <FormLabel>Email</FormLabel>
                    <FormInput
                        type="email"
                        name="email"
                        value={values.email}
                        onChange={onChange}
                        required
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
                    <Button type="submit">Register</Button>
                </FormGroup>
            </Form>
        </Base>
    )
}

export default Register
