import React, { useState } from 'react'

import List from '@/Shared/Components/Leafs/List'
import IconButton from '@/Shared/Components/Leafs/IconButton'
import ListItem from '@/Shared/Components/Leafs/ListItem'

import FormGroup from '@/Shared/Components/FormElements/FormGroup'
import FormInput from '@/Shared/Components/FormElements/FormInput'
import FormError from '@/Shared/Components/FormElements/FormError'
import FormLabel from '@/Shared/Components/FormElements/FormLabel'

const ListItemWithAction = ({ onDelete, children }) => (
    <ListItem
        Right={() => (
            <IconButton name="Close" type="button" onClick={onDelete} />
        )}
    >
        {children}
    </ListItem>
)

const ListField = ({ label, name, value = [], onChange, required, error }) => {
    const [newItem, setNewItem] = useState('')

    const onKeyPress = event => {
        if (event.code !== 'Enter') {
            return
        }

        event.preventDefault()

        onChange({
            target: {
                name,
                value: [...value, newItem],
            },
        })

        setNewItem('')
    }

    const handleChange = event => {
        setNewItem(event.target.value)
    }

    const handleDelete = item => {
        onChange({
            target: {
                name,
                value: [...value].filter(x => x !== item),
            },
        })
    }

    return (
        <FormGroup className="relative">
            <FormLabel input={name}>{label}</FormLabel>

            <List>
                {value.map(item => (
                    <ListItemWithAction
                        key={item}
                        onDelete={() => handleDelete(item)}
                    >
                        {item}
                    </ListItemWithAction>
                ))}
            </List>

            <FormInput
                placeholder="Environment name"
                value={newItem}
                onChange={handleChange}
                onKeyPress={onKeyPress}
                className="mt-4"
            />

            {error && <FormError className="absolute mt-1">{error}</FormError>}
        </FormGroup>
    )
}

export default ListField
