import React, { useState, useCallback } from 'react'

import { match } from '@/shared/helpers/methods'
import useOnPageChange from '@/shared/hooks/useOnPageChange'

import Shelf from '@/shared/components/layouts/Shelf'
import Box from '@/shared/components/base/Box'
import Text from '@/shared/components/base/Text'

import Icon from '@/shared/components/primitives/Icon'

const Container = ({ children }) => (
    <Box
        position="fixed"
        insetX={0}
        bottom={0}
        marginX={2}
        marginB={4}
        display="flex"
        justify="center"
    >
        {children}
    </Box>
)

const Message = ({ show = false, onClose, children }) => {
    return (
        show && (
            <Shelf
                justify="between"
                align="center"
                backgroundColor="green"
                backgroundShade="500"
                borderRadius="normal"
                spaceX={4}
                spaceY={3}
                shadow="lg"
                spacing={4}
                maxWidth={{ md: 'md' }}
            >
                <Text textColor="green" textShade="50" fontSize="sm" flex="1">
                    {children}
                </Text>
                <Box
                    Component="button"
                    type="button"
                    width={6}
                    height={6}
                    backgroundColor="green"
                    backgroundShade="600"
                    display="flex"
                    justify="center"
                    align="center"
                    borderRadius="sm"
                    onClick={onClose}
                >
                    <Icon name="Close" textColor="green" textShade="50" />
                </Box>
            </Shelf>
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
