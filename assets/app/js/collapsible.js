
window.addEventListener('load', () => {
  const buttons = document.querySelectorAll("[data-collapsible]")
  for (const button of buttons) {
    button.addEventListener('click', (event) => {
      event.preventDefault()
      const selector = event.target.dataset.collapsible
      if (!selector) {
        return
      }
      const elements = document.querySelectorAll(selector)
      for (const element of elements) {
        element.classList.toggle('collapsible--show')
      }
    })
  }
})
