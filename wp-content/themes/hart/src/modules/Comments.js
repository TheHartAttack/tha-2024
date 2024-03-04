import axios from "axios"
axios.defaults.headers.common["X-WP-Nonce"] = hartData.nonce

class Comments {
	constructor(comments) {
		this.comments = comments
		this.count = comments.querySelector(".comments__count > span")
		this.form = comments.querySelector(".comments__form")
		this.input = comments.querySelector(".form__input[name='comment']")
		this.inputButtons = comments.querySelector(".form__footer")
		this.cancel = comments.querySelector(".form__footer-link--cancel")
		this.submit = comments.querySelector(".form__submit")
		this.individualCommentsContainer = comments.querySelector(".comments__comments")
		this.individualComments = comments.querySelectorAll(".comment")
		this.loadMore = comments.querySelector(".comments__load")
		this.events()
	}

	events() {
		this.input?.addEventListener("keyup", () => this.handleInput())
		this.cancel?.addEventListener("click", e => this.cancelComment(e))
		this.form?.addEventListener("submit", e => this.postComment(e))
		this.loadMore?.addEventListener("click", e => this.loadMoreComments(e))
		this.individualComments.forEach(comment => {
			comment.querySelectorAll(".comment__like-icon").forEach(icon => icon.addEventListener("click", e => this.commentLikeClickDispatcher(e, comment)))
		})
	}

	handleInput() {
		this.inputButtons.classList.toggle("form__footer--hidden", !this.input.value)
	}

	cancelComment(e) {
		e.preventDefault()
		this.input.value = ""
		this.handleInput()
	}

	async postComment(e, parent = 0) {
		e.preventDefault()
		try {
			this.submit.setAttribute("disabled", "disabled")
			this.submit.classList.add("form__submit--loading")

			const response = await axios({
				url: `${hartData.rootUrl}/wp-json/hart/v1/addComment`,
				method: "post",
				data: {
					postID: this.comments.dataset.post,
					parentID: parent,
					comment: this.input.value,
				},
			})

			console.log(response)

			if (response.data.success) {
				//Add new comment to DOM
				const commentHTML = this.buildCommentHTML(response.data.newComment)
				this.individualCommentsContainer.insertAdjacentHTML("afterbegin", commentHTML)
				this.individualCommentsContainer.querySelectorAll(".comment--new").forEach(comment => {
					comment.querySelectorAll(".comment__like-icon").forEach(icon => {
						icon.addEventListener("click", e => this.commentLikeClickDispatcher(e, comment))
					})
					comment.classList.remove("comment--new")
				})

				//Increase comment count
				let count = parseInt(this.count.textContent, 10)
				count++
				this.count.textContent = count

				//Handle submit button
				this.submit.removeAttribute("disabled")
				this.submit.classList.remove("form__submit--loading")

				//Clear input
				this.input.value = ""
				this.handleInput()
			} else {
				throw new Error(response.data.message)
			}
		} catch (error) {
			this.submit.removeAttribute("disabled")
			this.submit.classList.remove("form__submit--loading")
			console.log(error.message)
		}
	}

	buildCommentHTML(data) {
		return `<div class="comment comment--new" data-id="${data.ID}" data-like="" data-liked="false">
      <img class="comment__image" src="${data.author.image}" alt="${data.author.name}">

      <div class="comment__info">
        <a class="comment__author" href="${data.author.url}">${data.author.name}</a>
        <span class="comment__time">Just now</span>
      </div>

      <p class="comment__content">${data.content}</p>

      <div class="comment__likes">
        <div class="comment__like comment__like--active">
          <span class="comment__like-count">0</span>
          <i class="comment__like-icon fa-regular fa-thumbs-up"></i>
          <i class="comment__like-icon fa-solid fa-thumbs-up"></i>
        </div>       
      </div>
    </div>`
	}

	commentLikeClickDispatcher(e, comment) {
		if (Number(hartData.loggedIn)) {
			if (comment.dataset.liked == "true") {
				this.deleteCommentLike(comment)
			} else {
				this.createCommentLike(comment)
			}
		}
	}

	async deleteCommentLike(comment) {
		try {
			const count = comment.querySelector(".comment__like-count")

			const response = await axios({
				url: `${hartData.rootUrl}/wp-json/hart/v1/manageCommentLike`,
				method: "delete",
				data: { likeID: comment.dataset.like },
			})

			if (response.data.success) {
				comment.setAttribute("data-liked", "false")
				let likeCount = parseInt(count.textContent, 10)
				likeCount--
				count.textContent = likeCount
				comment.setAttribute("data-like", "")
			} else {
				throw new Error(response.data.message)
			}
		} catch (error) {
			console.log(error.message)
		}
	}

	async createCommentLike(comment) {
		try {
			const count = comment.querySelector(".comment__like-count")
			const icon = comment.querySelector(".comment__like-icon")

			const response = await axios({
				url: `${hartData.rootUrl}/wp-json/hart/v1/manageCommentLike`,
				method: "post",
				data: { commentID: comment.dataset.id },
			})

			if (response.data.success) {
				comment.setAttribute("data-liked", "true")
				let likeCount = parseInt(count.textContent, 10)
				likeCount++
				count.textContent = likeCount
				comment.setAttribute("data-like", response.data.commentLike)
				icon.classList.add("post__like--animate")
			} else {
				throw new Error(response.data.message)
			}
		} catch (error) {
			console.log(error.message)
		}
	}

	async loadMoreComments(e) {
		e.preventDefault()
		try {
			const alreadyLoaded = []
			this.individualCommentsContainer.querySelectorAll(".comment").forEach(comment => {
				alreadyLoaded.push(comment.dataset.id)
			})

			const response = await axios({
				url: `${hartData.rootUrl}/wp-json/hart/v1/loadMoreComments`,
				method: "get",
				params: {
					postID: this.comments.dataset.id,
					alreadyLoaded: alreadyLoaded,
				},
			})

			console.log(response.data)

			if (response.data.success) {
				//Build and insert comment HTML
				let html = ""
				response.data.comments.forEach(comment => {
					html += this.buildCommentHTML(comment)
				})
				this.individualCommentsContainer.insertAdjacentHTML("beforeend", html)

				//Search for and loop through newly inserted comments and add necessary event listeners
				this.individualCommentsContainer.querySelectorAll(".comment--new").forEach(comment => {
					comment.querySelectorAll(".comment__like-icon").forEach(icon => {
						icon.addEventListener("click", e => this.commentLikeClickDispatcher(e, comment))
					})
					comment.classList.remove("comment--new")
				})

				//Remove load button if there are no more comments to load
				if (!response.data.moreToLoad) {
					this.loadMore.classList.add("comments__load--hidden")
				}
			} else {
				throw new Error(response.data.message)
			}
		} catch (error) {
			console.log(error.message)
		}
	}
}

export default Comments
