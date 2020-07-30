<?php use nickdenry\grid\toggle\components\RoundSwitchColumn;
use yii\grid\GridView; ?>


<?= GridView::widget([
    'dataProvider' => $provider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'label' => 'ФИО',
            'attribute' => 'fio',
            'headerOptions' => [
                'width' => '50%',
            ],
        ],
        [
            'label' => 'Телефон',
            'attribute' => 'tel',
        ],
        [
            'label' => 'Баланс',
            'attribute' => 'balans',
        ],
        [
            'class' => RoundSwitchColumn::class,
            'attribute' => 'status',
            'label' => 'Статус',
            'action' => 'toggle-and-send',
            'headerOptions' => ['width' => '1%'],
            'filter' => [
                'someActive' => 'Active',
                'someInactive' => 'Inactive',
            ]
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'headerOptions' => ['width' => '3%'],
            'template' => '{delete}'
        ],
    ],
    'tableOptions' => [
        'class' => 'table table-striped table-bordered'
    ],
]); ?>