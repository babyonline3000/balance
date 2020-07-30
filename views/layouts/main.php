<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\models\Data;
use app\widgets\Alert;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Teleport',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Список пользователей', 'url' => ['/site/index']],
            '<li><a id="id_a" style="cursor: pointer;">Регистрация</a></li>',
            '<li><a id="id_a_cash" style="cursor: pointer;">Cash</a></li>',
            ['label' => 'Cash history', 'url' => ['/site/cash_history']],

        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>


<!--модальное окно регистрация пользователя-->
<?php
$model = new Data();
Modal::begin([
    'id' => 'myModal',
    'header' => '<h4 style="padding-left: 10px">Регистрация пользователя</h4>',
    'clientOptions' => [
    'backdrop' => 'static',
    'keyboard' => false,
    ],
    'size' => Modal::SIZE_DEFAULT,
    'footer' => '<button type="submit" class="btn btn-success btn-md" id="but_save">Сохранить</button>',
]);?>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'id' => 'form_reg',
    'method' => 'POST',
    'action' => ['site/index'],
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-lg-4',
            'offset' => 'col-lg-offset-0',
            'wrapper' => 'col-lg-8',
        ],
    ],
]); ?>

<?= $form->field($model, 'id_ajax')->hiddenInput([
    'id' => 'id_hidden_reg',
])->label(false) ?>

<?= $form->field($model, 'fio')->textInput([
    'id' => 'id_form_fio',
    'placeholder' => 'ФИО',
]) ?>

<?= $form->field($model, 'tel')->widget('yii\widgets\MaskedInput', [
    'options' => [
        'id' => 'id_form_tel',
        'placeholder' => 'Номер телефона',
    ],
    'mask' => '+7(999)999 99 99',
]) ?>

<?php ActiveForm::end(); ?>
<?php Modal::end();?>







<!--модальное окно пополнение средств-->
<?php
$model = new Data();
Modal::begin([
    'id' => 'myModalCash',
    'header' => '<h4 style="padding-left: 10px">Пополнение средств</h4>',
    'clientOptions' => [
        'backdrop' => 'static',
        'keyboard' => false,
    ],
    'size' => Modal::SIZE_DEFAULT,
    'footer' => '<button type="submit" class="btn btn-success btn-md" id="but_save_cash">Пополнить</button>',
]);?>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'id' => 'form_cash',
    'method' => 'POST',
    'action' => ['site/index'],
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-lg-4',
            'offset' => 'col-lg-offset-0',
            'wrapper' => 'col-lg-8',
        ],
    ],
]); ?>

<?= $form->field($model, 'id_ajax')->hiddenInput([
    'id' => 'id_hidden_cash',
])->label(false) ?>

<?= $form->field($model, 'balans')->textInput([
    'id' => 'id_form_balans_cash',
    'type' => 'number',
    'min' => 0,
    'placeholder' => 'Внесите средства',
]) ?>

<?= $form->field($model, 'tel')->widget('yii\widgets\MaskedInput', [
    'options' => [
        'id' => 'id_form_tel_cash',
        'placeholder' => 'Номер телефона',
    ],
    'mask' => '+7(999)999 99 99',
]) ?>

<?php ActiveForm::end(); ?>
<?php Modal::end();?>

<?php
$script = <<<JS
    $(function(){
        $('#id_a').on('click',function(){
            $('#form_reg').trigger('reset');
            $('#myModal').modal('show');
        });

        $('#id_a_cash').on('click',function(){
            $('#form_cash').trigger('reset');
            $('#myModalCash').modal('show');
        });


        $('#but_save').on('click',function(){
            var i = $('#id_form_fio').val();
            var r = $('#id_form_tel').val();
            if(i.length < 1 || r.length < 1){
                return;
            }
            $('#myModal').modal('hide');
            $('#id_hidden_reg').val(0);//insert
            var form = $('#form_reg').serializeArray();
            var arr = $('#form_reg');
//            console.log(form);return;
            $.ajax({
                type : arr.attr('method'),
                url : arr.attr('action'),
                data : form
            }).done(function(response) {
                    $('#id_table').html(response);
                }).fail(function() {
                    console.log('error');
                });
            return false;
        });

        $('#but_save_cash').on('click',function(){
            var i = $('#id_form_balans_cash').val();
            var r = $('#id_form_tel_cash').val();
            if(i.length < 1 || r.length < 1){
                return;
            }
            $('#myModalCash').modal('hide');
            $('#id_hidden_cash').val(1);//cash
            var form = $('#form_cash').serializeArray();
            var arr = $('#form_cash');
//            console.log(form);return;
            $.ajax({
                type : arr.attr('method'),
                url : arr.attr('action'),
                data : form
            }).done(function(response) {
                    if(response == 404){
                        alert('Пополнение не возможно! Пользователь не зарегистрирован или заблокирован! Обратитесь в службу поддержки.');
                    }else{
                        $('#id_table').html(response);
                    }
                }).fail(function() {
                    console.log('error');
                });
            return false;
        });









    })
JS;
$this->registerJs($script,yii\web\View::POS_END);
?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
