import React from 'react'

import useForm from '@/Shared/Hooks/useForm'
import useProps from '@/Shared/Hooks/useProps'

import Authenticated from '@/Shared/Components/Layouts/Authenticated'

import Button from '@/Shared/Components/Leafs/Button'

import Form from '@/Shared/Components/FormElements/Form'
import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import TwoChoiceField from '@/Shared/Components/Fields/TwoChoiceField'

const options = [
    {
        key: true,
        label: 'Yes',
    },
    {
        key: false,
        label: 'No',
    },
]

const HostingPrompt = () => {
    const { configuration, pipeline } = useProps()

    const { values, onChange, errors, status, disabled, post } = useForm({
        value: configuration ? configuration.details.value : '',
    })

    const onSubmit = () => {
        post(
            route('steps.configuration.configure', {
                pipeline: pipeline.id,
                step: 'hosting',
            }),
            values
        )
    }

    return (
        <Authenticated title="Setup hosting">
            <Form className="space-y-6" onSubmit={onSubmit}>
                <TwoChoiceField
                    label="Configure servers"
                    name="value"
                    value={values.value}
                    onChange={onChange}
                    options={options}
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

export default HostingPrompt
