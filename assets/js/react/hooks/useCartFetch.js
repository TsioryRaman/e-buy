import {useState} from "react";

const URL = "http://localhost:8000/api/cart"
export const useCartFetch = () => {

    const [items, setItems] = useState(null)
    const [errors, setErrors] = useState(null)
    const loadCart = async (method,data) => {
        const response = await fetch(URL, {
            method: method,
            credentials: 'include',
            headers: {
                'content-type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        let responseData = await response.json()
        if (response.ok) {
            setItems(responseData)
        } else {
            setErrors(responseData)
        }
    }

    return {
        items,
        errors,
        loadCart
    }
}