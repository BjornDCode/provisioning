import React from 'react'

import useForm from '@/Shared/Hooks/useForm'
import useProps from '@/Shared/Hooks/useProps'

import { gitProviders } from '@/Shared/Helpers/constants'

import Authenticated from '@/Shared/Components/Layouts/Authenticated'

import Button from '@/Shared/Components/Leafs/Button'

import Form from '@/Shared/Components/FormElements/Form'
import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import RadioGridField from '@/Shared/Components/Fields/RadioGridField'

const GitProvider = () => {
    const { configuration } = useProps()

    const { values, onChange, errors, status, disabled, post } = useForm({
        value: configuration ? configuration.details.value : '',
    })

    const onSubmit = () => {
        post(route('projects.store'), values)
    }

    return (
        <Authenticated title="Choose Git provider">
            <Form className="space-y-6" onSubmit={onSubmit}>
                <RadioGridField
                    label="Git provider"
                    name="value"
                    value={values.value}
                    onChange={onChange}
                    options={gitProviders}
                    required
                    error={errors.value}
                />

                <FormGroup className="flex justify-end">
                    <Button type="submit">Create</Button>
                </FormGroup>
            </Form>
        </Authenticated>
    )
}

export default GitProvider
