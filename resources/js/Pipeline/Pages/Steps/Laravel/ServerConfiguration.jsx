import React from 'react'

import useForm from '@/Shared/Hooks/useForm'
import useProps from '@/Shared/Hooks/useProps'
import { match } from '@/Shared/Helpers/methods'

import Authenticated from '@/Shared/Components/Layouts/Authenticated'

import Link from '@/Shared/Components/Leafs/Link'
import Button from '@/Shared/Components/Leafs/Button'

import Form from '@/Shared/Components/FormElements/Form'
import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import SelectField from '@/Shared/Components/Fields/SelectField'

const ServerConfiguration = () => {
    const { configuration, pipeline, regions = [] } = useProps()

    const { values, onChange, errors, status, disabled, post } = useForm({
        region: configuration ? configuration.details.region : regions[0].id,
        size: configuration
            ? configuration.details.size
            : regions[0].sizes[0].size,
    })

    const onSubmit = () => {
        post(
            route('steps.configuration.configure', {
                pipeline: pipeline.id,
                step: 'server-configuration',
            }),
            values
        )
    }

    const regionOptions = regions.map(region => ({
        key: region.id,
        label: region.name,
    }))

    const sizeOptions = regions
        .find(region => region.id === values.region)
        .sizes.map(size => ({
            key: size.size,
            label: size.name,
        }))

    return (
        <Authenticated title="Configure servers">
            <Form className="space-y-6" onSubmit={onSubmit}>
                <SelectField
                    label="Regions"
                    name="region"
                    value={values.region}
                    onChange={onChange}
                    options={regionOptions}
                    error={errors.region}
                    required
                />

                {values.region && (
                    <SelectField
                        label="Size"
                        name="size"
                        value={values.size}
                        onChange={onChange}
                        options={sizeOptions}
                        error={errors.size}
                        required
                    />
                )}

                <FormGroup className="flex justify-end">
                    <Button type="submit">Next</Button>
                </FormGroup>
            </Form>
        </Authenticated>
    )
}

export default ServerConfiguration
