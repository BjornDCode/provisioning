import React from 'react'

import useForm from '@/Shared/Hooks/useForm'
import { projectTypes } from '@/Shared/Helpers/constants'

import Authenticated from '@/Shared/Components/Layouts/Authenticated'

import Button from '@/Shared/Components/Leafs/Button'

import Form from '@/Shared/Components/FormElements/Form'
import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import TextField from '@/Shared/Components/Fields/TextField'
import SearchableRadioGridField from '@/Shared/Components/Fields/SearchableRadioGridField'

const Create = () => {
    const { values, onChange, errors, status, disabled, post } = useForm({
        name: '',
        type: '',
    })

    const onSubmit = () => {
        post(route('projects.store'), values)
    }

    return (
        <Authenticated title="Create new project">
            <Form className="space-y-6" onSubmit={onSubmit}>
                <TextField
                    label="Name"
                    name="name"
                    value={values.name}
                    onChange={onChange}
                    required
                    autoFocus
                    error={errors.name}
                />

                <SearchableRadioGridField
                    label="Type"
                    name="type"
                    value={values.type}
                    onChange={onChange}
                    options={projectTypes}
                    required
                    error={errors.type}
                />

                <FormGroup className="flex justify-end">
                    <Button type="submit">Create</Button>
                </FormGroup>
            </Form>
        </Authenticated>
    )
}

export default Create
