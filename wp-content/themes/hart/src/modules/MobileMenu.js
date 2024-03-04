class MobileMenu {
  constructor(menu){
    this.menu = menu
    this.closeButton = menu.querySelector(".mobile-menu__close")
    this.openButton = document.querySelector(".header__open-mobile-menu")
    this.events()
  }

  events(){
    this.closeButton.addEventListener("click", (e) => this.closeMobileMenu(e))
  }

  closeMobileMenu(e){
    e.preventDefault()
    
    this.menu.classList.remove("mobile-menu--active")
    if (this.openButton){
      this.openButton.classList.remove("header__open-mobile-menu--active")
    }
  }
}

export default MobileMenu