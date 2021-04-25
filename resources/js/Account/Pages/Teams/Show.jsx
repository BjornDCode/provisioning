import React from 'react'

import useProps from '@/Shared/Hooks/useProps'

import Settings from '@/Account/Components/Layouts/Settings'

import Group from '@/Account/Components/Leafs/Group'
import Paragraph from '@/Shared/Components/Leafs/Paragraph'

import List from '@/Shared/Components/Leafs/List'
import ListItem from '@/Shared/Components/Leafs/ListItem'

const Show = () => {
    const { team, members = [], invitations = [] } = useProps()

    return (
        <Settings title={team.name}>
            <Group title="Members">
                {members.length > 0 && (
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
                )}
                {members.length === 0 && <Paragraph>No members yet.</Paragraph>}
            </Group>
            <Group title="Invitations">
                {invitations.length === 0 && (
                    <Paragraph>No invitations yet.</Paragraph>
                )}
            </Group>
        </Settings>
    )
}

export default Show
