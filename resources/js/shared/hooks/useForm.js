import { useState, useEffect } from 'react'
import { Inertia } from '@inertiajs/inertia'
import { usePage } from '@inertiajs/inertia-react'

import useProps from '@/Shared/Hooks/useProps'

const useForm = defaultValues => {
    const { errors: validationErrors } = useProps()
    const page = usePage().component

    const [values, setValues] = useState(defaultValues)
    const [errors, setErrors] = useState(validationErrors)
    const [status, setStatus] = useState('idle')

    useEffect(() => {
        setErrors(validationErrors)
    }, [page, validationErrors])

    const onChange = event => {
        setValues({
            ...values,
            [event.target.name]:
                event.target.type === 'checkbox'
                    ? event.target.checked
                    : event.target.value,
        })
        setErrors({
            ...errors,
            [event.target.name]: '',
        })
    }

    const onChanges = values => {
        setValues(values)
        setErrors({})
    }

    const post = (route, values) => {
        setStatus('processing')
        Inertia.post(route, values).then(() => {
            setStatus('idle')
        })
    }

    const reset = () => setValues(defaultValues)

    const disabled = status !== 'idle'

    return {
        values,
        onChange,
        onChanges,
        errors,
        status,
        disabled,
        post,
        reset,
    }
}

export default useForm
