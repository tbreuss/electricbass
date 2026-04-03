<?php

/**
 * @var string $content
 */
?>
<?= eval(str_replace(['<?php', '?>'], ' ', $content));
