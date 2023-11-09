import {useEffect, useState} from "react";
import {useFetch} from "./useFetch.js";

export function useTheme(defaultTheme) {
    const [theme, setTheme] = useState(defaultTheme || localStorage.theme || 'light')
    const {theme:data,load} = useFetch()
    const toggleTheme = () => {
        setTheme(t => (t === 'dark') ? 'light' : 'dark')
    }
    useEffect(() => {
        localStorage.theme = theme
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
        (async function()
        {
            try{
                const response = await load('http://localhost:8000/user/theme', 'POST', {theme})
                console.log(response)
            }catch (e)
            {
                console.log(e)
            }
        })()
    }, [theme])
    return {
        theme,
        toggleTheme
    }
}