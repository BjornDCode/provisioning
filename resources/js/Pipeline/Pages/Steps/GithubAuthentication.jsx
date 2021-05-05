import React from 'react'

import Authenticated from '@/Shared/Components/Layouts/Authenticated'

import Headline from '@/Shared/Components/Leafs/Headline'
import LinkButton from '@/Shared/Components/Leafs/LinkButton'

const GithubAuthentication = () => (
    <Authenticated title="Select a GitHub account">
        <Headline level="2">Existing accounts</Headline>

        <div className="flex justify-end">
            <LinkButton to={route('accounts.redirect', { provider: 'github' })}>
                Connect account
            </LinkButton>
        </div>
    </Authenticated>
)

export default GithubAuthentication
