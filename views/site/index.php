<?php


/* @var $this yii\web\View */
$this->title = 'groozgo test';
?>
<div class="site-index">

    <div class="jumbotron">
        <h2>Список клиентов</h2>
    </div>
    <div class="col-lg-12">
        <a class="btn btn-default pull-right" name="add" href="<?=Yii::$app->getUrlManager()->createUrl(['site/client','id'=>0]) ?>">
            <i class="glyphicon glyphicon-plus"></i> Добавить клиента
        </a>
        <div class="clearfix"></div>
    </div>

    <div class="body-content">
        <?php echo \yii\grid\GridView::widget([
            'dataProvider' => $provider,
            'columns' => [
                ['attribute' => 'last_name', 'header' => 'Фамилия'],
                ['attribute' => 'first_name', 'header' => 'Имя'],
                ['attribute' => 'sex',
                    'header' => 'Пол',
                    'value' => function ($data){ return $data->sex == 1 ? 'М' : 'Ж';}
                ],
                ['attribute' => 'born_date',
                    'header' => 'Дата рождения',
                    'value' => function ($data){
                        $born_date = new DateTime($data->born_date);
                        return $born_date->format('d,m,Y');
                    }
                ],
                ['attribute' => 'phone',
                    'header' => 'Телефон',
                    'value' => function ($data){
                        return '+7(' . substr($data->phone, 0, 3) . ')' . substr($data->phone, 3, 3) . '-' . substr($data->phone,6);
                    }
                ],

                [
                    'class' => '\yii\grid\ActionColumn',
                    'header' => 'Действия',
                    'template' => '{view}',
                    'buttons' => [
                        'view'=>function ($url, $model) {
                            $customurl=Yii::$app->getUrlManager()->createUrl(['site/client','id'=>$model['id']]);
                            return \yii\helpers\Html::a( '<span class="glyphicon glyphicon-eye-open"></span>', $customurl,
                                ['title' => Yii::t('yii', 'View'), 'data-pjax' => '0']);
                        }
                    ]
                ]
            ]
        ]); ?>
    </div>
</div>
