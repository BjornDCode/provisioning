import React from 'react'

import useProps from '@/Shared/Hooks/useProps'

import Authenticated from '@/Shared/Components/Layouts/Authenticated'

import Headline from '@/Shared/Components/Leafs/Headline'
import LinkButton from '@/Shared/Components/Leafs/LinkButton'

const GithubAuthentication = () => {
    const { configuration, project } = useProps()

    return (
        <Authenticated title="Select a GitHub account">
            <Headline level="2">Existing accounts</Headline>

            <div className="flex justify-end">
                <LinkButton
                    as="a"
                    href={route('accounts.redirect', {
                        provider: 'github',
                    })}
                >
                    Connect account
                </LinkButton>
            </div>
        </Authenticated>
    )
}

export default GithubAuthentication
