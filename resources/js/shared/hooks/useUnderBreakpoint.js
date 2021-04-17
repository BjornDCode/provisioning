import useBreakpoint from '@/Shared/Hooks/useBreakpoint'
import { breakpoints } from '@/Shared/Helpers/constants'

const useUnderBreakpoint = breakpoint => {
    const keys = Object.keys(breakpoints)
    const currentBreakpoint = useBreakpoint()

    const breakpointIndex = keys.findIndex(key => key === breakpoint)
    const currentBreakpointIndex = keys.findIndex(
        key => key === currentBreakpoint
    )

    return breakpointIndex > currentBreakpointIndex
}

export default useUnderBreakpoint
