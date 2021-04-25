import React from 'react'
import { App } from '@inertiajs/inertia-react'
import { render } from 'react-dom'
import { InertiaProgress } from '@inertiajs/progress'

const el = document.getElementById('app')

InertiaProgress.init({
    color: '#67E8F9',
})

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
