import axios from "axios"
import dayjs from "dayjs"
import AdvancedFormat from "dayjs/plugin/advancedFormat"
dayjs.extend(AdvancedFormat)

class Search {
	constructor() {
		this.addSearchHTML()
		this.search = document.querySelector(".search-overlay")
		this.openSearchButton = document.querySelector(".header__menu-link--search")
		this.closeSearchButton = this.search.querySelector(".search-overlay__close")
		this.searchInput = this.search.querySelector(".search-overlay__input")
		this.searchResultsDiv = this.search.querySelector(".search-overlay__results")
		this.isSearchOpen = false
		this.isLoading = false
		this.invalidKeypressElements = ["input", "textarea"]
		this.previousValue
		this.typingTimer
		this.events()
	}

	events() {
		this.openSearchButton.addEventListener("click", e => this.openSearch(e))
		this.closeSearchButton.addEventListener("click", e => this.closeSearch(e))
		document.addEventListener("keydown", e => this.keypressHandler(e))
		this.searchInput.addEventListener("keyup", () => this.typingLogic())
	}

	openSearch(e) {
		e.preventDefault()
		this.search.classList.add("search-overlay--active")
		this.searchInput.value = ""
		this.searchResultsDiv.innerHTML = ""
		this.searchInput.focus()
		this.isSearchOpen = true
	}

	closeSearch(e) {
		this.search.classList.remove("search-overlay--active")
		this.searchInput.blur()
		this.isSearchOpen = false
	}

	keypressHandler(e) {
		if (e.keyCode == 83 && !this.isSearchOpen && !this.invalidKeypressElements.includes(document.activeElement.tagName.toLowerCase()) && !document.querySelector(".contact-overlay--active")) {
			this.openSearch(e)
		}

		if (e.keyCode == 27 && this.isSearchOpen) {
			this.closeSearch(e)
		}
	}

	typingLogic() {
		if (this.searchInput.value != this.previousValue) {
			clearTimeout(this.typingTimer)

			if (this.searchInput.value) {
				if (!this.isLoading) {
					this.searchResultsDiv.innerHTML = `<div class="search-overlay__loading"></div>`
					this.isLoading = true
				}
				this.typingTimer = setTimeout(() => this.getResults(), 500)
				this.previousValue = this.searchInput.value
			} else {
				this.searchResultsDiv.innerHTML = ""
				this.isLoading = false
				this.previousValue = ""
			}
		}
	}

	async getResults() {
		try {
			const response = await axios.get(`${hartData.rootUrl}/wp-json/wp/v2/posts?search=${this.searchInput.value}&per_page=100`)

			if (response.data) {
				let html = ""

				response.data.map(post => {
					const date = new Date(post.date)

					html += `
						<a class="post-panel search-overlay__result" href="${post.link}">
							<img class="post-panel__image" src="${post.featured_image}" alt="${post.title.rendered}">
						
							<div class="post-panel__inner">
								<h3 class="post-panel__heading">${post.title.rendered}</h3>
								<time class="post-panel__date">${dayjs(date).format("Do MMMM YYYY")}</time>
								<span class="post-panel__read">Read post</span>
							</div>
						</a>
					`
				})

				this.searchResultsDiv.innerHTML = html
				this.isLoading = false
			} else {
				throw new Error("Something went wrong.")
			}
		} catch (error) {
			console.log(error)
			this.isLoading = false
		}
	}

	addSearchHTML() {
		document.querySelector("footer").insertAdjacentHTML(
			"afterend",
			`
			<div class="search-overlay">
				<a class="search-overlay__close" href="#"><i class="fa-solid fa-xmark"></i></a>

				<div class="search-overlay__container">
					<div class="search-overlay__head">
						<i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
						<input type="text" class="search-overlay__input" placeholder="Search..." id="search-term" autocomplete="off">
					</div>
				</div>

				<div class="search-overlay__body">
					<div class="search-overlay__container">
						<div class="search-overlay__results"></div>
					</div>
				</div>
			</div>
		`
		)
	}
}

export default Search
