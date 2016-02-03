<div class="mapping-steps">
<?php foreach ($steps['steps'] as $key => $step) :?>
    <span class="step<?= ($key == (count($steps['steps']) - 1))?' current':'';?>">
        <?= $step;?>
    </span>
<?php endforeach; ?>
</div>