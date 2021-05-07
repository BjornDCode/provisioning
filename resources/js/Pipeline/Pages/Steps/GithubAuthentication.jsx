import React from 'react'

import useForm from '@/Shared/Hooks/useForm'
import useProps from '@/Shared/Hooks/useProps'

import Authenticated from '@/Shared/Components/Layouts/Authenticated'

import Button from '@/Shared/Components/Leafs/Button'
import Headline from '@/Shared/Components/Leafs/Headline'
import LinkButton from '@/Shared/Components/Leafs/LinkButton'

import Form from '@/Shared/Components/FormElements/Form'
import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import RadioListField from '@/Shared/Components/Fields/RadioListField'

const GithubAuthentication = () => {
    const { configuration, project, accounts = [] } = useProps()

    const options = accounts.map(account => ({
        key: account.id,
        label: account.identifier,
    }))

    const { values, onChange, errors, status, disabled, post } = useForm({
        account_id: configuration ? configuration.details.account_id : '',
    })

    const onSubmit = () => {
        // post(
        //     route('steps.configuration.configure', {
        //         project: project.id,
        //         step: 'git-provider',
        //     }),
        //     values
        // )
    }

    return (
        <Authenticated title="Select a GitHub account">
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
                    <LinkButton
                        as="a"
                        href={route('accounts.redirect', {
                            provider: 'github',
                        })}
                    >
                        Connect account
                    </LinkButton>
                    <Button type="submit">Next</Button>
                </FormGroup>
            </Form>
        </Authenticated>
    )
}

export default GithubAuthentication
