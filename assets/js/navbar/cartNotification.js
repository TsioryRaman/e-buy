
const cart = document.querySelector('#cart-notification')
export const CartNotification = () => {
    console.log(cart)
    if(cart)
    {
        let cart_item = parseInt(cart.dataset.cart) || 0
        if(cart_item > 0)
        {
            cart.classList.remove('hidden')
        }else {
            cart.classList.add('hidden')
        }
    }
}