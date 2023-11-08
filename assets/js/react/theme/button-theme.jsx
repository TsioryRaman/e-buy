import React, {useEffect} from "react";
import {createRoot} from "react-dom/client";
import {Icon} from "../../../icons/Icon.jsx";
import {useTheme} from "../../hooks/useTheme.js";
import {SwitchTheme} from "./switch-theme.jsx";

export class ButtonThemeElement extends HTMLElement {
    connectedCallback() {
        const defaultTheme = this.dataset.defaultTheme
        const app = createRoot(this)
        app.render(<ButtonTheme userDefault={defaultTheme} defaultTheme={defaultTheme || localStorage.theme || 'light'}/>)
    }
}

function ButtonTheme({defaultTheme}) {

    return <SwitchTheme selectedDefault={defaultTheme}/>
}

