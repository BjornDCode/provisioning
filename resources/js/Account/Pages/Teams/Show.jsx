import React from 'react'

import useProps from '@/Shared/Hooks/useProps'

import Settings from '@/Account/Components/Layouts/Settings'

import Group from '@/Account/Components/Leafs/Group'

import List from '@/Shared/Components/Leafs/List'
import ListItem from '@/Shared/Components/Leafs/ListItem'

const Show = () => {
    const { team, members = [] } = useProps()

    return (
        <Settings title={team.name}>
            <Group title="Members">
                <List>
                    {members.map(member => (
                        <ListItem key={member.id}>
                            {member.name}{' '}
                            <span className="text-sm text-gray-400">
                                ({member.email})
                            </span>
                        </ListItem>
                    ))}
                </List>
            </Group>
            <Group title="Invitations"></Group>
        </Settings>
    )
}

export default Show
