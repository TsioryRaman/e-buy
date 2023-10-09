export const enabledRoute = function ()
{
    let links = document.querySelectorAll("#link");
    links.forEach(link => {
        if(link.getAttribute('aria-current') === 'page'){
            link.classList.add('text-green-500')
        }
    })
}