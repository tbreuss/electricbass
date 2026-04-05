<?php
/**
 * @var array<string, mixed> $applied
 * @var array<string, mixed> $defaults
 */
?>
<script>
    function updateLocationHash(params) {
        const currentHash = new URLSearchParams(window.location.hash.substring(1));
        const filters = <?= json_encode($defaults) ?>;
        let newHash = new URLSearchParams();

        for (const key in filters) {
            if (filters.hasOwnProperty(key)) {
                if (params[key]) {
                    newHash.append(key, params[key]);
                } else if (currentHash.has(key)) {
                    newHash.append(key, currentHash.get(key));
                } else {
                    newHash.append(key, filters[key])
                }
            }
        }

        window.location.hash = '#' + newHash.toString();
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

    window.addEventListener("hashchange", (event) => {
        const hashPart = event.newURL.split('#');
        if (hashPart[1]) {
            replacePage(new URLSearchParams(hashPart[1]));
        }
    });

    window.addEventListener("DOMContentLoaded", () => {
        const urlFragments = new URLSearchParams(<?= json_encode($applied) ?>);
        const locationHash = location.hash.substring(1);
        if ((locationHash !== '') && urlFragments.toString() !== locationHash) {
            replacePage(new URLSearchParams(locationHash));
        }
    });
</script>
