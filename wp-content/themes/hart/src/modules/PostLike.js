import axios from "axios"
axios.defaults.headers.common["X-WP-Nonce"] = hartData.nonce

class PostLike {
	constructor(postLike) {
		this.postLike = postLike
		this.icon = postLike.querySelectorAll(".post__like-icon")
		this.count = postLike.querySelector(".post__like-count")
		this.events()
	}

	events() {
		this.icon.forEach(icon => icon.addEventListener("click", e => this.clickDispatcher(e)))
	}

	clickDispatcher(e) {
		if (Number(hartData.loggedIn)) {
			if (this.postLike.dataset.liked == "true") {
				this.deletePostLike()
			} else {
				this.createPostLike()
			}
		}
	}

	async deletePostLike() {
		try {
			const response = await axios({
				url: `${hartData.rootUrl}/wp-json/hart/v1/managePostLike`,
				method: "delete",
				data: { likeID: this.postLike.dataset.like },
			})

			// console.log(response)

			if (response.data.success) {
				this.postLike.setAttribute("data-liked", "false")
				let likeCount = parseInt(this.count.textContent, 10)
				likeCount--
				this.count.textContent = likeCount
				this.postLike.setAttribute("data-like", "")
			} else {
				throw new Error(response.data.message)
			}
		} catch (error) {
			console.log(error.message)
		}
	}

	async createPostLike() {
		try {
			const response = await axios({
				url: `${hartData.rootUrl}/wp-json/hart/v1/managePostLike`,
				method: "post",
				data: { postID: this.postLike.dataset.post },
			})

			// console.log(response)

			if (response.data.success) {
				this.postLike.setAttribute("data-liked", "true")
				let likeCount = parseInt(this.count.textContent, 10)
				likeCount++
				this.count.textContent = likeCount
				this.postLike.setAttribute("data-like", response.data.postLike)
				this.postLike.classList.add("post__like--animate")
			} else {
				throw new Error(response.data.message)
			}
		} catch (error) {
			console.log(error.message)
		}
	}
}

export default PostLike
