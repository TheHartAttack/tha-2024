import axios from "axios"
axios.defaults.headers.common["X-WP-Nonce"] = hartData.nonce

class Contact {
	constructor() {
		this.addContactHTML()
		this.contact = document.querySelector(".contact-overlay")
		this.openContactButton = document.querySelector(".header__menu-link--contact")
		this.closeContactButton = this.contact.querySelector(".contact-overlay__close")
		this.isContactOpen = false
		this.invalidKeypressElements = ["input", "textarea"]
		this.form = this.contact.querySelector(".contact-overlay__form")
		this.name = this.contact.querySelector(".form__input[name='contact_name']")
		this.email = this.contact.querySelector(".form__input[name='contact_email']")
		this.message = this.contact.querySelector(".form__textarea[name='contact_message']")
		this.submit = this.contact.querySelector(".form__submit")
		this.events()
	}

	events() {
		this.openContactButton.addEventListener("click", e => this.openContact(e))
		this.closeContactButton.addEventListener("click", e => this.closeContact(e))
		document.addEventListener("keydown", e => this.keypressHandler(e))
		this.form.addEventListener("submit", e => this.handleSubmit(e))
	}

	openContact(e) {
		e.preventDefault()
		this.contact.classList.add("contact-overlay--active")
		this.name.value = hartData.user.name
		this.email.value = hartData.user.email
		this.message.value = ""
		this.contact.querySelector(".form__message")?.remove()
		this.isContactOpen = true
	}

	closeContact(e) {
		this.contact.classList.remove("contact-overlay--active")
		this.isContactOpen = false
	}

	keypressHandler(e) {
		if (e.keyCode == 67 && !this.isContactOpen && !this.invalidKeypressElements.includes(document.activeElement.tagName.toLowerCase()) && !document.querySelector(".search-overlay--active")) {
			this.openContact(e)
		}

		if (e.keyCode == 27 && this.isContactOpen) {
			this.closeContact(e)
		}
	}

	async handleSubmit(e) {
		e.preventDefault()
		try {
			this.submit.setAttribute("disabled", "disabled")
			this.submit.classList.add("form__submit--loading")

			const response = await axios({
				url: `${hartData.rootUrl}/wp-json/hart/v1/contact`,
				method: "post",
				data: {
					name: this.name.value,
					email: this.email.value,
					message: this.message.value,
				},
			})

			console.log(response)

			if (response.data.success) {
				this.form.insertAdjacentHTML("beforeend", `<span class="form__message">${response.data.message}</span>`)
				this.name.value = ""
				this.email.value = ""
				this.message.value = ""
				this.submit.removeAttribute("disabled")
				this.submit.classList.remove("form__submit--loading")

				setTimeout(() => {
					this.closeContact()
				}, 5000)
			} else {
				throw new Error(response.data.message)
			}
		} catch (error) {
			console.log(error.message)
			this.form.insertAdjacentHTML("beforeend", `<span class="form__message">${error.message}</span>`)
			this.submit.removeAttribute("disabled")
			this.submit.classList.remove("form__submit--loading")
		}
	}

	addContactHTML() {
		document.querySelector("footer").insertAdjacentHTML(
			"afterend",
			`
			<div class="contact-overlay">
				<a class="contact-overlay__close" href="#"><i class="fa-solid fa-xmark"></i></a>

				<div class="contact-overlay__container">
					<form class="contact-overlay__form form" novalidate>
						<div class="form__header">
							<h3 class="form__heading contact-overlay__form-heading">Contact Me</h3>
						</div>
			
						<div class="form__group">
							<label class="form__label" for="contact-name">Name</label>
							<input class="form__input" id="contact-name" type="text" name="contact_name" required value="${hartData.user.name}">
						</div>
			
						<div class="form__group">
							<label class="form__label" for="contact-email">Email</label>
							<input class="form__input" id="contact-email" type="email" name="contact_email" required value="${hartData.user.email}">
						</div>

						<div class="form__group">
							<label class="form__label" for="contact-message">Message</label>
							<textarea class="form__textarea form__textarea--large" id="contact-message" name="contact_message" required></textarea>
						</div>
						
						<div class="form__footer">
							<button class="form__submit" type="submit" name="contact_submit" style="--icon: '\\f0e0';">Send Message</button>
						</div>
					</form>
				</div>
			</div>
		`
		)
	}
}

export default Contact
