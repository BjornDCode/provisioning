import React from 'react'
import { Menu } from '@headlessui/react'
import { Inertia } from '@inertiajs/inertia'

import useProps from '@/Shared/Hooks/useProps'
import useClasses from '@/Shared/Hooks/useClasses'

import Icon from '@/Shared/Components/Leafs/Icon'

const TeamSwitcher = ({ className }) => {
    const { teams, currentTeam } = useProps()
    const classes = useClasses('relative', className)

    const switchTeam = id => {
        Inertia.patch(route('settings.account.update'), {
            current_team_id: id,
        })
    }

    return (
        <Menu as="div" className={classes}>
            <Menu.Button className="group flex items-center px-4 py-2 rounded-md border border-gray-500 hover:border-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-300">
                <span className="text-sm text-gray-300 font-medium mr-3 group-hover:text-gray-400">
                    {currentTeam.name}
                </span>
                <Icon
                    name="ChevronDown"
                    className="text-gray-400 group-hover:text-gray-500"
                />
            </Menu.Button>

            <Menu.Items className="absolute w-full mt-2 bg-gray-800 shadow-lg rounded-md divide-y divide-gray-700 focus:outline-none">
                {teams.map(team => (
                    <Menu.Item key={team.id}>
                        {({ active }) => (
                            <button
                                type="button"
                                className={useClasses(
                                    'flex items-center justify-between w-full text-left px-4 py-3 first:rounded-t-md last:rounded-b-md focus:outline-none',
                                    {
                                        'ring-2 ring-cyan-300 ring-inset': active,
                                    }
                                )}
                                onClick={() => switchTeam(team.id)}
                            >
                                <span className="text-sm font-medium text-gray-200 mr-3 truncate">
                                    {team.name}
                                </span>
                                {team.id === currentTeam.id && (
                                    <Icon
                                        name="Checkmark"
                                        className="text-gray-300"
                                    />
                                )}
                            </button>
                        )}
                    </Menu.Item>
                ))}
            </Menu.Items>
        </Menu>
    )
}

export default TeamSwitcher
