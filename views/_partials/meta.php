<div class="meta">
    <?php if (!empty($categories)): ?>
    <div class="meta_category">Gepostet in:
        <?php
        $delim = '';
        foreach ($categories as $category) {
            echo $delim;
            echo app\helpers\Html::a($category['label'], $category['url'], ['class' => 'meta__link']);
            $delim = ' &gt; ';
        }
        ?>
    </div>
    <?php endif; ?>
    <?php if (!empty($tags)): ?>
    <div class="meta_tags">Tagged:
        <?php
        if (is_string($tags)) {
            $tags = explode(',', $tags);
        }
        $delim = '';
        foreach ($tags as $tag) {
            echo $delim;
            echo $tag;
            $delim = ', ';
        }
        ?>
    </div>
    <?php endif; ?>
</div>
