<?php
/**
 * https://codepen.io/brianknapp/pen/JEotD/
 * https://css-tricks.com/five-methods-for-five-star-ratings/
 *
 * @var string $style left, right, none
 * @var int $ratingCount
 * @var float $ratingAverage
 */
?>

<?php if ($ratingCount > 0) : ?>
<div class="ratingReadonly ratingReadonly--<?php echo $style  ?>" data-stars="<?php echo round($ratingAverage) ?>">
    <svg viewBox="0 0 23 25" class="ratingReadonly__star">
        <polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
    </svg>
    <svg viewBox="0 0 23 25" class="ratingReadonly__star">
        <polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
    </svg>
    <svg viewBox="0 0 23 25" class="ratingReadonly__star">
        <polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
    </svg>
    <svg viewBox="0 0 23 25" class="ratingReadonly__star">
        <polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
    </svg>
    <svg viewBox="0 0 23 25" class="ratingReadonly__star">
        <polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
    </svg>
    <span class="ratingReadonly__text"><?php echo number_format(round($ratingAverage, 1), 1, '.', '') ?> bei <?php echo intval($ratingCount) ?> <?php echo (($ratingCount > 1) ? 'Stimmen' : 'Stimme') ?></span>
</div>
<?php endif; ?>
