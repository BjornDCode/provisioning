import { breakpoints } from '@/Shared/Helpers/constants'

const useBreakpoint = () => {
    const breakpoint = Object.keys(breakpoints)
        .reverse()
        .find(
            key => window.matchMedia(`(min-width: ${breakpoints[key]})`).matches
        )

    return breakpoint
}

export default useBreakpoint
