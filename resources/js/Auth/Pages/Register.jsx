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

const Register = () => {
    const { errors } = useProps()

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
