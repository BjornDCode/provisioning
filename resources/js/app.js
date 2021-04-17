import { App } from '@inertiajs/inertia-react'
import React from 'react'
import { render } from 'react-dom'

const el = document.getElementById('app')

render(
    <App
        initialPage={JSON.parse(el.dataset.page)}
        resolveComponent={name => {
            let [namespace, ...path] = name.split('/')
            path = path.join('/')
            return require(`./${namespace}/Pages/${path}`).default
        }}
    />,
    el
)
