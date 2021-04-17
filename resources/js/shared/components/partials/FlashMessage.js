import React, { useState, useCallback } from 'react'

import { match } from '@/shared/helpers/methods'
import useOnPageChange from '@/shared/hooks/useOnPageChange'

import Icon from '@/shared/components/primitives/Icon'

const Container = ({ children }) => (
    <div className="fixed inset-x-0 bottom-0 mx-2 mb-4 flex justify-center">
        {children}
    </div>
)

const Message = ({ show = false, onClose, children }) => {
    return (
        show && (
            <div className="flex justify-between items-center bg-green-500 rounded px-4 py-3 shadow-lg space-x-4 md:max-w-md">
                <span className="text-white font-medium text-sm flex-1">
                    {children}
                </span>
                <button
                    type="button"
                    className="w-6 h-6 bg-green-600 flex justify-center items-center rounded-sm focus:outline-none focus:ring-2 focus:ring-cyan-300"
                    onClick={onClose}
                >
                    <Icon name="Close" className="text-green-50" />
                </button>
            </div>
        )
    )
}

const FlashMessage = ({ level = 'success', text = '', ...props }) => {
    const [open, setOpen] = useState(true)

    useOnPageChange(() => {
        setOpen(!!text.length)
    })

    const onClose = useCallback(() => setOpen(false), [])

    return (
        <Container>
            <Message show={open} onClose={onClose}>
                {text}
            </Message>
        </Container>
    )
}

export default FlashMessage
