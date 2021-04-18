import React from 'react'

import useForm from '@/Shared/Hooks/useForm'
import useProps from '@/Shared/Hooks/useProps'

import Auth from '@/Auth/Components/Layouts/Auth'

import Button from '@/Shared/Components/Leafs/Button'
import Paragraph from '@/Shared/Components/Leafs/Paragraph'

import Form from '@/Auth/Components/FormElements/Form'
import FormGroup from '@/Shared/Components/FormElements/FormGroup'

import TextField from '@/Shared/Components/Fields/TextField'

const ForgotPassword = () => {
    const { values, onChange, errors, status, disabled, post } = useForm({
        email: '',
    })

    const onSubmit = () => {
        post(route('password.email'), values)
    }

    return (
        <Auth title="Forgot password">
            <Paragraph className="mb-6">
                Forgot your password? No problem. Just let us know your email
                address and we will email you a password reset link that will
                allow you to choose a new one.
            </Paragraph>

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
                <FormGroup>
                    <Button type="submit" size="large">
                        Send reset link
                    </Button>
                </FormGroup>
            </Form>
        </Auth>
    )
}

export default ForgotPassword
