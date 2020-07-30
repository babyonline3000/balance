<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * ContactForm is the model behind the contact form.
 */
class CashSearch extends Cash
{
    public $from_date;
    public $to_date;

    public function rules() {
        return [
            [['summa'], 'number'],
            [['tel'], 'safe'],
            [['fio'], 'safe'],
            [['from_date', 'to_date'], 'date'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }


    public function search($params)
    {
        $query = Cash::find()
            ->select('reports.id,reports.date_check,users.fio,users.tel,reports.summa')
            ->leftJoin('users','`reports`.`id_user` = `users`.`id`')
            ->orderBy('date_check');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'summa' => $this->summa,
            'tel' => $this->tel,
            'fio' => $this->fio,
        ])
            ->andFilterWhere(['>=', 'date_check', $this->from_date])
            ->andFilterWhere(['<=', 'date_check', $this->to_date]);;

        return $dataProvider;
    }


}
