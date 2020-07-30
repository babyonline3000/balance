<?php

namespace app\controllers;

use app\models\Cash;
use app\models\CashSearch;
use app\models\Data;
use app\models\Users;
use nickdenry\grid\toggle\actions\ToggleAction;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;

class SiteController extends Controller
{

    public function actionIndex()
    {
        $model_data = new Data();

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (($model_data->load(Yii::$app->request->post()) && $model_data->validate())){
                switch($model_data->id_ajax){
                    case 0:
                        $model = new Users();
                        $model->fio = $model_data->fio;
                        $model->tel = $model_data->tel;
                        $model->save();
                        break;
                    case 1:
                        $model = Users::findOne(['tel' => $model_data->tel]);
                        if($model->status == 0){
                            return 404;
                        }

                        $model->balans += $model_data->balans;
                        $model->update();

                        $cash = new Cash();
                        $cash->id_user = $model->id;
                        $cash->summa = $model_data->balans;
                        $cash->save();
                        break;
                }

                $query = Users::find();
                $provider = new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                    'sort' => false,
                ]);
                return $this->renderAjax('spisok',compact('provider'));
            }
            exit;
        }

        $query = Users::find();
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => false,
        ]);
        return $this->render('index',compact('provider'));
    }

    public function actionToggleAndSend()
    {
        $id = Yii::$app->request->post('id');
        $id = preg_replace('/[^0-9]/','',$id);
        Yii::$app->db->createCommand("update users set status = 1 - status where id = :id",['id' => $id])->execute();
        $query = Users::find();
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => false,
        ]);
        return $this->render('index',compact('provider'));
    }


    public function actionDelete($id)
    {
        Users::deleteAll(['id' => $id]);
        $query = Users::find();
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => false,
        ]);
        return $this->render('index',compact('provider'));
    }

    public function actionCash_history()
    {
        $searchModel = new CashSearch();
        $provider = $searchModel->search(Yii::$app->request->queryParams);

        $model = $provider->getModels();
        return $this->render('cash_h',compact('provider','model','searchModel'));
    }

    protected function findModel($id)
    {
        if (($model = Cash::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionCancel($id)
    {
        $model_cash = Cash::findOne($id);
        $model_user = Users::findOne($model_cash->id_user);
        $model_user->balans -= $model_cash->summa;
        $model_user->update();

        Cash::deleteAll(['id' => $id]);
        $searchModel = new CashSearch();
        $provider = $searchModel->search(Yii::$app->request->queryParams);

        $model = $provider->getModels();
        return $this->render('cash_h',compact('provider','model','searchModel'));
    }
}
