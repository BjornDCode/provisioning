import React, { Fragment, useEffect } from 'react'
import { Inertia } from '@inertiajs/inertia'

import useProps from '@/Shared/Hooks/useProps'

import Authenticated from '@/Shared/Components/Layouts/Authenticated'

import Button from '@/Shared/Components/Leafs/Button'
import Paragraph from '@/Shared/Components/Leafs/Paragraph'

import StepListItem from '@/Pipeline/Components/Steps/StepListItem'
import StandaloneList from '@/Pipeline/Components/Leafs/StandaloneList'

const Show = () => {
    const { pipeline, steps } = useProps()

    const reload = () => {
        Inertia.reload({ only: ['pipeline', 'steps'] })
    }

    useEffect(() => {
        window.Echo.private(`pipeline.${pipeline.id}`)
            .listen('PipelineStepRunning', reload)
            .listen('PipelineStepFailed', reload)
            .listen('PipelineStepSuccessful', reload)
    }, [])

    const onSubmit = event => {
        event.preventDefault()

        Inertia.post(
            route('pipelines.execute', {
                pipeline: pipeline.id,
            })
        )
    }

    return (
        <Authenticated title={`Provisioning ${pipeline.name}`}>
            {pipeline.status === 'pending' && (
                <Fragment>
                    <Paragraph>
                        Your project is ready to be provisioned. Click the
                        button below to start the pipeline.
                    </Paragraph>
                    <form onSubmit={onSubmit}>
                        <Button size="large" type="submit">
                            Run pipeline
                        </Button>
                    </form>
                </Fragment>
            )}
            {pipeline.status === 'running' && (
                <Paragraph>
                    Your project is being provisioned. It will be ready in a few
                    minutes.
                </Paragraph>
            )}
            {pipeline.status === 'failed' && (
                <Fragment>
                    <Paragraph>
                        A problem occured in your pipeline. Please update the
                        configuration or try and run the pipeline again.
                    </Paragraph>
                    <form onSubmit={onSubmit}>
                        <Button size="large" type="submit">
                            Re-run pipeline
                        </Button>
                    </form>
                </Fragment>
            )}
            {pipeline.status === 'successful' && (
                <Paragraph>Your project has been provisioned.</Paragraph>
            )}

            <StandaloneList>
                {steps.map(step => (
                    <StepListItem
                        key={step.id}
                        id={step.id}
                        status={step.status}
                        type={step.type}
                        pipelineId={pipeline.id}
                    >
                        {step.title}
                    </StepListItem>
                ))}
            </StandaloneList>
        </Authenticated>
    )
}

export default Show
