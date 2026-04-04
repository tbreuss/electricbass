<?php
/**
 * @var string[][] $urlFragments
 * @var string[] $defaults
 */
?>
<script>
    function updatePage(page) {
        const params = new URLSearchParams(window.location.hash.substring(1));
        const sort = params.get('sort') ?? '<?= $defaults['sort'] ?>';
        const artist = params.get('artist') ?? '<?= $defaults['artist'] ?>';
        window.location.hash = '#page=' + page + '&sort=' + sort + '&artist=' + artist;
    }

    function updateSort(sort) {
        const params = new URLSearchParams(window.location.hash.substring(1));
        const page = params.get('page') ?? '<?= $defaults['page'] ?>';
        const artist = params.get('artist') ?? '<?= $defaults['artist'] ?>';
        window.location.hash = '#page=' + page + '&sort=' + sort + '&artist=' + artist;
    }

    function replacePage(urlSearchParams) {
        const params = {};
        urlSearchParams.forEach((value, key) => {
            params[key] = value;
        });

        up.render({
            target: ".content-wrap",
            url: "<?= app\helpers\Url::current() ?>",
            method: "post",
            params: params,
        });
    }

    window.addEventListener("DOMContentLoaded", () => {
        window.addEventListener("hashchange", (event) => {
            const locationHash = location.hash.substring(1);
            const hashPart = event.newURL.split('#');
            if (hashPart[1]) {
                replacePage(new URLSearchParams(hashPart[1]));
            }
        });

        const urlFragments = <?= json_encode($urlFragments) ?>;
        const locationHash = location.hash.substring(1);
        if ((locationHash !== '') && !urlFragments.includes(locationHash)) {
            replacePage(new URLSearchParams(locationHash));
        }
    });
</script>
