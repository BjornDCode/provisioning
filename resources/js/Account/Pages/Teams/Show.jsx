import React from 'react'
import { Inertia } from '@inertiajs/inertia'

import useProps from '@/Shared/Hooks/useProps'

import Settings from '@/Account/Components/Layouts/Settings'

import Group from '@/Account/Components/Leafs/Group'
import Paragraph from '@/Shared/Components/Leafs/Paragraph'
import IconButton from '@/Shared/Components/Leafs/IconButton'

import List from '@/Shared/Components/Leafs/List'
import ListItem from '@/Shared/Components/Leafs/ListItem'

import InlineForm from '@/Shared/Components/FormElements/InlineForm'

const ListItemWithAction = ({ onAction, children }) => (
    <ListItem Right={() => <IconButton name="Close" onClick={onAction} />}>
        {children}
    </ListItem>
)

const Show = () => {
    const { team, members = [], invitations = [] } = useProps()

    const onCreateInvitation = ({ values, post }) => {
        post(
            route('settings.teams.invitations.store', { team: team.id }),
            values
        )
    }

    const onDeleteInvitation = id => {
        Inertia.delete(
            route('settings.teams.invitations.destroy', {
                team: team.id,
                invitation: id,
            })
        )
    }

    return (
        <Settings title={team.name}>
            <Group title="Members">
                {members.length > 0 && (
                    <List>
                        {members.map(member => (
                            <ListItem key={member.id}>
                                {member.name}
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
                {invitations.length > 0 && (
                    <List>
                        {invitations.map(invitation => (
                            <ListItemWithAction
                                key={invitation.id}
                                onAction={() =>
                                    onDeleteInvitation(invitation.id)
                                }
                            >
                                {invitation.email}
                            </ListItemWithAction>
                        ))}
                    </List>
                )}
                {invitations.length === 0 && (
                    <Paragraph>No invitations yet.</Paragraph>
                )}
                <InlineForm
                    onSubmit={onCreateInvitation}
                    cta="Invite someone"
                    label="Email"
                    name="email"
                />
            </Group>
        </Settings>
    )
}

export default Show
