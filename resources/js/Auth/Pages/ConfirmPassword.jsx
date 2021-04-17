import React from 'react'
import { Inertia } from '@inertiajs/inertia'

import useForm from '@/Shared/Hooks/useForm'
import useProps from '@/Shared/Hooks/useProps'

import Base from '@/Shared/Components/Layouts/Base'

import Button from '@/Shared/Components/Leafs/Button'
import Paragraph from '@/Shared/Components/Leafs/Paragraph'

import Form from '@/Shared/Components/FormElements/Form'
import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import FormInput from '@/Shared/Components/FormElements/FormInput'
import FormError from '@/Shared/Components/FormElements/FormError'
import FormLabel from '@/Shared/Components/FormElements/FormLabel'

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
