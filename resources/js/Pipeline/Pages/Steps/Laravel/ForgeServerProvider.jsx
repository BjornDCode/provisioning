import React from 'react'

import useForm from '@/Shared/Hooks/useForm'
import useProps from '@/Shared/Hooks/useProps'
import { match } from '@/Shared/Helpers/methods'

import Authenticated from '@/Shared/Components/Layouts/Authenticated'

import Link from '@/Shared/Components/Leafs/Link'
import Button from '@/Shared/Components/Leafs/Button'
import Paragraph from '@/Shared/Components/Leafs/Paragraph'

import Form from '@/Shared/Components/FormElements/Form'
import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import RadioGridField from '@/Shared/Components/Fields/RadioGridField'
import RadioListField from '@/Shared/Components/Fields/RadioListField'

const ForgeServerProvider = () => {
    const {
        configuration,
        pipeline,
        providers = [],
        credentials = [],
    } = useProps()

    const { values, onChange, errors, status, disabled, post } = useForm({
        provider: configuration ? configuration.details.provider : '',
        credentials_id: configuration
            ? configuration.details.credentials_id
            : '',
    })

    const onSubmit = () => {
        post(
            route('steps.configuration.configure', {
                pipeline: pipeline.id,
                step: 'forge-server-provider',
            }),
            values
        )
    }

    const options = providers.map(provider => {
        const label = match(provider, {
            ocean2: 'Digital Ocean',
            linode: 'Linode',
            vultr: 'Vultr',
            aws: 'AWS',
        })

        const icon = match(provider, {
            ocean2: 'DigitalOcean',
            linode: 'Linode',
            vultr: 'Vultr',
            aws: 'Aws',
        })

        return {
            key: provider,
            label: label,
            icon: icon,
            disabled: false,
        }
    })

    const credentialsOptions = values.provider
        ? credentials
              .filter(credential => credential.type === values.provider)
              .map(credential => ({
                  key: credential.id,
                  label: credential.name,
              }))
        : []

    return (
        <Authenticated title="Choose server provider">
            <Paragraph>
                To use another server provider go to the{' '}
                <Link
                    as="a"
                    href="https://forge.laravel.com/user/profile#/providers"
                    target="_blank"
                    className="text-green-400"
                >
                    Forge dashboard
                </Link>{' '}
                and connect a provider.
            </Paragraph>

            <Form className="space-y-6" onSubmit={onSubmit}>
                <RadioGridField
                    label="Server provider"
                    name="provider"
                    value={values.provider}
                    onChange={onChange}
                    options={options}
                    required
                    error={errors.provider}
                />

                {values.provider && (
                    <RadioListField
                        label="Credentials"
                        name="credentials_id"
                        value={values.credentials_id}
                        onChange={onChange}
                        options={credentialsOptions}
                        required
                        error={errors.credentials_id}
                    />
                )}

                <FormGroup className="flex justify-end">
                    <Button type="submit">Next</Button>
                </FormGroup>
            </Form>
        </Authenticated>
    )
}

export default ForgeServerProvider
