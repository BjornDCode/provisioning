import React from 'react'

import useForm from '@/Shared/Hooks/useForm'
import useProps from '@/Shared/Hooks/useProps'

import Authenticated from '@/Shared/Components/Layouts/Authenticated'

import Button from '@/Shared/Components/Leafs/Button'

import Form from '@/Shared/Components/FormElements/Form'
import FormGroup from '@/Shared/Components/FormElements/FormGroup'

import ListField from '@/Shared/Components/Fields/ListField'

const Environments = () => {
    const { configuration, pipeline } = useProps()

    const { values, onChange, errors, status, disabled, post } = useForm({
        value: configuration
            ? configuration.details.value
            : ['Staging', 'Production'],
    })

    const onSubmit = () => {
        post(
            route('steps.configuration.configure', {
                pipeline: pipeline.id,
                step: 'environments',
            }),
            values
        )
    }

    return (
        <Authenticated title="Define environments">
            <Form className="space-y-6" onSubmit={onSubmit}>
                <ListField
                    label="Add environments"
                    name="value"
                    value={values.value}
                    onChange={onChange}
                    required
                    error={errors.value}
                />

                <FormGroup className="flex justify-end">
                    <Button type="submit">Next</Button>
                </FormGroup>
            </Form>
        </Authenticated>
    )
}

export default Environments
