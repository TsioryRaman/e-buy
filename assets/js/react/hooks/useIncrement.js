import {useCallback, useState} from "react";
export function useIncrement(initialState= 0,pas = 1,maxTarget=Infinity,minTarget = 0) {
    const [state,setState] = useState(initialState)

    const increment = useCallback(_ => {
        if(maxTarget >= (state + pas))
        {
            setState((s) => s + pas)
        }
    },[state])

    const decrement = useCallback( _ => {
        if(minTarget <= (state - pas))
        {
            setState(s => s - pas)
        }
    },[state])

    return {
        state,
        increment,
        decrement,
        setState
    }
}