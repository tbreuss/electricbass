window.addEventListener('click', (event) => {
  const svg = event.target.closest('.rating__star')
  const container = event.target.closest('.rating')
  if (!svg || !container) {
    return
  }
  const params = {
    tableName: container.dataset.context,
    tableId: container.dataset.id,
    ratingValue: svg.dataset.rating
  }
  fetch('/api/rate', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(params)
  })
    .then((res) => {
      return res.json()
    })
    .then((data) => {
      container.dataset.stars = parseInt(data.ratingAverage, 10)
      container.querySelector('.rating__textAverage').textContent = data.ratingAverage
      container.querySelector('.rating__textCount').textContent = data.ratingCount
      container.querySelector('.rating__text').classList.remove('rating__text--hidden')
      container.querySelector('.rating__empty').classList.add('rating__empty--hidden')
    })
})
