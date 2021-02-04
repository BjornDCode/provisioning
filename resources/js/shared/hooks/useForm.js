import { useState } from 'react'

const useForm = defaultValues => {
    const [values, setValues] = useState(defaultValues)

    const onChange = event => {
        setValues({
            ...values,
            [event.target.name]:
                event.target.type === 'checkbox'
                    ? event.target.checked
                    : event.target.value,
        })
    }

    return [values, onChange]
}

export default useForm
