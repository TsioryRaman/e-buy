import React, {useEffect, useState} from "react";
import {createRoot} from "react-dom/client";
import {Icon} from "../../../icons/Icon.jsx";
import {useIncrement} from "../hooks/useIncrement.js";
import {useCartFetch} from "../hooks/useCartFetch.js";

const cart = document.querySelector('#cart-notification')

export class ArticleCart extends HTMLElement {
    connectedCallback() {
        // Quantite du produit
        let quantity = parseInt(this.dataset.quantity) || 0
        // Quantite du produit commander
        let defaultQuantity = parseInt(this.dataset.default) || null
        let show = this.dataset.show ?? true
        let article = parseInt(this.dataset.article) || 0
        let cart_item = parseInt(cart?.dataset?.cart) || 0
        let user = this.dataset.user || null
        const app = createRoot(this)
        app.render(<ArticleCartComponent
            article={article}
            quantity={quantity}
            defaultQuantity={defaultQuantity}
            user={user}
            show={show}
            cart_item={cart_item}
        />)
    }
}

const Articlequantity = React.memo(({quantity}) => {
    return <h3 className="flex flex-row justify-between capitalize"><span>Quantite:</span>
        <strong>{quantity}</strong>
    </h3>
})

const ArticleCartComponent = ({quantity: _quantity, article, defaultQuantity, user, show, cart_item: cart_item}) => {
    const [quantity, setQuantity] = useState(_quantity)
    const [cartQuantity, setCartQuantity] = useState(cart_item)
    const {state, increment, decrement, setState} = useIncrement(defaultQuantity || 0, 1, quantity)
    const {loadCart, items} = useCartFetch()

    const onSubmit = (e) => {
        e.preventDefault()
        e.stopPropagation()
        loadCart('POST', {id: article, quantity: state})
    }
    const handleChange = (e) => {
        e.preventDefault()
        state > quantity ? setState(quantity) : setState(e.target.value === '' ? 0 : e.target.value)
    }

    useEffect(() => {
        setQuantity(q => items?.cart_quantity ? q - items?.cart_quantity : q)
        if (cart) {
            items?.cart_quantity > 0 || cart_item > 0 ? cart.classList.remove('hidden') : cart.classList.add('hidden')
            // cart.innerHTML = cartQuantity + ''
        }
    }, [items])


    return <div className="flex flex-col">
        {show === true && <Articlequantity quantity={quantity}/>}
        {user &&
            <div className="flex flex-row justify-between gap-2">
                <div className="flex flex-row gap-2">
                    <ArticleIcon onClick={decrement}>
                        <Icon name="minus" size="18" className="text-2xl"></Icon>
                    </ArticleIcon>
                    <div className="flex items-center justify-center">
                        <input style={{minHeight: "35px"}} onClick={e => e.stopPropagation()} type="text"
                               value={state > quantity ? quantity : state} onChange={handleChange}
                               className="w-10 py-0 min-h-8 px-2"/>
                    </div>
                    <ArticleIcon onClick={increment}>
                        <Icon name="plus" size="18" className="text-2xl"></Icon>
                    </ArticleIcon>
                </div>
                <button onClick={onSubmit}
                        className="bg-green-400 hover:bg-green-500 duration-150 p-2 rounded shadow text-white"><Icon
                    size="22" name="cart"/></button>

            </div>}
    </div>
}

const ArticleIcon = ({onClick, children}) => {

    const handleClick = (e) => {
        e.preventDefault()
        onClick()
    }

    return <div onClick={handleClick}
                className="px-2 py-1 flex items-center justify-center text-white bg-green-400 cursor-pointer hover:bg-green-500 duration-150 rounded shadow">
        {children}
    </div>
}