const notifications = document.querySelectorAll('#notification-flash')

let timeout;
notifications.forEach(element => {
    element.classList.add('reveal_top')
    timeout = setTimeout(() => {
        element.remove()
    },3000)
})
