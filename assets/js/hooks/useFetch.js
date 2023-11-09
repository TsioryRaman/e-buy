import {useState} from "react";

export function useFetch()
{
    const [items,setData] = useState(null)
    const load = async (url,method,data) => {
        const response = await fetch(url,{
            method:method,
            headers: {
                'content-type':'application/json',
            },
            body:JSON.stringify(data),
            credentials: 'include'
        })
        let responseData = await response.json()
        if (response.ok) {
            setData(i => responseData)
        }
    }
    return {
        load,
        data:items
    }
}