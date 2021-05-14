import React, { useState } from 'react'

import useForm from '@/Shared/Hooks/useForm'
import useProps from '@/Shared/Hooks/useProps'

import Authenticated from '@/Shared/Components/Layouts/Authenticated'

import Link from '@/Shared/Components/Leafs/Link'
import Modal from '@/Shared/Components/Leafs/Modal'
import Button from '@/Shared/Components/Leafs/Button'
import Headline from '@/Shared/Components/Leafs/Headline'
import Paragraph from '@/Shared/Components/Leafs/Paragraph'
import LinkButton from '@/Shared/Components/Leafs/LinkButton'

import Form from '@/Shared/Components/FormElements/Form'
import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import TextField from '@/Shared/Components/Fields/TextField'
import RadioListField from '@/Shared/Components/Fields/RadioListField'

const ForgeAuthentication = () => {
    const [showModal, setShowModal] = useState(false)
    const { configuration, pipeline, accounts = [] } = useProps()

    const options = accounts.map(account => ({
        key: account.id,
        label: account.identifier,
    }))

    const { values, onChange, errors, status, disabled, post } = useForm({
        account_id: configuration ? configuration.details.account_id : '',
    })

    const {
        values: accountValues,
        onChange: onAccountChange,
        errors: accountErrors,
    } = useForm({
        name: '',
        key: '',
    })

    const onSubmit = () => {
        post(
            route('steps.configuration.configure', {
                pipeline: pipeline.id,
                step: 'forge-authentication',
            }),
            values
        )
    }

    const onConnect = () => {
        closeModal()
        post(route('accounts.forge.store'), accountValues)
    }

    const openModal = () => setShowModal(true)
    const closeModal = () => setShowModal(false)

    return (
        <Authenticated title="Select a Forge account">
            <Form className="space-y-6" onSubmit={onSubmit}>
                <RadioListField
                    label="Pick account"
                    empty="You haven't connected any accounts"
                    name="account_id"
                    value={values.account_id}
                    onChange={onChange}
                    options={options}
                    required
                    error={errors.account_id}
                />

                <FormGroup className="flex justify-between">
                    <Button
                        type="button"
                        variant="secondary"
                        onClick={openModal}
                    >
                        Connect account
                    </Button>
                    <Button type="submit">Next</Button>
                </FormGroup>
            </Form>

            <Modal
                open={showModal}
                onClose={closeModal}
                onConfirm={onConnect}
                title="Connect Forge account"
                confirm="Connect"
            >
                <div className="space-y-4">
                    <Paragraph>
                        You can find your API key in the{' '}
                        <Link
                            as="a"
                            href="https://forge.laravel.com/user/profile#/api"
                            target="_blank"
                        >
                            Forge dashboard.
                        </Link>
                    </Paragraph>
                    <Form className="space-y-6" onSubmit={onConnect}>
                        <TextField
                            label="Name"
                            name="name"
                            value={accountValues.name}
                            onChange={onAccountChange}
                            required
                            error={accountErrors.name}
                        />
                        <TextField
                            label="API Key"
                            name="key"
                            type="password"
                            value={accountValues.key}
                            onChange={onAccountChange}
                            required
                            error={accountErrors.key}
                        />
                    </Form>
                </div>
            </Modal>
        </Authenticated>
    )
}

export default ForgeAuthentication
