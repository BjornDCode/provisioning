import React from 'react'
import { Inertia } from '@inertiajs/inertia'

import useForm from '@/shared/hooks/useForm'
import useProps from '@/shared/hooks/useProps'

import Base from '@/shared/components/layouts/Base'

import Button from '@/shared/components/primitives/Button'
import Paragraph from '@/shared/components/primitives/Paragraph'

import Form from '@/shared/components/forms/Form'
import FormGroup from '@/shared/components/forms/FormGroup'
import FormInput from '@/shared/components/forms/FormInput'
import FormError from '@/shared/components/forms/FormError'
import FormLabel from '@/shared/components/forms/FormLabel'

const ConfirmPassword = () => {
    const { errors } = useProps()

    const [values, onChange] = useForm({
        password: '',
    })

    const onSubmit = () => {
        Inertia.post(route('password.confirm'), values)
    }

    return (
        <Base>
            <h1>Confirm password</h1>

            <Paragraph>
                This is a secure area of the application. Please confirm your
                password before continuing.
            </Paragraph>

            <Form onSubmit={onSubmit}>
                <FormGroup>
                    <FormLabel>Password</FormLabel>
                    <FormInput
                        name="password"
                        type="password"
                        value={values.password}
                        onChange={onChange}
                        required
                        autoComplete="current-password"
                        autoFocus
                    />
                </FormGroup>
                <FormGroup>
                    <Button type="submit">Confirm</Button>
                </FormGroup>
            </Form>
        </Base>
    )
}

export default ConfirmPassword
