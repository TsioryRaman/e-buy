import React, {useState} from "react";
import '../../../css/_button.css'
import {Icon} from "../../../icons/Icon.jsx";
import {useTheme} from "../../hooks/useTheme.js";

export function SwitchTheme({selectedDefault}) {
    const [selected, setSelected] = useState(selectedDefault !== 'light')
    const {toggleTheme,theme} = useTheme(selectedDefault)

    const onChange = () => {
        setSelected(s => !s)
        toggleTheme()
    }

    return <div className="relative">
        <input id="theme" name="theme" className="hidden" checked={selected} type="checkbox" onChange={onChange}/>
        <label htmlFor="theme" className="button_theme"/>
        <span className="sun__icon">
            <Icon size={14} name="sun"></Icon>
        </span>
        <span className="moon__icon">
            <Icon size={14} name="moon"></Icon>
        </span>
    </div>
}