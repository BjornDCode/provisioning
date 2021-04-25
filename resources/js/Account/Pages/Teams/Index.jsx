import React from 'react'

import useProps from '@/Shared/Hooks/useProps'

import Settings from '@/Account/Components/Layouts/Settings'

import Group from '@/Account/Components/Leafs/Group'
import Paragraph from '@/Shared/Components/Leafs/Paragraph'

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
            <Group title="Your teams">
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
            </Group>
            <Group title="Teams you are a member of">
                {memberships.length > 0 && (
                    <List>
                        {memberships.map(team => (
                            <ListItem key={team.id}>{team.name}</ListItem>
                        ))}
                    </List>
                )}
                {memberships.length === 0 && (
                    <Paragraph>You are not part of any teams yet.</Paragraph>
                )}
            </Group>
        </Settings>
    )
}

export default Index
