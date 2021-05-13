import React from 'react'
import { RadioGroup } from '@headlessui/react'

import useClasses from '@/Shared/Hooks/useClasses'

import ListItem from '@/Shared/Components/Leafs/ListItem'
import RadioCircle from '@/Shared/Components/FormElements/RadioCircle'

const ListItemRadio = ({ value, children }) => {
    return (
        <RadioGroup.Option
            value={value}
            className="group focus:outline-none first:rounded-t-lg last:rounded-b-lg"
        >
            {({ checked, active }) => (
                <ListItem
                    className={useClasses(
                        'cursor-pointer border-2 group-first:rounded-t-lg group-last:rounded-b-lg',
                        {
                            'border-green-300': checked && !active,
                            'border-gray-600': !checked && !active,
                            'outline-none border-cyan-300': active,
                        }
                    )}
                    Right={() => <RadioCircle checked={checked} />}
                >
                    {children}
                </ListItem>
            )}
        </RadioGroup.Option>
    )
}

export default ListItemRadio
