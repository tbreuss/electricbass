<div id="responseDiv"></div>
<script>
    window.addEventListener('load', function() {
        fetch('<?= app\helpers\Url::to(['/fingering/category']) ?>', {
            method: 'POST',
            headers: {
                'X-CSRF-Token': document.head.querySelector("[name~=csrf-token][content]").content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ category: '<?= $category ?>' })
        })
            .then(response => response.text())
            .then(html => {
                document.getElementById('responseDiv').innerHTML = html;
            })
            .catch(error => {
                document.getElementById('responseDiv').innerHTML = 'Fehler: ' + error.message;
            });
    });
</script>
