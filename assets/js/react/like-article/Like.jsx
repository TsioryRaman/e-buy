import React, {useEffect, useState} from "react";
import {createRoot} from "react-dom/client";
import {useFetch} from "../hooks/useFetch.js";
import {Icon} from "../../../icons/Icon.jsx";

export class LikeElement extends HTMLElement {
    connectedCallback() {
        const article = parseInt(this.dataset.article)
        const liked = Boolean(this.dataset.liked.trim())
        const app = createRoot(this)
        app.render(<Like article={article} like={liked}/>)
    }
}

function Like({article, like}) {
    const {load, data} = useFetch('http://localhost:8000/article/like', 'POST', {article_id: article})
    const [liked, setLiked] = useState(false)
    const handleClick = async (e) => {
        e.preventDefault()
        load()
    }

    useEffect(() => {
        setLiked(data)
    },[data])

    useEffect(() => {
        setLiked(like)
    },[like])

    return <span onClick={handleClick}>
        <Icon name="heart" className={`heart__icon ${liked ? "heart__icon_liked" : ""}`}></Icon>
    </span>
}

