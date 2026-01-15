<?php
/**
 * @var \yii\web\View $this
 * @var int $id
 * @var string $title
 * @var string $notation
 * @var string $options
 * @var string $uniqid
 */
?>
<?php app\assets\Abcjs::register($this); ?>
<div class="play-along">
    <h2 class="play-along__title"><?= $title ?></h2>
    <div class="play-along__notation" id="play-along-<?= $uniqid ?>"></div>
</div>
<script>
    window.addEventListener("DOMContentLoaded", () => {
        let abc = "<?= $notation ?>";
        <?php if (!empty($options)): ?>
            let options = <?= $options ?>;
        <?php endif ?>
        let visualOptions = { responsive: 'resize' };
        window.ABCJS.renderAbc("play-along-<?= $uniqid ?>", abc, options, visualOptions);
    });
</script>
<style>
.play-along__title { margin-bottom: 0; }
.play-along__notation { margin-bottom: 2rem; }
</style>