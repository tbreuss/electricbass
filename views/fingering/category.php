<div class="input-group d-block d-md-none">
    <select class="form-select" onchange="window.location.href = this.value">
        <option selected>— Inhaltsverzeichnis —</option>
        <?php foreach ($models as $model): ?>
            <option value="<?= $model->url ?>"><?= $model->title ?></option>
        <?php endforeach ?>
    </select>
</div>

<ul class="table-of-contents d-none d-md-block">
    <?php foreach ($models as $model): ?>
        <li><a href="<?= $model->url ?>"><?= $model->title ?></a></li>
    <?php endforeach ?>
</ul>
