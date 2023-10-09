export class Icon extends HTMLElement {
    
    connectedCallback()
    {

        let size = this.getAttribute('size') || 24
        let name = this.getAttribute('name')
        let classes = this.getAttribute('class') || null

        const href = `/assets/icons/sprite.svg#${name}`
        this.innerHTML = `
            <svg height=${size} class=${classes} width=${size}>
                <use xlink:href=${href} />
            </svg>
        `
    }
}
