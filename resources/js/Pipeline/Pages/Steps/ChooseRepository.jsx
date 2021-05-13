import React from 'react'

import useForm from '@/Shared/Hooks/useForm'
import useProps from '@/Shared/Hooks/useProps'

import Authenticated from '@/Shared/Components/Layouts/Authenticated'

import Button from '@/Shared/Components/Leafs/Button'

import Form from '@/Shared/Components/FormElements/Form'
import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import SearchableRadioListField from '@/Shared/Components/Fields/SearchableRadioListField'

const ChooseRepository = () => {
    const { configuration, pipeline, repositories = [] } = useProps()

    const { values, onChanges, errors, status, disabled, post } = useForm({
        owner: configuration ? configuration.details.owner : '',
        name: configuration ? configuration.details.name : '',
    })

    const computedValue = `${values.owner}/${values.name}`
    const options = repositories.map(repository => ({
        key: `${repository.owner}/${repository.name}`,
        label: repository.name,
    }))

    const handleChange = event => {
        const [owner, name] = event.target.value.split('/')

        onChanges({
            owner,
            name,
        })
    }

    const onSubmit = () => {
        post(
            route('steps.configuration.configure', {
                pipeline: pipeline.id,
                step: 'choose-repository',
            }),
            values
        )
    }

    return (
        <Authenticated title="Choose repository">
            <Form className="space-y-6" onSubmit={onSubmit}>
                <SearchableRadioListField
                    label="Repository"
                    name="value"
                    value={computedValue}
                    onChange={handleChange}
                    options={options}
                    empty="No repositories"
                    error={errors.owner || errors.name}
                    required
                />

                <FormGroup className="flex justify-end">
                    <Button type="submit">Next</Button>
                </FormGroup>
            </Form>
        </Authenticated>
    )
}

export default ChooseRepository
