<?php

namespace app\controllers;

use app\models\Client;
use app\models\ClientAddress;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\Response;
class SiteController extends Controller
{


    /**
     * Displays homepage.
     * На главную выводим грид клиентов
     *
     * @return string
     */
    public function actionIndex()
    {
        $client_list = Client::find();
        $provider = new ActiveDataProvider([
            'query' => $client_list,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $this->render('index', [
            'provider' => $provider
        ]);
    }

    /**
     * Client action.
     *
     * @return Response
     * Карточка Клиента с возможностью редактирования и добавления адресов объектов
     */
    public function actionClient($id)
    {
        if ($id == 0){
            $client_data = [
                'id' => 0,
                'first_name' => '',
                'last_name' => '',
                'born_date' => '',
                'sex' => '',
                'phone' => '',
                'db_phone' => '',
                'db_sex' => ''
            ];
            return $this->render('client', [
                'client' => $client_data,
                'edit' => false
            ]);
        }
        $client_data = Client::findOneView($id);
        $address_list = ClientAddress::find()->where(['client_id' => $id]);
        $provider = new ActiveDataProvider([
            'query' => $address_list,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $this->render('client', [
            'client' => $client_data,
            'provider' => $provider,
            'edit' => $id > 0
        ]);
    }

    public function actionAddress()
    {
        $id = Yii::$app->request->post('id');
        $address = ClientAddress::find()
            ->where(['id'=>$id])
            ->asArray()
            ->one();
        return json_encode($address);
    }

    public function actionSave()
    {
        $address = Yii::$app->request->post('client_address');
        if ($address){
            if ($address['id'] > 0){
                $model = ClientAddress::findOne($address['id']);
                $model->name = $address['name'];
                $model->address = $address['address'];
            }else{
                $model = new ClientAddress();
                $model->client_id = $address['client_id'];
                $model->name = $address['name'];
                $model->address = $address['address'];
            }
            if ($model->validate()){
                $model->save();
                return 'saved';
            }else {
                $errors = $model->errors;
                return 'error';
            }
        }
        return 'error';
    }

    /**
     * @return int|mixed
     */
    public function actionCreate()
    {
        $client_data = Yii::$app->request->post('client_data');
        if ($client_data){
            if ($client_data['id'] > 0){
                $model = Client::findOne($client_data['id']);
            }else{
                $model = new Client();
            }
            $model->first_name = $client_data['first_name'];
            $model->last_name = $client_data['last_name'];
            $model->born_date = $client_data['born_date'];
            $model->sex = $client_data['sex'];
            $model->phone = $client_data['phone'];
            if ($model->validate()){
                $model->save();
                return $model->id;
            }else {
                $errors = $model->errors;
                return $client_data['id'] > 0 ? $client_data['id'] : 0;
            }
        }
        return 0;
    }


}
