<?php

/**
 * @var string $imgAlt
 * @var string $imgContent
 * @var string $imgType
 * @var string $url
 */
?>
<a href="<?= $url ?>" target="_blank">
    <img class="img-fluid" src="data:image/<?= $imgType ?>;base64,<?= base64_encode($imgContent) ?>" width="300" height="250" alt="<?= $imgAlt ?>">
</a>
