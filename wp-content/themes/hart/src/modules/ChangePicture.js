class ChangePicture {
	constructor(page) {
		this.page = page
		this.upload = page.querySelector(".form__upload")
		this.label = page.querySelector(".form__upload-label")
		this.input = page.querySelector(".form__upload-input")
		this.existing = page.querySelector(".form__upload-existing")
		this.events()
	}

	events() {
		this.input.addEventListener("change", () => {
			if (this.input.files.length) {
				this.setUploadImage()
			}
		})

		this.label.addEventListener("click", e => {
			if (this.input.files.length || this.existing.value) {
				e.preventDefault()
				this.removeUploadImage()
			}
		})
	}

	setUploadImage() {
		const image = URL.createObjectURL(this.input.files[0])
		this.upload.classList.add("form__upload--preview")
		this.upload.style.setProperty("--bg-image", `url('${image}')`)
	}

	removeUploadImage() {
		this.input.value = null
		this.upload.classList.remove("form__upload--preview")
		this.upload.style.removeProperty("--bg-image")
		this.existing.value = ""
	}
}

export default ChangePicture
