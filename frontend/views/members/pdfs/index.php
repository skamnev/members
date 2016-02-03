<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = 'My PDFs';
?>
<h1>My PDFs page</h1>

<?php if (count($pdfs_listing)) :?>

    <table class="table table-striped">
    <thead>
    <tr>
        <th><?= Yii::t('frontend', 'File Name')?></th>
        <th><?= Yii::t('frontend', 'File Url')?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($pdfs_listing as $pdf) :?>
        <tr>
            <td><?= $pdf->name?></td>
            <td><?= Html::a($pdf->file, $pdf->getUploadedFileUrl('file'), array('target' => '_blank')) ?></td>
        </tr>
    <?php endforeach ?>
    </tbody>
    </table>
<?php else: ?>
    <?= Yii::t('frontend', 'No Files for you yet.');?>
<?php endif;?>