<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * ContactForm is the model behind the contact form.
 */
class Cash extends ActiveRecord
{
    public $fio;
    public $tel;

    public static function tableName(){
        return 'reports';
    }

    public function getUsers()
    {
        return $this->hasOne(Users::className(), ['id' => 'id_user']);
    }

    /*public function getSumma() {
        return $this->summa;
    }*/
}
