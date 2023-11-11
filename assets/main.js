import './css/App.css'
import './icons/icons.css'
import './custom-element'
import "./js/route-enabled.js";
import {multipleUpload} from "./js/multiple-upload.js";
import './js/index.js'
import './js/notification-flash.js'
import '/js/carousel.js'
import '/js/functions/theme.js'
import '/js/functions/auto-upload.js'
import TurboLinks from 'turbolinks'

multipleUpload()

/**
 * Evite le chargement ajax lors de l'utilisation d'une ancre
 *
 * cf : https://github.com/turbolinks/turbolinks/issues/75
 */
document.addEventListener('turbolinks:click', e => {
    const anchorElement = e.target
    const isSamePageAnchor =
        anchorElement.hash &&
        anchorElement.origin === window.location.origin &&
        anchorElement.pathname === window.location.pathname

    if (isSamePageAnchor) {
        Turbolinks.controller.pushHistoryWithLocationAndRestorationIdentifier(e.data.url, Turbolinks.uuid())
        e.preventDefault()
        window.dispatchEvent(new Event('hashchange'))
    }
})

TurboLinks.start()