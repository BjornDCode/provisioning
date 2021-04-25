import React from 'react'

import useForm from '@/Shared/Hooks/useForm'

import Form from '@/Shared/Components/FormElements/Form'
import FormGroup from '@/Shared/Components/FormElements/FormGroup'

import TextField from '@/Shared/Components/Fields/TextField'
import Button from '@/Shared/Components/Leafs/Button'

const InlineForm = ({ onSubmit, cta, value, error, label, name }) => {
    const { values, onChange, errors, status, disabled, post, reset } = useForm(
        {
            [name]: '',
        }
    )

    const handleSubmit = () => {
        onSubmit({ values, post })
        reset()
    }

    return (
        <Form onSubmit={handleSubmit}>
            <FormGroup className="flex items-end space-x-6">
                <TextField
                    label={label}
                    name={name}
                    value={values[name]}
                    onChange={onChange}
                    error={errors[name]}
                    required
                    containerClassName="flex-1"
                    disabled={disabled}
                />
                <Button
                    variant="secondary"
                    size="large"
                    className="w-auto px-10 leading-3"
                    disabled={disabled}
                >
                    {cta}
                </Button>
            </FormGroup>
        </Form>
    )
}

export default InlineForm
