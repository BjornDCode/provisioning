import React from 'react'
import { Dialog } from '@headlessui/react'

import Button from '@/Shared/Components/Leafs/Button'
import Headline from '@/Shared/Components/Leafs/Headline'

const Modal = ({
    open,
    title,
    onClose,
    onConfirm,
    cancel = 'Cancel',
    confirm = 'Confirm',
    children,
}) => (
    <Dialog
        open={open}
        onClose={onClose}
        as="div"
        className="fixed inset-0 z-70 overflow-y-auto flex justify-center items-center"
    >
        <Dialog.Overlay className="fixed inset-0 bg-gray-600 opacity-40" />

        <div
            className="relative bg-gray-800 max-w-full rounded-md px-10 py-6"
            style={{ width: '30rem' }}
        >
            {title && (
                <Dialog.Title as={Headline} level="2" className="mb-8">
                    {title}
                </Dialog.Title>
            )}

            <div>{children}</div>

            <div className="flex flex-row-reverse items-center justify-between mt-6">
                <Button onClick={onConfirm}>{confirm}</Button>
                <Button onClick={onClose} variant="secondary">
                    {cancel}
                </Button>
            </div>
        </div>
    </Dialog>
)

export default Modal
