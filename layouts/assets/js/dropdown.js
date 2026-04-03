
window.addEventListener('click', (event) => {
  if (!event.target.matches('.dropdown__button')) {
    const dropdowns = document.getElementsByClassName("dropdown__content");
    for (let i = 0; i < dropdowns.length; i++) {
      const openDropdown = dropdowns[i]
      if (openDropdown.classList.contains('dropdown__content--show')) {
        openDropdown.classList.remove('dropdown__content--show')
      }
    }
  }
})

window.addEventListener('load', () => {
  const buttons = document.querySelectorAll(".dropdown__button")
  for (const button of buttons) {
    button.addEventListener('click', function(event) {
      event.preventDefault();
      const dropdown = event.target.closest('.dropdown')
      if (!dropdown) {
        return
      }
      const content = dropdown.querySelector('.dropdown__content')
      if (!content) {
        return
      }
      content.classList.toggle('dropdown__content--show')
    })
  }
})
