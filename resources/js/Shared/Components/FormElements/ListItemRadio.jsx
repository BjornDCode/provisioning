import React from 'react'
import { RadioGroup } from '@headlessui/react'

import useClasses from '@/Shared/Hooks/useClasses'

import ListItem from '@/Shared/Components/Leafs/ListItem'
import RadioCircle from '@/Shared/Components/FormElements/RadioCircle'

const ListItemRadio = ({ value, children }) => {
    return (
        <RadioGroup.Option value={value} className="focus:outline-none">
            {({ checked, active }) => (
                <ListItem
                    className={useClasses(
                        'cursor-pointer border-2 first:rounded-t-lg last:rounded-b-lg',
                        {
                            'border-green-300': checked && !active,
                            'border-gray-600': !checked && !active,
                            'outline-none border-cyan-300': active,
                        }
                    )}
                    Right={() => <RadioCircle checked={checked} />}
                >
                    {console.log(active)}
                    {children}
                </ListItem>
            )}
        </RadioGroup.Option>
    )
}

export default ListItemRadio
