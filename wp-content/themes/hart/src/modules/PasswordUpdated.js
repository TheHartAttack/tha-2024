class PasswordUpdated {
  constructor(){
    this.redirect()
  }

  redirect(){
    setTimeout(() => {
      window.location.href = hartData.rootUrl
    }, 10000)
  }
}

export default PasswordUpdated