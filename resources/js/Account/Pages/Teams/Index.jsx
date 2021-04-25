import React from 'react'

import useProps from '@/Shared/Hooks/useProps'

import Settings from '@/Account/Components/Layouts/Settings'

import Headline from '@/Shared/Components/Leafs/Headline'

import List from '@/Shared/Components/Leafs/List'
import ListItem from '@/Shared/Components/Leafs/ListItem'
import ListItemLink from '@/Shared/Components/Leafs/ListItemLink'

const Index = () => {
    const { owned, memberships } = useProps()

    return (
        <Settings title="Teams">
            <div className="space-y-12">
                <div>
                    <Headline level="2" className="mb-4">
                        Your teams
                    </Headline>

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
