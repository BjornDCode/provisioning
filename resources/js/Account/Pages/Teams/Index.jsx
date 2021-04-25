import React from 'react'

import useProps from '@/Shared/Hooks/useProps'

import Settings from '@/Account/Components/Layouts/Settings'

import Headline from '@/Shared/Components/Leafs/Headline'

import InlineForm from '@/Shared/Components/FormElements/InlineForm'

import List from '@/Shared/Components/Leafs/List'
import ListItem from '@/Shared/Components/Leafs/ListItem'
import ListItemLink from '@/Shared/Components/Leafs/ListItemLink'

const Index = () => {
    const { owned, memberships } = useProps()

    const onCreateTeam = ({ values, post }) => {
        post(route('settings.teams.store'), values)
    }

    return (
        <Settings title="Teams">
            <div className="space-y-12">
                <div className="space-y-4">
                    <Headline level="2">Your teams</Headline>

                    <List as="div">
                        {owned.map(team => (
                            <ListItemLink
                                key={team.id}
                                to={route('settings.teams.show', {
                                    team: team.id,
                                })}
                            >
                                {team.name}
                            </ListItemLink>
                        ))}
                    </List>

                    <InlineForm
                        onSubmit={onCreateTeam}
                        cta="Create team"
                        label="New team"
                        name="name"
                    />
                </div>
                <div>
                    <Headline level="2" className="mb-4">
                        Teams you are a member of
                    </Headline>

                    <List>
                        {memberships.map(team => (
                            <ListItem key={team.id}>{team.name}</ListItem>
                        ))}
                    </List>
                </div>
            </div>
        </Settings>
    )
}

export default Index
