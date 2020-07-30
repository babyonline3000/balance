<?php
use yii\grid\GridView;
use yii\helpers\Html;
use kartik\date\DatePicker;

//echo '<pre>' . print_r($model, true) . '</pre>';
$sum = 0;
foreach($model as $a){
    $sum += $a['summa'];
}
?>


<?= GridView::widget([
    'dataProvider' => $provider,
    'showFooter' => true,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'class' => 'yii\grid\SerialColumn',
            'headerOptions' => ['width' => '5%'],
        ],
        [
            'label' => 'Дата транзакции',
            'attribute' => 'date_check',
            'filter'=> DatePicker::widget([
                    'name' => 'from_date',
                    'type' => DatePicker::TYPE_RANGE,
                    'name2' => 'to_date',
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd'
               ]]),
            'headerOptions' => ['width' => '25%'],
        ],

        [
            'label' => 'ФИО',
            'attribute' => 'fio',
            'headerOptions' => ['width' => '30%'],
        ],
        [
            'label' => 'Телефон',
            'attribute' => 'tel',
            'filter' => yii\widgets\MaskedInput::widget([
                    'model' => $searchModel,
                    'value' => $searchModel->tel,
                    'attribute' => 'tel',
                    'mask' => '+7(999)999 99 99',
                    'clientOptions' => [
                        'alias' => 'tel',

                    ],
                ]),
            'headerOptions' => ['width' => '15%'],
        ],
        [
            'label' => 'Сумма',
            'attribute' => 'summa',
            'headerOptions' => ['width' => '10%'],
            'footer' => sprintf('%0.2f', round($sum, 2))
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'headerOptions' => ['width' => '3%'],
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url, $model) {
                        return Html::a('отменить', [
                            'site/cancel',
                            'id' => $model->id,
                            ],['onclick' => 'return confirm("Отменить транзакцию ?");']);
                    },

            ],
        ],
    ],
    'tableOptions' => [
        'class' => 'table table-striped table-bordered'
    ],
]); ?>