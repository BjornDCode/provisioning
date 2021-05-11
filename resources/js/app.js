import React from 'react'
import Pusher from 'pusher-js'
import Echo from 'laravel-echo'
import { App } from '@inertiajs/inertia-react'
import { render } from 'react-dom'
import { InertiaProgress } from '@inertiajs/progress'

const el = document.getElementById('app')

InertiaProgress.init({
    color: '#67E8F9',
})

window.Pusher = Pusher

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
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
