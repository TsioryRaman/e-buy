import {useCallback, useState} from "react";

export const useFetch = (url, method = "POST", data) => {
    const [loading, setLoading] = useState(false)
    const [items, setItems] = useState()
    const [errors, setErrors] = useState({})
    const load = useCallback(async () => {
        setLoading(true)
        const response = await fetch(url, {
            method: method,
            credentials: "include",
            headers: {
                'content-type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        const responseData = await response.json()
        if (response.ok) {
            setItems(d => responseData)
        } else {
            setErrors(responseData)
        }
        setLoading(false)
    }, [url, data, method])

    return {
        loading: loading,
        load: load,
        data: items,
        errors: errors
    }
}