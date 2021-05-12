import React from 'react'

import useClasses from '@/Shared/Hooks/useClasses'
import { match } from '@/Shared/Helpers/methods'

import Icon from '@/Shared/Components/Leafs/Icon'
import Button from '@/Shared/Components/Leafs/Button'

import StandaloneListItem from '@/Pipeline/Components/Leafs/StandaloneListItem'

const StepListItem = ({ id, status, children }) => (
    <StandaloneListItem
        className={useClasses('border-2 relative', {
            'border-gray-300': status === 'pending',
            'border-green-400': status === 'successful',
            'border-red-400': status === 'failed',
            'border-yellow-300': status === 'running',
            'border-gray-500': status === 'cancelled',
        })}
        Text={({ text }) => (
            <span
                className={useClasses('font-medium', {
                    'text-gray-100': status === 'pending',
                    'text-green-100': status === 'successful',
                    'text-red-100': status === 'failed',
                    'text-yellow-100': status === 'running',
                    'text-gray-400': status === 'cancelled',
                })}
            >
                {text}
            </span>
        )}
        Right={() => (
            <div
                className={useClasses('p-1 rounded-full', {
                    'bg-gray-300': status === 'pending',
                    'bg-green-400': status === 'successful',
                    'bg-red-400': status === 'failed',
                    'bg-yellow-300': status === 'running',
                    'bg-gray-500': status === 'cancelled',
                })}
            >
                <Icon
                    className="text-gray-600 w-5 h-5"
                    name={match(status, {
                        pending: 'Dots',
                        running: 'Loading',
                        failed: 'Close',
                        successful: 'Checkmark',
                        cancelled: 'Minus',
                    })}
                />
            </div>
        )}
    >
        {children}

        {status === 'failed' && (
            <form
                className="absolute top-1/2 left-full ml-4 transform -translate-y-1/2"
                onSubmit={event => event.preventDefault()}
            >
                <Button variant="secondary">Retry</Button>
            </form>
        )}
    </StandaloneListItem>
)

export default StepListItem
