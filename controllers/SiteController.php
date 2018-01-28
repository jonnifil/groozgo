<?php

namespace app\controllers;

use app\models\Client;
use app\models\ClientAddress;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

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
                $model->save();
            }else{
                $model = new ClientAddress();
                $model->client_id = $address['client_id'];
                $model->name = $address['name'];
                $model->address = $address['address'];
                $model->save();
            }
            return 'saved';
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
            $model->save();
            return $model->id;
        }
        return 0;
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
