import { Icon } from "./Icon";
import { SearchElement } from "./js/search/search";
import {LikeElement} from "./js/react/like-article/Like.jsx";
import {ArticleCart} from "./js/react/article-commande/article-command.jsx";
import {ButtonThemeElement} from "./js/react/theme/button-theme.jsx";

customElements.define("icon-feather",Icon)
customElements.define("search-article",SearchElement)
customElements.define("like-article",LikeElement)
customElements.define("article-commande",ArticleCart)
customElements.define("button-theme",ButtonThemeElement)