<?php

/**
 * @var yii\web\View $this
 * @var string $url
 * @var string $tableName
 * @var int $tableId
 */

$this->registerJs(<<<JS
window.addEventListener('load', () => {
  const params = {
    tableName: '$tableName',
    tableId: '$tableId'
  }
  fetch('$url', {
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
    console.log(data)
  })
})
JS
, $this::POS_END);
