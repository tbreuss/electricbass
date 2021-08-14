window.addEventListener('click', (event) => {

  if (event.target.matches('.navbar-dropdown__search')) {
    // do nothing
    return
  }

  const openedDropdowns = document.getElementsByClassName("navbar-dropdown__content--show");
  for (let i = 0; i < openedDropdowns.length; i++) {
    const openedDropdown = openedDropdowns[i]
    openedDropdown.classList.remove('navbar-dropdown__content--show')
  }

  if (event.target.matches('.navbar-dropdown__button')) {
    event.preventDefault()
    const dropdown = event.target.closest('.navbar-dropdown')
    if (!dropdown) {
      return
    }
    const content = dropdown.querySelector('.navbar-dropdown__content')
    if (!content) {
      return
    }
    content.classList.add('navbar-dropdown__content--show')
  }

})