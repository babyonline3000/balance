<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * ContactForm is the model behind the contact form.
 */
class Data extends Model
{
    public $id;
    public $id_ajax;
    public $tel;
    public $fio;
    public $balans;
    public $status;
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['tel'], 'required'],
            [['fio','tel'], 'string'],
            [['id','id_ajax','status'], 'integer'],
            [['balans'], 'default','value' => 0],
            [['fio','tel'], 'default','value' => ''],
            [['status'],'in', 'range' => [0,1]],
            ['balans', 'number'],
            [['fio','tel'], 'filter', 'filter' => function ($value) {
                    $value = preg_replace('/[\'\"\;]/','',$value);
                    $result = str_replace('--','',$value);
                    return $result;
                }],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'fio' => 'ФИО',
            'tel' => 'Номер телефона',
            'balans' => 'Денежные средства',
        ];
    }


}
