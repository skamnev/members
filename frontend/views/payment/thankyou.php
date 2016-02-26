<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Thank you';
$this->params['breadcrumbs'][] = $this->title;

$currency = 'NOK';
$totalValue = '199';
$conversion_label = Yii::$app->session->get('payment_google_conversion_label');
?>
<h1><?= Yii::t('frontend', 'Thank you!');?></h1>

<?php if (!empty($conversion_label)) :?>
<!-- Google Code for Purchase Conversion Page -->
<script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 1234567890;
    var google_conversion_language = "en_US";
    var google_conversion_format = "1";
    var google_conversion_color = "666666";
    var google_conversion_label = "<?php echo $conversion_label ?>";
    <?php if ($totalValue):?>
    var google_conversion_value = <?php echo $totalValue ?>
    var google_conversion_currency = <?php echo $currency ?>
    <?php else:?>
    var google_conversion_value = 199;
    var google_conversion_currency = "NOK";
    <?php endif;?>
    /* ]]> */ 
    </script>
    <script type="text/javascript"
    src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<img height=1 width=1 border=0
src="//www.googleadservices.com/pagead/
conversion/1234567890/?value=
<? echo $totalValue ?>&conversion_currency=<? echo $currency ?>
&label=Purchase&script=0">
</noscript>
<?php endif;?>
</body>