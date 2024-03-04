import "../css/style.sass"

import Header from "./modules/Header"
import MobileMenu from "./modules/MobileMenu"
import PasswordUpdated from "./modules/PasswordUpdated"
import ChangePicture from "./modules/ChangePicture"
import Search from "./modules/Search"
import Contact from "./modules/Contact"
import PostLike from "./modules/PostLike"
import Comments from "./modules/Comments"

for (const header of document.querySelectorAll(".header")) {
	new Header(header)
}

for (const menu of document.querySelectorAll(".mobile-menu")) {
	new MobileMenu(menu)
}

for (const page of document.querySelectorAll(".password-updated")) {
	new PasswordUpdated(page)
}

for (const page of document.querySelectorAll(".change-picture")) {
	new ChangePicture(page)
}

for (const postLike of document.querySelectorAll(".post__like")) {
	new PostLike(postLike)
}

for (const comments of document.querySelectorAll(".comments")) {
	new Comments(comments)
}

const search = new Search()
const contact = new Contact()
