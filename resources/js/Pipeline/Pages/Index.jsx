import React from 'react'

import useProps from '@/Shared/Hooks/useProps'

import Authenticated from '@/Shared/Components/Layouts/Authenticated'

import List from '@/Shared/Components/Leafs/List'
import Group from '@/Shared/Components/Leafs/Group'
import LinkButton from '@/Shared/Components/Leafs/LinkButton'
import ListItemLink from '@/Shared/Components/Leafs/ListItemLink'

const Index = () => {
    const {
        pending = [],
        running = [],
        failed = [],
        successful = [],
    } = useProps()

    return (
        <Authenticated title="Pipelines">
            {pending.length > 0 && (
                <Group title="Pending pipelines">
                    <List as="div">
                        {pending.map(pipeline => (
                            <ListItemLink
                                key={pipeline.id}
                                to={route('pipelines.show', {
                                    pipeline: pipeline.id,
                                })}
                            >
                                {pipeline.name}
                            </ListItemLink>
                        ))}
                    </List>
                </Group>
            )}

            {running.length > 0 && (
                <Group title="Running pipelines">
                    <List as="div">
                        {running.map(pipeline => (
                            <ListItemLink
                                key={pipeline.id}
                                to={route('pipelines.show', {
                                    pipeline: pipeline.id,
                                })}
                            >
                                {pipeline.name}
                            </ListItemLink>
                        ))}
                    </List>
                </Group>
            )}

            {failed.length > 0 && (
                <Group title="Failed pipelines">
                    <List as="div">
                        {failed.map(pipeline => (
                            <ListItemLink
                                key={pipeline.id}
                                to={route('pipelines.show', {
                                    pipeline: pipeline.id,
                                })}
                            >
                                {pipeline.name}
                            </ListItemLink>
                        ))}
                    </List>
                </Group>
            )}

            {successful.length > 0 && (
                <Group title="Successful pipelines">
                    <List as="div">
                        {successful.map(pipeline => (
                            <ListItemLink
                                key={pipeline.id}
                                to={route('pipelines.show', {
                                    pipeline: pipeline.id,
                                })}
                            >
                                {pipeline.name}
                            </ListItemLink>
                        ))}
                    </List>
                </Group>
            )}
            <div class="flex justify-end">
                <LinkButton to={route('pipelines.create')}>
                    Create pipeline
                </LinkButton>
            </div>
        </Authenticated>
    )
}

export default Index
