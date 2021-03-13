import React, { useEffect } from 'react'
import { usePage } from '@inertiajs/inertia-react'

const useOnPageChange = (callable = () => {}) => {
    const page = usePage().component

    return useEffect(callable, [page])
}

export default useOnPageChange
