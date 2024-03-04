import {throttle} from 'underscore'

class Header {
  constructor(header){
    this.header = header
    this.openMobileMenuButton = header.querySelector(".header__open-mobile-menu")
    this.mobileMenu = document.querySelector(".mobile-menu")
    this.events()
  }

  events(){
    //Page scroll
    window.addEventListener("scroll", throttle(this.toggleScrolled.bind(this), 125))

    //Open mobile menu
    this.openMobileMenuButton.addEventListener("click", (e) => this.openMobileMenu(e))
  }

  toggleScrolled(){
    this.header.classList.toggle("header--scrolled", window.scrollY > 282)
  }

  openMobileMenu(e){
    e.preventDefault()

    if (this.mobileMenu){
      this.openMobileMenuButton.classList.add("header__open-mobile-menu--active")
      this.mobileMenu.classList.add("mobile-menu--active")
    }
  }
}

export default Header