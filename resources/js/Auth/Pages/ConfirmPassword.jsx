import React from 'react'

import useForm from '@/Shared/Hooks/useForm'

import Auth from '@/Auth/Components/Layouts/Auth'

import Button from '@/Shared/Components/Leafs/Button'
import Paragraph from '@/Shared/Components/Leafs/Paragraph'

import Form from '@/Auth/Components/FormElements/Form'
import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import TextField from '@/Shared/Components/Fields/TextField'

const ConfirmPassword = () => {
    const { values, onChange, errors, status, disabled, post } = useForm({
        password: '',
    })

    const onSubmit = () => {
        post(route('password.confirm'), values)
    }

    return (
        <Auth title="Confirm password">
            <Paragraph className="mb-6">
                This is a secure area of the application. Please confirm your
                password before continuing.
            </Paragraph>

            <Form onSubmit={onSubmit}>
                <TextField
                    label="Password"
                    name="password"
                    type="password"
                    value={values.password}
                    onChange={onChange}
                    required
                    autoComplete="current-password"
                    autoFocus
                />
                <FormGroup>
                    <Button type="submit" size="large">
                        Confirm
                    </Button>
                </FormGroup>
            </Form>
        </Auth>
    )
}

export default ConfirmPassword
