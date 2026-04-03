<?php
/**
 * https://codepen.io/brianknapp/pen/JEotD/
 * https://css-tricks.com/five-methods-for-five-star-ratings/
 *
 * @var string $url
 * @var string $tableName
 * @var int $tableId
 * @var int $ratingCount
 * @var float $ratingAverage
 * @var float $yourRating
 */
?>

<div class="rating-share">
    <div class="rating" data-count="<?php echo $ratingCount ?>" data-stars="<?php echo round($ratingAverage) ?>" data-context="<?php echo $tableName ?>" data-id="<?php echo $tableId ?>">
        <svg viewBox="0 0 23 25" class="rating__star" data-rating="1">
            <polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
        </svg>
        <svg viewBox="0 0 23 25" class="rating__star" data-rating="2">
            <polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
        </svg>
        <svg viewBox="0 0 23 25" class="rating__star" data-rating="3">
            <polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
        </svg>
        <svg viewBox="0 0 23 25" class="rating__star" data-rating="4">
            <polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
        </svg>
        <svg viewBox="0 0 23 25" class="rating__star" data-rating="5">
            <polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
        </svg>
        <span class="rating__text <?php echo ($ratingCount > 0) ? '' : 'rating__text--hidden' ?>">
            <span class="rating__textAverage"><?php echo number_format(floatval($ratingAverage), 1, '.', '') ?></span>
            bei
            <span class="rating__textCount"><?php echo $ratingCount ?></span>
            Stimmen
        </span>
        <span class="rating__empty <?php echo ($ratingCount > 0) ? 'rating__empty--hidden' : '' ?>">Bewerten</span>
    </div>
    <div class="share">
        <span class="share-title">Teilen</span>
        <a class="share-link" href="#" onclick="navigator.share({title: document.title, text:'', url:document.URL});return false;" aria-label="Teilen">
            <svg class="share-button" viewBox="0 0 64 64" width="28" height="28"><title>Teilen</title><circle cx="12" cy="32" r="9"></circle><circle cx="52" cy="10" r="9"></circle><circle cx="52" cy="54" r="9"></circle><path d="M12 30.3l40-22v3.4l-40 22l40 22v-3.4l-40-22"></path></svg>
        </a>
    </div>
    <script type="text/javascript">if(!navigator.share) { document.querySelector('.share').style.display = 'none'; }</script>
</div>
